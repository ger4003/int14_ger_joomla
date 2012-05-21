<?php
/**
 * admin.php
 *@package Listbingo
 *@subpackage flag
 *
 *Flag Addon Controller
 *code Bruce
 *
 */

defined ( 'JPATH_BASE' ) or die ();
gbimport ( "gobingoo.addonscontroller" );

class GControllerFlag_Front extends GAddonsController {
	
	function displayFlag($row) {
		$view = $this->getView ( 'flag', 'html' );
		$view->display ();
	}
	
	function showFlagForm() {
		
		global $mainframe, $option, $listitemid;
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$user = JFactory::getUser ();
		$adid = JRequest::getVar ( 'adid', 0 );
		if ($params->get ( 'enable_flag_for_guest', 0 ) || ! $user->guest) {
			
			$ip = GHelper::getIP ();
			$model = gbaddons ( "flag.model.flag" );
			$flag = $model->isFromThisIP ( $adid, $ip );
			
			$article_id = $params->get ( 'flag_id', 0 );
			$article = JTable::getInstance ( "content" );
			$article->load ( $article_id );
			
			$article->text = $article->introtext . $article->fulltext;
			
			$xparams = & $mainframe->getParams ( 'com_content' );
			$results = $mainframe->triggerEvent ( 'onPrepareContent', array (& $article, & $xparams, 0 ) );
			
			$report = array ();
			
			$report [] = JHTML::_ ( 'select.option', '', JText::_ ( 'PLEASE_SELECT' ), 'id', 'title' );
			
			if ($params->get ( 'flag_criteria', '' ) != "") {
				$criteria = explode ( "\n", $params->get ( 'flag_criteria', '' ) );
				foreach ( $criteria as $c ) {
					if(!empty($c))
					{
						$report [] = JHTML::_ ( 'select.option', $c, $c, 'id', 'title' );
					}
				}
			
			}
			$report = array_filter($report);
			

			$reportlist = JHTML::_ ( 'select.genericlist', $report, 'flag_id', 'class="inputField required"', 'id', 'title' );
			
			$view = $this->getView ( 'flag', 'html' );
			$view->assignRef ( 'article', $article );
			$view->assignRef ( 'report', $reportlist );
			$view->assignRef ( 'params', $params );
			$view->assignRef ( 'adid', $adid );
			$view->setLayout ( 'form' );
			$view->customDisplay ();
		} else {
			$msg = JText::_ ( 'FLAG_DISABLED_FOR_GUEST' );
			$link = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=$adid&Itemid=$listitemid", false );
			
			GApplication::redirect ( $link, $msg );
		}
	}
	
	function report() {
		global $mainframe, $option, $listitemid;
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$data = JRequest::get ( 'post', array () );
		$data ['ipaddress'] = GHelper::getIP ();
		$user = JFactory::getUser ();
		
		$data ['user_id'] = $user->get ( 'id' );
		
		try {
			
			$model = gbaddons ( "flag.model.flag" );
			
			$filter = new stdClass ();
			$filter->item_id = $data ['item_id'];
			$filter->email = $data ['email'];
			
			if (! $model->checkFlaggedItem ( $filter )) {
				$id = $model->save ( $data );
				$link = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=" . $data ['item_id'] . "&Itemid=$listitemid", false );
				$msg = JText::_ ( "FLAGGED" );
				$mtype="success";
				
				$flag = $model->load ( $id );
				$model->doSuspension ( $params );
				GApplication::triggerEvent ( 'onFlagSave', array (&$flag, &$params ) );
			} else {
				
				$msg = JText::_ ( 'ALREADY_FLAGGED' );
				$mtype="error";
				$link = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=" . $data ['item_id'] . "&Itemid=$listitemid", false );
			}
		
		} catch ( DataException $e ) {
			$link = JRoute::_ ( "index.php?option=$option&task=ads.view&adid=" . $data ['item_id'] . "&Itemid=$listitemid", false );
			$msg = $e->getMessage ();
			$mtype="error";
		}
		
		GApplication::redirect ( $link, $msg ,$mtype);
	}

}
?>