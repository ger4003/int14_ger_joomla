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
 * HTML View class for the listBingo component
 */
class ListbingoViewRegions extends GTemplate {
	function display($tpl = null) {
		
		global $option, $listitemid;
		
		$mainframe = JFactory::getApplication ();
		
		$cid = JRequest::getInt ( 'cid', 0 );
		$rid = JRequest::getInt ( 'rid', 0 );
		
		$mainframe->setUserState ( $option . 'country', $cid );
		$mainframe->setUserState ( $option . 'region', 0 );
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionsmodel = gbimport ( "listbingo.model.region" );
		
		$params = $configmodel->getParams ();
		
		$country = $countrymodel->getCurrentCountry ( $params );
		
		$region = $regionsmodel->getCurrentRegion ( $params );
		
		if ($country == 0) {
			$countrylink = JRoute::_ ( "index.php?option=$option&task=countries&Itemid=$listitemid&time=" . time (), false );
			GApplication::redirect ( $countrylink );
		
		}
		
		$filter = new stdClass ();
		$this->setLayout ( "compact" );
		
		$regions = $regionsmodel->getRegionsWithChild ( $country, $rid );
		
		if (! is_array ( $regions )) {
			
			$regions = array ($regions );
		}
		
		if (count ( $regions ) > 0) {
			foreach ( $regions as $r ) {
				
				if (isset ( $r->child ) && count ( $r->child ) > 0) {
					$r->link = JRoute::_ ( "index.php?option=$option&Itemid=$listitemid&task=regions&cid=$cid&rid=$r->slug&time=" . time () );
				} else {
					$r->link = JRoute::_ ( "index.php?option=$option&Itemid=$listitemid&task=regions.region&rid=$r->slug&time=" . time () );
				}
			}
		}
		if (! count ( $regions ) || ! $params->get ( 'region_selection', 0 )) {
			$link = JRoute::_ ( "index.php?option=$option&task=categories&Itemid=$listitemid&time=" . time (), false );
			GApplication::redirect ( $link );
		}
		
		$this->assignRef ( 'regions', $regions );
		$this->assignRef ( 'params', $params );
		
		parent::display ( $tpl );
	
	}
	
	function customDisplay($tpl = null) {
		parent::display ( $tpl );
	}
}
?>