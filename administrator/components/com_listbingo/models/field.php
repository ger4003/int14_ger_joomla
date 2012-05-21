<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: field.php 2010-01-10 00:57:37 svn $
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

class ListbingoModelField extends GModel {
	
	var $_count=0;
	
	function __construct() {
		parent::__construct ();
	}
	
	function getList($published = false, $filter = array()) {
		
		$db = JFactory::getDBO ();
		
		$cond = array ();
		switch ($filter->published) {
			
			case 0 :
			case 1 :
				$cond [] = " f.published=$filter->published";
				break;
			case - 1 :
				break;
		
		}
		
		if(!empty($filter->type))
		{
			$cond[] = " f.type='".$filter->type."'";	
		}
		
		$textcondition = ($filter->keyword == 'all') || empty ( $filter->keyword );
		
		if (! $textcondition) {
			
			$words = explode ( ' ', $filter->keyword );
			$wheres = array ();
			
			foreach ( $words as $word ) {
				$nativeword = $word;
				$word = $db->Quote ( '%' . $db->getEscaped ( $word, true ) . '%', false );
				$wheres2 = array ();
				
				$wheres2 [] = 'f.title LIKE ' . $word;
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
		$orderby = ' ORDER BY ' . $filter->order . ' ' . $filter->order_dir . ', ordering';
		
		
		$query = "SELECT f.*,g.name as groupname from #__gbl_fields as f left join #__groups as g on g.id=f.access $condition $orderby";
		$db->setQuery ( $query, $filter->limitstart, $filter->limit );
		$rows = $db->loadObjectList ();
		$this->_count = $this->_getListCount ( $query );
		return $rows;
	}
	
	function getListCount($published = false) {
		/*$pubcond = "";
		if ($published) {
			$pubcond = " where published='1'";
		}
		
		$db = JFactory::getDBO ();
		$query = "SELECT count(*) from #__gbl_fields $pubcond";
		$db->setQuery ( $query );
		return $db->loadResult ();*/
		
		return $this->_count;
	}
	
	function getListForSelect($published = false) {
		$pubcond = "";
		if ($published) {
			$pubcond = " where published='1'";
		}
		
		$orderby = ' ORDER BY  ordering';
		
		$db = JFactory::getDBO ();
		$query = "SELECT id as value, title as text from #__gbl_fields $pubcond $orderby";
		$db->setQuery ( $query );
		return $db->loadAssocList ();
	
	}
	
	function getListForSelect2($published = false) {
		$pubcond = "";
		if ($published) {
			$pubcond = " and published='1'";
		}
		
		$orderby = ' ORDER BY  ordering';
		
		$db = JFactory::getDBO ();
		$query = "SELECT id as value, title as text from #__gbl_fields where type not in ('text','textarea','email','date','url','attachment') $pubcond $orderby";
		
		$db->setQuery ( $query );
		
		return $db->loadAssocList ();
	
	}
	function publish($task, $cid) {
		if ($task == 'publish') {
			$publish = '1';
		} else {
			$publish = '0';
		}
		$table = & JTable::getInstance ( 'field' );
		
		return $table->publish ( $cid, $publish );
	
	}
	
	function load($id) {
		$table = JTable::getInstance ( "field" );
		$table->load ( $id );
		return $table;
	
	}
	
	function save($post = null) {
		
		if (! is_array ( $post )) {
			throw new DataException ( JText::_ ( "INVALID_DATA" ), 400 );
		}
		
		$row = JTable::getInstance ( "field" );
		if (! $row->bind ( $post )) {
			throw new DataException ( JText::_ ( "NO_BIND" ) . $row->getError (), 401 );
		}
		
		if (! $row->id) {
			$row->ordering = $row->getNextOrder ();
		}
		
		if (! $row->check ()) {
			throw new DataException ( $row->getError (), 402 );
		}
		
		if (! $row->store ( $post ['categories'] )) {
			throw new DataException ( JText::_ ( "NO_SAVE" ) . $row->getError (), 402 );
		}
		return $row->id;
	}
	
	function remove($cid = array()) {
		
		$db = JFactory::getDBO ();
		if (count ( $cid )) {
			$cids = implode ( ',', $cid );
			$query = "DELETE from #__gbl_fields where id in ($cids)";
			$db->setQuery ( $query );
			if (! $db->query ()) {
				throw new DataException ( JText::_ ( "NO_DELETE" ), 400 );
			}
			return true;
		}
	}
	
	function saveorder($cid, $order, $total) {
		$db = & JFactory::getDBO ();
		$row = & JTable::getInstance ( 'field' );
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
		$row = & JTable::getInstance ( 'field' );
		$row->load ( $id );
		
		return $row->move ( $dir, '' );
	
	}
}
?>