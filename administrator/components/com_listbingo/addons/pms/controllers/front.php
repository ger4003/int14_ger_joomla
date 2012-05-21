<?php
/**
 * admin.php
 *@package Listbingo
 *@subpackage agent
 *
 *Listbingo Agent Controller
 *
 */

defined ( 'JPATH_BASE' ) or die ();
gbimport ( "gobingoo.addonscontroller" );

class GControllerPms_Front extends GAddonsController {
	
	function showReply($row) {
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$view = $this->getView ( 'compose', 'html' );
		$view->assignRef ( 'params', $params );
		$view->assignRef ( 'adid', $row->id );
		$view->display ();
	}
	
	function displayComposeBox() {
		
		global $option, $mainframe, $listitemid;
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$user = JFactory::getUser ();
		$adid = JRequest::getVar ( 'adid', 0 );
		if ($params->get ( 'enable_pms_for_guest', 0 ) || ! $user->guest) {
			
			$model = gbimport ( "listbingo.model.ad" );
			$row = $model->load ( $adid );
			
			$view = $this->getView ( 'compose', 'html' );
			$view->assignRef ( 'ad', $row );
			$view->assignRef ( 'params', $params );
			$view->setLayout ( 'form' );
			$view->display ();
		} else {
			$msg = JText::_ ( 'PMS_DISABLED_FOR_GUEST' );
			$link = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=$adid&Itemid=$listitemid", false );
			
			GApplication::redirect ( $link, $msg );
		}
	
	}
	
	function send() {
		global $option, $listitemid;
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		$post = JRequest::get ( 'post' );
		//var_dump($post);exit;
		$model = gbaddons ( "pms.model.pm" );
		try {
			$row = $model->save ( $post, $params );
			if (is_object ( $row )) {
				GApplication::triggerEvent ( 'onReplySave', array (&$row, &$params ) );
				$msg = JText::_ ( "MESSAGE_SEND_SUCCESS" );
				$errortype = "success";
			} else {
				$msg = JText::_ ( "MESSAGE_SEND_FAILURE" );
				$errortype = "error";
			}
			
			$redirlink = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=" . $post ['ad_id'] . "&Itemid=$listitemid", false );
		} catch ( DataException $e ) {
			$redirlink = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=" . $post ['ad_id'] . "&Itemid=$listitemid", false );
			$msg = $e->getMessage ();
		}
		GApplication::redirect ( $redirlink, $msg, $errortype );
	
	}

}
?>