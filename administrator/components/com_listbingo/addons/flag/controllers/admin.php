<?php
/**
 *admin.php
 *@package Listbingo
 *@subpackage Flag
 *
 *A classified ad Flag Addon Controller
 *code Bruce
 *
 */

defined ( 'JPATH_BASE' ) or die ();
gbimport ( "gobingoo.addonscontroller" );

class GControllerFlag_Admin extends GAddonsController {
	function __construct($config = array()) {
		parent::__construct ( $config );
	}
	
	function display() {
		//Get Application
		$app = & JFactory::getApplication ();
		
		//check if the user is admin or not
		if (! $app->isAdmin ()) {
			$link = JRoute::_ ( "index.php", false );
			$msg = JText::_ ( "INVALID_ACCESS" );
			GApplication::redirect ( $link, $msg, "error" );
		
		}
		$view = $this->getView ( 'flags', 'html', true );
		$view->display ();
	}
	
	function flagSettingsPage($params = null) {
		//Get Application
		$app = & JFactory::getApplication ();
		
		//check if the user is admin or not
		if (! $app->isAdmin ()) {
			$link = JRoute::_ ( "index.php", false );
			$msg = JText::_ ( "INVALID_ACCESS" );
			GApplication::redirect ( $link, $msg, "error" );
		
		}
		$view = $this->getView ( 'settings', 'html', true );
		$view->assignRef ( 'config', $params );
		$view->display ();
	}
	
	function view() {
		
		$view = $this->getView ( 'flag', 'html', true );
		$view->display ();
	}
	
	function approve() {
		global $mainframe, $option;
		
		//Get Application
		$app = & JFactory::getApplication ();
		
		//check if the user is admin or not
		if (! $app->isAdmin ()) {
			$link = JRoute::_ ( "index.php", false );
			$msg = JText::_ ( "INVALID_ACCESS" );
			GApplication::redirect ( $link, $msg, "error" );
		
		}
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$returnurl = JRoute::_ ( 'index.php?option=' . $option . '&task=addons.flag.admin', false );
		
		$cid = JRequest::getInt ( 'cid' );
		$fid = JRequest::getInt ( 'fid' );
		
		/*$admodel = gbimport ( 'listbingo.model.ad' );
		$row = $admodel->loadWithFields ( $cid );
		$row->flag = $fid;*/
		$model=gbaddons("flag.model.flag");
		$flag = $model->load ( $fid );
				
		if ($cid) {
			
			$table = & JTable::getInstance ( 'ad' );
			$table->id = $cid;
			$table->status = 2;
			$table->store ();
			$msg = JText::_ ( 'APPROVED_FOR_FLAG' );
			GApplication::triggerEvent ( "onFlagApproval", array (&$flag, &$params ) );
			
			$model = gbaddons ( "flag.model.flag" );
			$model->remove ( $cid );
			
			GApplication::redirect ( $returnurl, $msg );
		} else {
			GApplication::redirect ( $returnurl );
		}
	
	}
	
	function unapprove() {
		global $mainframe, $option;
		
		//Get Application
		$app = & JFactory::getApplication ();
		
		//check if the user is admin or not
		if (! $app->isAdmin ()) {
			$link = JRoute::_ ( "index.php", false );
			$msg = JText::_ ( "INVALID_ACCESS" );
			GApplication::redirect ( $link, $msg, "error" );
		
		}
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$returnurl = JRoute::_ ( 'index.php?option=' . $option . '&task=addons.flag.admin', false );
		
		$cid = JRequest::getInt ( 'cid' );
		$fid = JRequest::getInt ( 'fid' );
		
		/*$admodel = gbimport ( 'listbingo.model.ad' );
		$row = $admodel->loadWithFields ( $cid );
		$row->flag = $fid;*/
		
		$model=gbaddons("flag.model.flag");
		$flag = $model->load ( $fid );
		
		if ($cid) {
			$msg = JText::_ ( 'UNAPPROVED_FOR_FLAG' );
			GApplication::triggerEvent ( "onFlagUnapproval", array (&$flag, &$params ) );
			
			$model = gbaddons ( "flag.model.flag" );
			$model->remove ( $cid );
			
			GApplication::redirect ( $returnurl, $msg, 'success' );
		} else {
			GApplication::redirect ( $returnurl );
		}
	
	}

}

?>