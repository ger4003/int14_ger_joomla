<?php
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$layout = $params->get ( 'searchlayout', 'simple' );

if ($layout == "advance") {
	
	require_once (dirname ( __FILE__ ) . DS . 'helper.php');

	$filter->id = 0;
	$filter->parent_id = 0;
	$cat_list = modLbSearchHelper::getTreeForSelect ( true, $filter );
	$categories = array ();
	$categories [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'SELECT_CATEGORY' ), 'value', 'text' );
	foreach ( $cat_list as $cat ) {
		
		$xtreename = str_replace ( ".", "", $cat->treename );
		$xtreename = str_replace ( "&nbsp;", "-", $xtreename );
		$xtreename = str_replace ( "<sup>|_</sup>", "", $xtreename );
		$categories [] = JHTML::_ ( 'select.option', $cat->value, JText::_ ( $xtreename ), 'value', 'text' );
	}
	$lists = JHTML::_ ( 'select.genericlist', $categories, 'catid', 'class="inputbox"', 'value', 'text', JRequest::getInt ( 'catid' ) );
	
	
	$countries = modLbSearchHelper::getCountryForSelect ( true,'id','title',JRequest::getInt('countries',0) );

}
$searchtext = $params->get('searchexampletxt');
$enable_searchtext = $params->get('enable_searchexampletxt');

require (JModuleHelper::getLayoutPath ( 'mod_lbsearch', $layout ));
