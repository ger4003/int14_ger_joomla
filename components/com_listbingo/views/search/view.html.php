<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: view.html.php 2010-01-10 00:57:37 svn $
 * @author GOBINGOO
 * @package Joomla
 * @subpackage LISTBINGO
 * @license GNU/GPL
 *
 * A classified ad component from GOBINGOO.
 *
 * code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

gbimport ( "gobingoo.template" );

/**
 * HTML View class for the ListBingo component
 */
class ListbingoViewSearch extends GTemplate {
	function display($tpl = null) {
		global $option, $listitemid;
		$mainframe = JFactory::getApplication ();
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$forceads = $params->get ( 'force_ads', 0 );
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionmodel = gbimport ( "listbingo.model.region" );
		
		$country = $countrymodel->getCurrentCountry ( $params );
		
		$region = $regionmodel->getCurrentRegion ( $params );
		if (! $forceads) {
			if (! $country) {
				$listlink = JRoute::_ ( "index.php?option=$option&task=countries&Itemid=$listitemid&time=" . time (), false );
				GApplication::redirect ( $listlink );
			}
			
			if (! $region) {
				$listlink = JRoute::_ ( "index.php?option=$option&task=regions&cid=$country&Itemid=$listitemid&time=" . time (), false );
				GApplication::redirect ( $listlink );
			
			}
		}
		
		$type = "";
		$type = JRequest::getVar ( 'type', '' );
		$filter = new stdClass ();
		
		$filter->searchtxt = JFilterOutput::cleanText ( JRequest::getVar ( 'q', '' ) );
		$filter->price_from = JRequest::getVar ( 'searchpricefrom', '' );
		$filter->price_to = JRequest::getVar ( 'searchpriceto', '' );
		$filter->search_type = JRequest::getVar ( 'search_type', '' );
		
		$this->assignRef ( 'filter', $filter );
		
		$menus = &JSite::getMenu ();
		$menu = $menus->getActive ();
		
		if ($menu) {
			
			$menu_params = new JParameter ( $menu->params );
			
			if ($menu_params->get ( 'advance_layout' ) != "" && $menu_params->get ( 'advance_layout', 0 ) == 1) {
				$this->setLayout ( 'advance' );
			
			} else {
				if ($type == "adv") {
					$this->setLayout ( 'advance' );
				} else {
					//default
				}
			}
		
		} else {
			if ($type == "adv") {
				$this->setLayout ( 'advance' );
			} else {
				//default
			}
		
		}
		
		$layout = $this->getLayout ();
		
		gbimport ( "listbingo.searchqueue" );
		$queue = new SearchQueue ();
		$queue->reset ();
		
		if ($layout == 'advance') {
			$filter->searchtype = $mainframe->getUserStateFromRequest ( $option . 'type', 'search_type', '', 'cmd' );
			$lists = array ();
			$catmodel = gbimport ( "listbingo.model.category" );
			
			$filter->id = 0;
			$filter->parent_id = 0;
			$cat_list = $catmodel->getTreeForSelect ( true, $filter );
			$categories = array ();
			$categories [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'SELECT_CATEGORY' ), 'value', 'text' );
			foreach ( $cat_list as $cat ) {
				
				$xtreename = str_replace ( ".", "", $cat->treename );
				$xtreename = str_replace ( "&nbsp;", "-", $xtreename );
				$xtreename = str_replace ( "<sup>|_</sup>", "", $xtreename );
				$categories [] = JHTML::_ ( 'select.option', $cat->value, JText::_ ( $xtreename ), 'value', 'text' );
			}
			$lists ['categories'] = JHTML::_ ( 'select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', JRequest::getInt ( 'catid' ) );
			
			$cmodel = gbimport ( "listbingo.model.country" );
			$countries1 = array ();
			$countries1 [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'SELECT_COUNTRIES' ), 'value', 'text' );
			
			$countries2 = $cmodel->getListForSelect ( true );
			$countries = array_merge ( $countries1, $countries2 );
			$lists ['countries'] = JHTML::_ ( 'select.genericlist', $countries, 'countries', 'class="inputbox" size="1"', 'value', 'text', $country);
			
			$this->assignRef('country', $country);
			
			$this->assignRef ( 'lists', $lists );
		}
		
		parent::display ( $tpl );
	}

}
?>