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
class ListbingoViewCategories extends GTemplate {
	
	function display($tpl = null) {
		
		global $mainframe, $option, $listitemid;
		
		$menus = & JSite::getMenu ();
		$menu = $menus->getActive ();
		
		$pathway = & $mainframe->getPathWay ();
		
		
		$cancel = JRequest::getInt ( 'cancel', 0 );
		
		if ($cancel) {
			$wherewasi = $mainframe->setUserState ( "hereiam", "" );
			
			$mainframe->setUserState ( $option . 'title', "" );
			$mainframe->setUserState ( $option . 'pricetype', "" );
			$mainframe->setUserState ( $option . 'currencycode', "" );
			$mainframe->setUserState ( $option . 'currency', "" );
			$mainframe->setUserState ( $option . 'price', "" );
			$mainframe->setUserState ( $option . 'zipcode', "" );
			$mainframe->setUserState ( $option . 'address1', "" );
			$mainframe->setUserState ( $option . 'address2', "" );
			$mainframe->setUserState ( $option . 'description', "" );
			$mainframe->setUserState ( $option . 'tags', '' );
			$mainframe->setUserState ( $option . 'metadesc', '' );
		
		}
		
		//$start = ListbingoHelper::microtime_float();
		

		$configmodel = gbimport ( "listbingo.model.configuration" );
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionmodel = gbimport ( "listbingo.model.region" );
		
		$params = $configmodel->getParams ();
		
		$country = $countrymodel->getCurrentCountry ( $params );
		$region = $regionmodel->getCurrentRegion ( $params );
		
		if ($country == 0) {
			$listlink = JRoute::_ ( "index.php?option=$option&task=countries&Itemid=$listitemid&time=" . time (), false );
			GApplication::redirect ( $listlink );
		}
		
		if ($region == 0) {
			$listlink = JRoute::_ ( "index.php?option=$option&task=regions&Itemid=$listitemid&cid=$country&time=" . time (), false );
			GApplication::redirect ( $listlink );
		
		}
		
		$filter = new stdClass ();
		
		$filter->order = $mainframe->getUserStateFromRequest ( $option . 'catfilter_order', 'order', 'c.title', 'cmd' );
		$filter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'catfilter_order_Dir', 'dir', '', 'word' );
		
		$filter->country = $country > 0 ? $country : 0;
		$filter->region = $region > 0 ? $region : 0;
		
		$catid = JRequest::getInt ( 'catid', 0 );
		$filter->catid = $catid;
		
		$user = JFactory::getUser ();
		$filter->access = ( int ) $user->get ( 'aid', 0 );
		
		$filter->params = $params;
		$model = gbimport ( "listbingo.model.category" );
		
		$categories = $model->getListForProduct ( true, $filter );
		
		if (! is_array ( $categories )) {
			
			$categories = array ($categories );
		}
		

		$category = $model->load($catid);
		
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
		if ($category) {
		
		
		
			$parents = $model->getParentlist ($catid);
		
			for($p = 0; $p < count ( $parents ); $p ++) {
				// Do not add the above and root categories when coming from a directory view
	
					$pathway->addItem ( $this->escape ( $parents [$p]->title ), JRoute::_ ('index.php?option='.$option.'&task=categories&catid='.$parents [$p]->categoryslug ) ) ;

			}
		}
		
		$wherewasi = $mainframe->getUserState ( "hereiam" );
		
		GApplication::triggerEvent ( 'onBeforeListDisplay', array (&$rows, &$params ) );
		$this->assignRef ( 'categories', $categories );
		$this->assignRef ( 'filter', $filter );
		$this->assignRef ( 'params', $params );
		$this->assignRef ( 'indcount', $indcount );
		$this->assignRef ( 'wherewasi', $wherewasi );
		parent::display ( $tpl );
		GApplication::triggerEvent ( 'onAfterListDisplay', array (&$rows, &$params ) );
	
	}
	
	function searchDisplay($tpl = null) {
		parent::display ( $tpl );
	
	}

}
?>