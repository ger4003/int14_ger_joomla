<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: category.php 2010-01-10 00:57:37 svn $
 * @author GOBINGOO
 * @package Joomla
 * @subpackage LISTBINGO
 * @license GNU/GPL
 *
 * A classified ad component from gobingoo.com.
 *
 * code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Import Joomla! libraries
gbimport ( "gobingoo.model" );
jimport ( 'joomla.filter.input' );

class ListbingoModelCategory extends GModel {
	var $_count;
	
	var $_pagination = null;
	
	function __construct() {
		parent::__construct ();
	}
	
	function getPriceTypeCategories() {
		$db = JFactory::getDBO ();
		$query = "SELECT id,hasprice FROM #__gbl_categories WHERE published='1'";
		$db->setQuery ( $query );
		return $db->loadObjectList ();
	}
	
	function _countAds(&$cat = null) {
		if (is_null ( $cat )) {
			$cat->adcount = 0;
			return 0;
		}
		
		if (isset ( $cat->child ) && count ( $cat->child ) > 0) {
			if(isset($cat->pcount))
			{
				$total = $cat->pcount;
			}
			else
			{
				$total = 0;
			}
			foreach ( $cat->child as &$child ) {
				$total += self::_countAds ( $child );
			}
			
			$cat->adcount = $total;
			
			return $total;
		
		} else {
			$cat->adcount = $cat->pcount;
			return $cat->pcount;
		
		}
	}
	
	function getListForProduct($published = false, $filter = array()) {
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$db = &JFactory::getDBO ();
		
		$pubcond = "";
		$condition = array ();
		if ($published) {
			$condition [] = " c.published='1'";
		}
		
		if (isset ( $filter->access )) {
			$condition [] = " c.access<=$filter->access";
		}
		
		if (count ( $condition ) > 0) {
			$pubcond = "where " . implode ( " AND ", $condition );
		}
		
		$country="";
		$region="";
		
		if (isset ( $filter->country ) && $filter->country>0) {
			$country = "AND country_id=" . $filter->country;
		}
		
		if (isset ( $filter->region ) && $filter->region>0) {
			$region = "AND region_id=" . $filter->region;
		}

		
		$orderby = ' ORDER BY c.parent_id asc' . $filter->order_dir . ",c.ordering ASC";
$expired = " AND (expiry_date > NOW() || expiry_date='" . $db->getNullDate () . "')";
		
		$query = "SELECT c.*,c.title as name,CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as slug,
		(SELECT count(id) FROM #__gbl_ads WHERE category_id=c.id AND status=1 $expired $country $region) as pcount,
		(SELECT ifnull(id,0) from #__gbl_categories where id=c.parent_id) as parentexists
			FROM #__gbl_categories as c $pubcond $orderby";
		$db->setQuery ( $query );
		$categories = $db->loadObjectList ( 'id' );
		
		if (count ( $categories ) > 0) {
			$removearray = array ();
			foreach ( $categories as $cat ) {
				
				if ($cat->parent_id > 0 && $cat->parentexists) {
					$categories [$cat->parent_id]->child [] = $cat;
					$categories [$cat->parent_id]->childrencount ++;
					array_push ( $removearray, $categories [$cat->id] );
				} elseif ($cat->parent_id > 0 && ! $cat->parentexists) {
					array_push ( $removearray, $categories [$cat->id] );
				
				}
			}
			//Now remove the unwanted array members except the one that is currently asked for
			foreach ( $removearray as &$remarr ) {
				if ($filter->catid != $remarr->id) {
					unset ( $categories [$remarr->id] );
				}
			}
		}
		
		foreach ( $categories as &$cat ) {
			$cat->adcount = self::_countAds ( $cat );
		
		}
		
		if ($filter->catid > 0) {
			if (isset ( $categories [$filter->catid]->child )) {
				
				return $categories [$filter->catid]->child;
			} else {
				return array ();
			}
		} else {
			return $categories;
		}
	
	}
	
	function getList($filter = array()) {
		
		global $mainframe;
		
		$db = JFactory::getDBO ();
		
		$cond = array ();
		switch ($filter->published) {
			
			case 0 :
			case 1 :
				$cond [] = " c.published=$filter->published";
				break;
			case - 1 :
				break;
		
		}
		
		$textcondition = ($filter->keyword == 'all') || empty ( $filter->keyword );
		
		if (! $textcondition) {
			
			$words = explode ( ' ', $filter->keyword );
			$wheres = array ();
			
			foreach ( $words as $word ) {
				$nativeword = $word;
				$word = $db->Quote ( '%' . $db->getEscaped ( $word, true ) . '%', false );
				$wheres2 = array ();
				
				$wheres2 [] = 'c.title LIKE ' . $word;
				//$wheres2 [] = 'c.description LIKE ' . $word;
				

				if (count ( $wheres2 ) > 0) {
					$wheres [] = implode ( ' OR ', $wheres2 );
				}
			}
			if (count ( $wheres ) > 0) {
				$cond [] = '(' . implode ( ') OR (', $wheres ) . ')';
			}
		}
		
		$condition = "";
		
		if (count ( $cond ) > 0) {
			$condition = " WHERE " . implode ( " AND ", $cond );
		}
		

		$orderby = ' ORDER BY ' . $filter->order . ' ' . $filter->order_dir . ",c.parent_id asc,c.ordering";
		
		$query = "SELECT c.*,c.title as name,
		CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as slug, 
		c.parent_id as parent from #__gbl_categories as c
		$condition $orderby";
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		
		$children = array ();
		// first pass - collect children
		foreach ( $rows as $v ) {
			$pt = $v->parent;
			$list = @$children [$pt] ? $children [$pt] : array ();
			array_push ( $list, $v );
			$children [$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = JHTML::_ ( 'menu.treerecurse', 0, '', array (), $children, max ( 0, $filter->levellimit - 1 ) );
		
		$newlist = array ();
		foreach ( $list as $item ) {
			$newlist [] = $item;
		}
		
		$total = count ( $newlist );
		$this->_count = $total;
		
		jimport ( 'joomla.html.pagination' );
		$this->_pagination = new JPagination ( $total, $filter->limitstart, $filter->limit );
		
		// slice out elements based on limits
		$newlist = array_slice ( $newlist, $this->_pagination->limitstart, $this->_pagination->limit );
		
		return $newlist;
	
	}
	
	function getListCount() {
		return $this->_count;
	}
	
	function &getPagination() {
		if ($this->_pagination == null) {
			$this->getItems ();
		}
		
		return $this->_pagination;
	}
	
	function getListForSelect($published = false, $id = 0, $rootonly = true, $type = null) {
		$pubcond = "";
		if ($published) {
			$pubcond = " and published='1'";
		}
		
		$orderby = ' ORDER BY  ordering';
		$rootcond = "";
		if ($rootonly) {
			$rootcond = " and parent_id=0";
		}
		$typecond = "";
		if (! is_null ( $type )) {
			
			$typecond = " and type_id in ($type)";
		}
		
		$db = JFactory::getDBO ();
		$query = "SELECT id as value, title as text,type_id from #__gbl_categories where id !=$id $pubcond $rootcond $typecond $orderby";
		$db->setQuery ( $query );
		return $db->loadAssocList ();
	
	}
	
	function getTreeForSelect($published = false, $filter = array()) {
		$wheres = array ();
		
		// If a not a new item, lets set the menu item id
		if ($filter->id) {
			$wheres [] = ' c.id != ' . ( int ) $filter->id;
		} else {
			$wheres [] = ' c.id != ' . ( int ) $filter->id;
		}
		
		if ($published) {
			$wheres [] = " c.published='1'";
		}
		
		if (count ( $wheres ) > 0) {
			$cond = " WHERE " . implode ( " AND ", $wheres );
		}
		
		$orderby = ' ORDER BY c.parent_id asc,c.ordering';
		
		$db = JFactory::getDBO ();
		$query = "SELECT c.*, c.id as value,c.title as name,c.parent_id as parent from #__gbl_categories as c
		$cond $orderby";
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		$children = array ();
		// first pass - collect children
		foreach ( $rows as $v ) {
			$pt = $v->parent_id;
			$list = @$children [$pt] ? $children [$pt] : array ();
			array_push ( $list, $v );
			/*if($filter->id && $v->parent_id==$filter->parent_id && $filter->parent_id>=0)
			 {

			 }
			 else
			 {
				$children[$pt] = $list;
				}*/
			$children [$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = JHTML::_ ( 'menu.treerecurse', 0, '', array (), $children, 9999, 0, 0 );
		$newlist = array ();
		foreach ( $list as $item ) {
			
			$newlist [] = $item;
		
		}
		
		return $newlist;
	
	}
	
	function publish($task, $cid) {
		if ($task == 'publish') {
			$publish = '1';
		} else {
			$publish = '0';
		}
		$table = & JTable::getInstance ( 'category' );
		
		return $table->publish ( $cid, $publish );
	
	}
	
	function load($id) {
		$table = JTable::getInstance ( "category" );
		$table->load ( $id );
		return $table;
	
	}
	
	function save($post = null, $file = null, $params = null) {
		
		if (! is_array ( $post )) {
			throw new DataException ( JText::_ ( "INVALID_DATA" ), 400 );
		}
		
		$related = $post ['related'];
		
		$row = JTable::getInstance ( "category" );
		if (! $row->bind ( $post )) {
			throw new DataException ( JText::_ ( "NO_BIND" ) . $row->getError (), 401 );
		}
		
		if (! $row->id) {
			$row->ordering = $row->getNextOrder ( 'parent_id=' . $row->parent_id );
		}
		
		if (! $row->check ()) {
			throw new DataException ( $row->getError (), 402 );
		}
		
		if (! $row->store ( $file, $params, $related )) {
			throw new DataException ( JText::_ ( "NO_SAVE" ) . $row->getError (), 402 );
		}
		
		//$this->saveImages($images,$row->id);
		

		return $row->id;
	}
	
	function remove($cid = array()) {
		
		$db = JFactory::getDBO ();
		if (count ( $cid )) {
			$cids = implode ( ',', $cid );
			
			$db->setQuery ( "SELECT * FROM #__gbl_categories WHERE id not IN ($cids) AND parent_id IN ($cids)" );
			if ($db->loadResult ()) {
				throw new DataException ( JText::_ ( "LISTBINGO_DELETE_CATEGORY_SELECT_CHIDLS" ), 400 );
			}
			
			$query = "DELETE from #__gbl_categories where id in ($cids)";
			$db->setQuery ( $query );
			if (! $db->query ()) {
				throw new DataException ( JText::_ ( "NO_DELETE" ), 400 );
			}
			return true;
		}
	}
	
	function saveorder($cid, $order, $total) {
		$db = & JFactory::getDBO ();
		$row = & JTable::getInstance ( 'category' );
		$groupings = array ();
		
		// update ordering values
		for($i = 0; $i < $total; $i ++) {
			$row->load ( ( int ) $cid [$i] );
			// track categories
			

			if ($row->ordering != $order [$i]) {
				$row->ordering = $order [$i];
				if (! $row->store ()) {
					throw new DataException ( JText::_ ( "NO_ORDER_SAVE" ), 500 );
				}
			}
		}
	
	}
	
	function order($task, $id) {
		
		if ($task == 'orderup') {
			
			$dir = - 1;
		} else {
			$dir = 1;
		}
		$row = & JTable::getInstance ( 'category' );
		$row->load ( $id );
		
		return $row->move ( $dir, 'parent_id=' . $row->parent_id );
	
	}
	
	function getExtrafields($cid = 0, $id = 0, $published = true) {
		$pubcond = "";
		if ($published) {
			$pubcond = " and f.published='1'";
		
		}
		
		$parents = $this->getParentList ( $cid );
		
		$parray = array ();
		$exarray = $cid;
		
		if (count ( $parents ) > 0) {
			foreach ( $parents as $p ) {
				$parray [] = $p->id;
			}
			
			$exarray = implode ( ',', $parray );
		} else {
			$exarray = $cid;
		}
		
		$query = "SELECT distinct(f.id), f.* from #__gbl_fields as f left join #__gbl_categories_fields as cf on cf.field_id=f.id where cf.category_id IN ($exarray) $pubcond order by ordering";
		$db = JFactory::getDBO ();
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		
		if (count ( $rows ) > 0) {
			
			foreach ( $rows as &$r ) {
				
				$query = "SELECT field_value  from #__gbl_ads_fields where field_id='$r->id' and ad_id='$id'";
				$db->setQuery ( $query );
				$fields = $db->loadResultArray ();
				if (count ( $fields ) > 0) {
					$r->field_value = $fields;
					if (count ( $fields ) < 2) {
						$r->field_value = $fields [0];
					}
				} else {
					$r->field_value = $r->default_value;
				}
			
			}
		}
		
		return $rows;
	}
	
	function saveImages($images = null, $id = 0) {
		
		global $option;
		$params = &JComponentHelper::getParams ( $option );
		
		if (is_null ( $images )) {
			return false;
		}
		
		gbimport ( "gobingoo.upload" );
		$uploader = new GUpload ();
		$returnvar = array ();
		
		$thumbparams = new stdClass ();
		$thumbparams->prefix = $params->get ( 'prefix' );
		$thumbparams->saveoriginal = $params->get ( 'saveoriginal' );
		$thumbparams->convert = $params->get ( 'convertto' );
		
		$uploadfolder = $params->get ( 'imagespath' );
		if (strpos ( $uploadfolder, "/" ) == 0) {
			$thumbparams->uploadfolder = JPATH_ROOT . $uploadfolder . DS . $id;
		} else {
			$thumbparams->uploadfolder = JPATH_ROOT . DS . $uploadfolder . DS . $id;
		}
		
		$thumbnails = array ();
		$ratio = $params->get ( 'saveproportions' );
		
		if ($params->get ( 'enable_thumbnail_sml' )) {
			$thumbnail = new stdClass ();
			$thumbnail->suffix = $params->get ( 'suffix_thumbnail_sml' );
			$thumbnail->width = $params->get ( 'width_thumbnail_sml' );
			$thumbnail->height = $params->get ( 'height_thumbnail_sml' );
			$thumbnail->y = $params->get ( 'y_thumbnail_sml' );
			$thumbnail->x = $params->get ( 'x_thumbnail_sml' );
			$thumbnail->ration = $ratio;
			$thumbnail->resize = $params->get ( 'resize_thumbnail_sml' );
			$thumbnails [] = $thumbnail;
		}
		
		if ($params->get ( 'enable_thumbnail_mid' )) {
			$thumbnail = new stdClass ();
			$thumbnail->suffix = $params->get ( 'suffix_thumbnail_mid' );
			$thumbnail->width = $params->get ( 'width_thumbnail_mid' );
			$thumbnail->height = $params->get ( 'height_thumbnail_mid' );
			$thumbnail->y = $params->get ( 'y_thumbnail_mid' );
			$thumbnail->x = $params->get ( 'x_thumbnail_mid' );
			$thumbnail->ration = $ratio;
			$thumbnail->resize = $params->get ( 'resize_thumbnail_mid' );
			$thumbnails [] = $thumbnail;
		}
		
		if ($params->get ( 'enable_thumbnail_lrg' )) {
			$thumbnail = new stdClass ();
			$thumbnail->suffix = $params->get ( 'suffix_thumbnail_lrg' );
			$thumbnail->width = $params->get ( 'width_thumbnail_lrg' );
			$thumbnail->height = $params->get ( 'height_thumbnail_lrg' );
			$thumbnail->y = $params->get ( 'y_thumbnail_lrg' );
			$thumbnail->x = $params->get ( 'x_thumbnail_lrg' );
			$thumbnail->ration = $ratio;
			$thumbnail->resize = $params->get ( 'resize_thumbnail_lrg' );
			$thumbnails [] = $thumbnail;
		}
		
		$thumbparams->thumbnails = $thumbnails;
		
		$returns = $uploader->thumbnailArray ( $images, $thumbparams );
		
		if (count ( $returns->uploaded ) > 0) {
			foreach ( $returns->uploaded as $img ) {
				$table = JTable::getInstance ( 'category' );
				$table->id = $id;
				$imgtable->logo = $img->filename;
				$table->extension = $img->extension;
				$table->store ();
			}
		}
		
		GApplication::message ( $returns->success . " " . JText::_ ( "IMG_UPLOAD_SUCCESS" ) );
		
		return true;
	
	}
	
	/*function getAllParents($id = null) {
		
		$db = JFactory::getDBO ();
		
		$query = "SELECT c.id as cid, c.title as ctitle, c.description as cdesc,p.id, p.title, p.description
		FROM #__gbl_categories as c
		LEFT JOIN #__gbl_categories as p ON (c.parent_id=p.id) WHERE c.id=$id";
		$db->setQuery ( $query );
		return $db->loadObjectList ();
	}*/
	
	function _getParents($id) {
		$parents = array ();
		$db = JFactory::getDBO ();
		$query = "SELECT id,title,parent_id,
		CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as categoryslug FROM #__gbl_categories WHERE id=" . ( int ) $id;
		$db->setQuery ( $query );
		$obj = $db->loadObject ();
		if ($obj) {
			$parents [] = $obj;
			return array_merge ( $parents, self::_getParents ( $obj->parent_id ) );
		} else {
			return $parents;
		}
	}
	
	function getParentList($id) {
		return array_reverse ( self::_getParents ( $id ) );
	}
	
	function _getChildArray($cat = null) {
		if (is_null ( $cat ) || ! is_object ( $cat )) {
			
			return array ();
		
		}
		
		$array = array ($cat->id );
		
		if (isset ( $cat->child ) && count ( $cat->child ) > 0) {
			
			foreach ( $cat->child as &$child ) {
				$array = array_merge ( $array, self::_getChildArray ( $child ) );
			}
		
		}
		
		return $array;
	
	}
	
	function _getChildrens($cid, $access = 0) {
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$db = &JFactory::getDBO ();
		
		$pubcond = "";
		$condition = array ();
		
		$condition [] = " c.published='1'";
		
		if (isset ( $access )) {
			$condition [] = " c.access<=$access";
		}
		
		if (count ( $condition ) > 0) {
			$pubcond = "where " . implode ( " AND ", $condition );
		}
		
		$orderby = ' ORDER BY c.parent_id asc, ordering asc';
		
		$query = "SELECT c.id,c.parent_id,(select ifnull(id,0) from #__gbl_categories where id=c.parent_id) as parentexists
			FROM #__gbl_categories as c $pubcond $orderby";
		$db->setQuery ( $query );
		$categories = $db->loadObjectList ( 'id' );
		
		if (count ( $categories ) > 0) {
			$removearray = array ();
			foreach ( $categories as $cat ) {
				
				if ($cat->parent_id > 0 && $cat->parentexists) {
					$categories [$cat->parent_id]->child [] = $cat;
					$categories [$cat->parent_id]->childrencount ++;
					array_push ( $removearray, $categories [$cat->id] );
				} elseif ($cat->parent_id > 0 && ! $cat->parentexists) {
					array_push ( $removearray, $categories [$cat->id] );
				
				}
			}
			
			//Now remove the unwanted array members except the one that is currently asked for
			foreach ( $removearray as &$remarr ) {
				if ($cid != $remarr->id) {
					unset ( $categories [$remarr->id] );
				}
			}
		}
		
		$categories_temp = array ();
		if ($cid > 0) {
			if (isset ( $categories [$cid] )) {
				
				$categories_temp = array ($categories [$cid] );
			} else {
				return array ();
			}
		} else {
			$categories_temp = $categories;
		}
		
		$return_array = array ();
		
		if (count ( $categories_temp ) > 0) {
			foreach ( $categories_temp as $c ) {
				$return_array = array_merge ( $return_array, self::_getChildArray ( $c ) );
			}
		
		}
		
		return $return_array;
	
	}
	
	function _countTotalProducts($filter) {
		$db = JFactory::getDBO ();
		
		$configmodel = gbimport ( 'listbingo.model.configuration' );
		$params = $configmodel->getParams ();
		$regmodel = gbimport ( "listbingo.model.region" );
		$pubcond = "";
		$cond = array ();
		
		$cond [] = " (a.expiry_date > NOW() || a.expiry_date='" . $db->getNullDate () . "')";
		
		if (isset ( $filter->access )) {
			$condition = " AND cat.access<=$filter->access";
		}
		
		if ((isset ( $filter->country ) && $filter->country > 0)) {
			
			$cond [] = "(a.country_id=" . $filter->country . ")";
		}
		
		if (isset ( $filter->region ) && ! is_array ( $filter->region )) {
			$filter->region = array ($filter->region );
		}
		
		if (isset ( $filter->region ) && count ( $filter->region ) > 0) {
			
			$regionmodel = gbimport ( "listbingo.model.region" );
			$regions = array ();
			foreach ( $filter->region as $r ) {
				if ($r > 0) {
					$regions [] = $r;
					$xregions = $regionmodel->getSubRegion ( $r );
					$regions = array_merge ( $regions, $xregions );
				}
			}
			$regioncondition = "";
			if (count ( $regions ) > 0) {
				$regioncondition = implode ( ", ", $regions );
				$cond [] = "(a.region_id  in (" . $regioncondition . "))";
			}
		
		}
		
		if (count ( $cond ) > 0) {
			$pubcond = " AND " . implode ( " AND ", $cond );
		}
		
		$query = "SELECT	cat.title, cat.id, " . "(" . "SELECT	count(a.id) " . "FROM	#__gbl_ads as a " . "WHERE	a.category_id = cat.id AND a.status=1 $pubcond" . ") AS adCount " . "FROM	#__gbl_categories as cat WHERE published=1 $condition";
		$db->setQuery ( $query );
		return $db->loadObjectList ();
	
	}
	
	function getSlug($id = 0) {
		$db = JFactory::getDBO ();
		
		$query = "SELECT id, CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug FROM #__gbl_categories WHERE id=$id";
		$db->setQuery ( $query );
		$category = $db->loadObject ();
		$slug = $category->slug;
		
		return $slug;
	
	}
	
	function getId($name = '') {
		$db = JFactory::getDBO ();
		
		$query = "SELECT id FROM #__gbl_categories WHERE title='$name'";
		$db->setQuery ( $query );
		return $db->loadObject ();
	}
	
	function getCategories($published = 1, $filter = null) {
		
		$condition = "";
		
		$cond = array ();
		
		if ($published) {
			
			$cond [] = " cat.published=1";
		}
		
		if (isset ( $filter->access )) {
			$cond [] = " cat.access<=" . $filter->access;
		}
		
		if (count ( $cond ) > 0) {
			
			$condition = " WHERE " . implode ( " AND ", $cond );
		}
		
		$expirycondition = "";
		
		$query = "SELECT cat.*,(select count(id) from #__gbl_ads where category_id=cat.id and status='1' $expirycondition ) as adcount from #__gbl_categories as cat $condition";
		$rows = $this->_getList ( $query );
		
		$categorytree = array ();
		if (count ( $rows ) > 0) {
			foreach ( $rows as $r ) {
				$categorytree [$r->id] = $r;
				$categorytree [$r->id]->totalcount = $r->adcount;
				if ($r->parent_id > 0) {
					$categorytree [$r->parent_id]->children [] = $r;
					$categorytree [$r->parent_id]->totalcount += $r->adcount;
				
				}
			
			}
		
		}
		
		if (isset ( $filter->catid )) {
			$returntree = array ();
			$returntree = $categorytree [$filter->catid];
			/*	echo "<pre>";
			print_r ( $returntree );
			echo "</pre>";*/
		} else {
			/*echo "<pre>";
			print_r ( $categorytree );
			echo "</pre>";*/
		}
	
	}
	
	function find(&$title = null, $access = 0) {
		global $option;
		
		$replacearray = array ('in', 'of', 'over', 'on', 'near', '+', 'and', 'or' );
		$tmpwords = explode ( "+", $title );
		
		$tmpwords = implode ( " + ", $tmpwords );
		$filterwords = explode ( " ", $tmpwords );
		$filterwords = array_filter ( $filterwords );
		
		$filterkey = array_diff ( $filterwords, $replacearray );
		
		$db = JFactory::getDBO ();
		
		$wheres = array ();
		$cond = array ();
		$condition = "";
		
		foreach ( $filterkey as $word ) {
			$word = $db->Quote ( '%' . $db->getEscaped ( $word, true ) . '%', false );
			$wheres [] = 'title LIKE ' . $word;
		}
		
		if (count ( $wheres ) > 0) {
			$cond [] = '(' . implode ( ') OR (', $wheres ) . ')';
		}
		
		$cond [] = " published='1'";
		
		$cond [] = " access<=$access ";
		
		if (count ( $cond ) > 0) {
			$condition = " WHERE " . implode ( " AND ", $cond );
		}
		
		$query = "SELECT id, title,
 		CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(':', id, alias) ELSE id END as slug
		FROM #__gbl_categories $condition";
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		
		$catid = array ();
		$cattitle = array ();
		
		$catobj = new stdClass ();
		
		if (count ( $rows ) > 0) {
			foreach ( $rows as $r ) {
				$catx = self::_getChildrens ( $r->id, $access );
				$catid [] = $r->id;
				if (count ( $catx ) > 0) {
					$catid = array_merge ( $catid, $catx );
				}
			
			}
			$catid = array_unique ( $catid );
			
			$cattitle = self::_getChildrensTitle ( $catid );
			
			$catobj->catid = $catid;
			
			$catobj->cattitle = array_unique ( $cattitle );
			
			return $catobj;
		
		} else {
			return false;
		}
	
	}
	
	function _getChildrensTitle($cid = array()) {
		
		if (count ( $cid ) < 1) {
			return false;
		}
		
		$db = JFactory::getDBO ();
		
		$catids = implode ( ",", $cid );
		
		$query = "SELECT title from #__gbl_categories where id in ($catids) order by ordering ASC";
		$db->setQuery ( $query );
		$rows = $db->loadResultArray ();
		
		return $rows;
	
	}

}
?>
