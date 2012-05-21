<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: view.html.php 2010-01-10 00:57:37 svn $
 * @author gobingoo.com
 * @package Joomla
 * @subpackage LISTBINGO
 * @license GNU/GPL
 *
 * A classified ad component from gobingoo.com.
 *
 * @code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

gbimport ( "gobingoo.template" );

/**
 * HTML View class for the Listbingo component
 */
class ListbingoViewAds extends GTemplate {
	function display($tpl = null) {
		
		global $mainframe, $option, $listitemid;
		$country = 0;
		$region = 0;
		$user = JFactory::getUser ();
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		$menus = & JSite::getMenu ();
		$menu = $menus->getActive ();
		$pathway = & $mainframe->getPathWay ();
		
		$query = "index.php?option=$option&task=ads&time=" . time () . "&Itemid=$listitemid";
		$mainframe->setUserState ( "hereiam", base64_encode ( $query ) );
		
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionmodel = gbimport ( "listbingo.model.region" );
		
		$country = $countrymodel->getCurrentCountry ( $params );
		$region = $regionmodel->getCurrentRegion ( $params );
		
		$countrytitle = $countrymodel->getCountryTitle ( $params );
		$regiontitle = $regionmodel->getRegionTitle ( $params );
		
		$forceads = $params->get ( 'force_ads', 0 );
		
		if ($country != 0 && $region != 0) {
			
			gbimport ( "gobingoo.currency" );
			
			$filter = new stdClass ();
			$catid = JRequest::getInt ( 'catid', 0 );
			
			$filter->limit = JRequest::getInt ( 'limit', $params->get ( 'ads_per_page', 10 ) );
			$filter->limitstart = JRequest::getInt ( 'limitstart', 0 );
			
			$filter->category_id = $catid;
			$filter->searchtxt = JRequest::getVar ( 'q', '' );
			$filter->alpha = JFilterOutput::cleanText ( substr ( JRequest::getVar ( 'alpha', '' ), 0, 6 ) );
			$filter->checkExpiryDate = 1;
			
			$order = $mainframe->getUserStateFromRequest ( $option . 'adsfilter_order', 'order', $params->get ( 'layout_ordering' ), 'cmd' );
			$filter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'adsfilter_order_Dir', 'dir', '', 'word' );
			
			$orderingtype = strtolower ( JFilterOutput::cleanText ( JRequest::getVar ( 'orderingtype', '' ) ) );
			if (! empty ( $orderingtype )) {
				if ($orderingtype == "desc") {
					$orderingtype = "asc";
				} else {
					$orderingtype = "desc";
				}
			} else {
				$orderingtype = "desc";
			}
			$filter->orderingtype = $orderingtype;
			switch ($order) {
				case 'latest' :
					$filter->order = "a.created_date $orderingtype";
					break;
				
				case 'price' :
					
					$filter->order = "a.price $orderingtype";
					break;
				
				default :
					$filter->order = "a.ordering $orderingtype";
					break;
			}
			
			$model = gbimport ( "listbingo.model.ad" );
			$filter->country = $country > 0 ? $country : 0;
			$filter->region = $region > 0 ? $region : 0;
			$filter->regiontitle = $regiontitle;
			$filter->expiry_days = $params->get ( 'expiry_days' );
			$filter->access = ( int ) $user->get ( 'aid', 0 );
			
			$filter->price_from = JRequest::getVar ( 'searchpricefrom', '' );
			$filter->price_from = JRequest::getVar ( 'searchpriceto', '' );
			
			$filter->params = $params;
			$rows = $model->getListWithInfobar ( true, $filter );
			
			//Add searches to queue for futher navigation
			

			if (count ( $rows ) > 1) {
				gbimport ( "listbingo.searchqueue" );
				$queue = new SearchQueue ();
				$queue->loadFromObjects ( $rows );
				$queue->save ();
			}
			
			$total = $model->getListCountForSearch ( true, $filter );
			jimport ( 'joomla.html.pagination' );
			
			$pagenav = new JPagination ( $total, $filter->limitstart, $filter->limit );
			
			/*
			 * shows category lists
			 */
			
			$catfilter = new stdClass ();
			
			$catfilter->order = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order', 'order', 'c.title', 'cmd' );
			$catfilter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order_Dir', 'dir', '', 'word' );
			
			$catfilter->country = $country;
			$catfilter->region = $region;
			$catfilter->countrytitle = $countrytitle;
			$catfilter->regiontitle = $regiontitle;
			$catfilter->catid = $catid;
			$catfilter->access = ( int ) $user->get ( 'aid', 0 );
			
			$catmodel = gbimport ( "listbingo.model.category" );
			$childcategories = $catmodel->getListForProduct ( true, $catfilter );
			
			if (! is_array ( $childcategories )) {
				
				$childcategories = array ($childcategories );
			}
			
			$category = $model->load ( $catid );
			
			if (is_object ( $menu )) {
				$menu_params = new JParameter ( $menu->params );
				
				if (! $menu_params->get ( 'page_title' )) {
					if ($category) {
						
						$params->set ( 'page_title', $category->title );
					} else {
						
						$params->set ( 'page_title', JText::_ ( "CATEGORY" ) );
					}
				}
			} else {
				if ($category) {
					$params->set ( 'page_title', $category->title );
				} else {
					
					$params->set ( 'page_title', JText::_ ( "CATEGORY" ) );
				}
			}
			
			/**
			 * Work on Pathways here
			 */
			/*if ($category) {
				
				$parents = $catmodel->getParentlist ( $catid );
				
				for($p = 0; $p < count ( $parents ); $p ++) {
					// Do not add the above and root categories when coming from a directory view					

					$pathway->addItem ( $this->escape ( $parents [$p]->title ), JRoute::_ ( 'index.php?option=' . $option . '&task=categories&catid=' . $parents [$p]->categoryslug ) );
				
				}
			}*/
			
			/*
			 * shows related category lists
			 */
			
			$relatedtable = & JTable::getInstance ( 'relatedcategory' );
			$relatedtable->id = $catid;
			$relatedcat = $relatedtable->getRelatedCategoryLists ();
			
			$db = JFactory::getDBO ();
			$nulldate = $db->getNullDate ();
			
			$userid = $user->get ( 'id' );
			
			$document = & JFactory::getDocument ();
			
			if (isset ( $childcategories [0] ) && $catid) {
				
				$document->setTitle ( html_entity_decode ( $childcategories [0]->title ) );
				
				$document->setMetadata ( 'keywords', html_entity_decode ( $childcategories [0]->title ) );
				
				$desc = GHelper::trunchtml ( trim ( strip_tags ( html_entity_decode ( $childcategories [0]->description ) ) ), 200 );
			
			}
			if (isset ( $rows ) && count ( $rows ) > 0) {
				foreach ( $rows as &$row ) {
					
					$row->title = JFilterOutput::cleanText ( $row->title );
					$row->id = JFilterOutput::cleanText ( $row->id );
					
					$row->address1 = JFilterOutput::cleanText ( $row->address1 );
					$row->address2 = JFilterOutput::cleanText ( $row->address2 );
					$row->price = JFilterOutput::cleanText ( $row->price );
				}
			}
			
			JFilterOutput::objectHTMLSafe ( $pagenav );
			JFilterOutput::objectHTMLSafe ( $filter );
			JFilterOutput::objectHTMLSafe ( $params );
			JFilterOutput::objectHTMLSafe ( $indcount );
			JFilterOutput::objectHTMLSafe ( $childcategories );
			JFilterOutput::objectHTMLSafe ( $relatedcat );
			JFilterOutput::objectHTMLSafe ( $user );
			
			GApplication::triggerEvent ( 'onBeforeListDisplay', array (&$rows, &$params ) );
			
			if (empty ( $filter->searchtxt )) {
				$filter->searchtxt = JText::_ ( 'ALL' );
			}
			
			$menus = &JSite::getMenu ();
			$menu = $menus->getActive ();
			$menu_params = new JParameter ( $menu->params );
			
			if ($menu_params && $menu_params->get('list_layout')!="") {
				$params->set ( 'default_listing_layout', $menu_params->get ( 'list_layout' ) );
			}
			$this->assignRef ( 'rows', $rows );
			$this->assignRef ( 'pagination', $pagenav );
			$this->assignRef ( 'filter', $filter );
			$this->assignRef ( 'params', $params );
			$this->assignRef ( 'indcount', $indcount );
			$this->assignRef ( 'categories', $childcategories );
			$this->assignRef ( 'relatedcat', $relatedcat );
			$this->assign ( 'user', $user );
			$this->assign ( 'userid', $userid );
			$this->assign ( 'guest', $user->guest );
			$this->assign ( 'nulldate', $nulldate );
			parent::display ( $tpl );
			GApplication::triggerEvent ( 'onAfterListDisplay', array (&$rows, &$params ) );
		
		} else {
			$redirlink = JRoute::_ ( "index.php?option=$option&task=regions&Itemid=$listitemid&time=" . time (), false );
			GApplication::redirect ( $redirlink );
		}
	}
	
	function searchDisplay($tpl = null) {
		parent::display ( $tpl );
	
	}
	
	function customDisplay($tpl = null) {
		parent::display ( $tpl );
	
	}
}
?>
