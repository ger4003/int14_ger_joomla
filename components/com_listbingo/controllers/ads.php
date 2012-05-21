<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: ads.php 2010-01-10 00:57:37 svn $
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

gbimport ( "gobingoo.controller" );

class ListbingoControllerAds extends GController {
	
	var $myviews = array ();
	function __construct($config = array()) {
		parent::__construct ( $config );
		$this->myviews = array ("ad", "ads" );
	
	}
	
	function display() {
		
		$currentview = JRequest::getCmd ( 'view' );
		
		if (! in_array ( $currentview, $this->myviews )) {
			
			JRequest::setVar ( 'view', 'ads' );
		}
		$this->item_type = 'Default';
		
		parent::display ();
	}
	
	function view() {
		
		if (JRequest::getCmd ( 'view' ) == '') {
			JRequest::setVar ( 'view', 'ad' );
		}
		$this->item_type = 'Default';
		
		parent::display ();
	
	}
	
	function showuserads() {
		global $mainframe, $option;
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$uid = JRequest::getInt ( 'uid', 0 );
		
		if ($uid) {
			$user = JFactory::getUser ( $uid );
		} else {
			$user = JFactory::getUser ();
			$uid = $user->get ( 'id', 0 );
		}
		
		if (! $uid) {
			$returnurl = base64_encode ( JRoute::_ ( "index.php?option=$option&Itemid=$listitemid&task=ads.showuserads&time=" . time (), false ) );
			
			$customloginlink = $params->get ( 'lb_custom_login', '' );
			if (! empty ( $customloginlink )) {
				$link = JRoute::_ ( $customloginlink . "&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			} else {
				$link = JRoute::_ ( "index.php?option=com_user&view=login&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			}
			
			$msg = JText::_ ( "LOGIN_TO_VIEW_YOUR_PROFILE" );
			GApplication::redirect ( $link, $msg, "error" );
		}
		
		GApplication::triggerEvent ( 'onLoadProfilelink', array (&$user, &$params ) );
		
		if (! empty ( $user->profilelink )) {
			GApplication::redirect ( $user->profilelink );
		}
		
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionmodel = gbimport ( "listbingo.model.region" );
		
		$country = $countrymodel->getCurrentCountry ( $params );
		$region = $regionmodel->getCurrentRegion ( $params );
		
		$countrytitle = $countrymodel->getCountryTitle ( $params );
		$regiontitle = $regionmodel->getRegionTitle ( $params );
		
		$profilemodel = gbimport ( "listbingo.model.profile" );
		$profile = $profilemodel->loadProfile ( $uid, $params );
		
		$address = array ();
		if (! empty ( $profile->address1 )) {
			$address [] = $profile->address1;
		}
		
		if (! empty ( $profile->address2 )) {
			$address [] = $profile->address2;
		}
		if (count ( $address ) > 0) {
			$useraddress = "";
			$useraddress .= "<strong>" . implode ( ", ", $address ) . "<strong><br />";
			$loc = array ();
			$loc [0] = $profile->rtitle;
			$loc [1] = $profile->ctitle;
			$loc = array_filter ( $loc );
			//$useraddress .= $profile->rtitle . ", " . $profile->ctitle;
			$useraddress .= implode ( ", ", $loc );
		} else {
			$useraddress = JText::_ ( 'ADDRESS_NOT_SPECIFIED' );
		}
		
		$model = gbimport ( "listbingo.model.ad" );
		
		$filter = new stdClass ();
		$filter->userid = $uid;
		
		$filter->limit = JRequest::getInt ( 'limit', $params->get ( 'ads_per_page' ) );
		$filter->limitstart = JRequest::getInt ( 'limitstart', 0 );
		
		$filter->country = $country;
		$filter->region = $region;
		$filter->regiontitle = $regiontitle;
		$filter->params = $params;
		$filter->checkExpiryDate = 1;
		
		$filter->searchtxt = JRequest::getVar ( 'q', "" );
		$filter->catid = JRequest::getVar ( 'category_id', "" );
		$filter->status = JRequest::getVar ( 'status', 1 );
		$filter->access = ( int ) $user->get ( 'aid', 0 );
		
		$rows = $model->getUserAds ( true, $filter );
		
		$total = $model->getUserAdsCount ( true, $filter );
		jimport ( 'joomla.html.pagination' );
		
		$pagenav = new JPagination ( $total, $filter->limitstart, $filter->limit );
		
		$baseurl = JUri::root ();
		$basepath = JPATH_ROOT;
		
		$suffix = $params->get ( $params->get ( 'listlayout_thumbnail' ) );
		
		$delimagelink = "";
		
		if (! file_exists ( $basepath . $profile->image . $params->get ( 'suffix_profile_image' ) . "." . $profile->extension )) {
			if ($params->get ( 'profile_enable_gravatar' )) {
				$hashemail = md5 ( $profile->email );
				$imgurl = "http://www.gravatar.com/avatar/" . $hashemail . "?s=75";
			} else {
				$imgurl = JUri::root () . $params->get ( 'path_default_profile_noimage' );
			}
		
		} else {
			$imgurl = $baseurl . $profile->image . $params->get ( 'suffix_profile_image' ) . "." . $profile->extension;
		}
		
		$view = $this->getView ( 'ads', 'html' );
		
		JFilterOutput::objectHTMLSafe ( $rows );
		JFilterOutput::objectHTMLSafe ( $pagenav );
		JFilterOutput::objectHTMLSafe ( $filter );
		JFilterOutput::objectHTMLSafe ( $params );
		JFilterOutput::objectHTMLSafe ( $profile );
		JFilterOutput::objectHTMLSafe ( $imgurl );
		JFilterOutput::objectHTMLSafe ( $useraddress );
		
		$view->assignRef ( 'rows', $rows );
		$view->assignRef ( 'profile', $profile );
		$view->assignRef ( 'pagination', $pagenav );
		$view->assignRef ( 'filter', $filter );
		$view->assignRef ( 'params', $params );
		$view->assignRef ( 'imgurl', $imgurl );
		$view->assignRef ( 'useraddress', $useraddress );
		$view->setLayout ( 'userads' );
		
		$view->customDisplay ();
	
	}
	
	function search() {
		
		global $mainframe, $option;
		
		$country = 0;
		$region = 0;
		
		$menus = & JSite::getMenu ();
		$menu = $menus->getActive ();
		$pathway = & $mainframe->getPathWay ();
		
		$user = JFactory::getUser ();
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$countrymodel = gbimport ( "listbingo.model.country" );
		$regionmodel = gbimport ( "listbingo.model.region" );
		$catmodel = gbimport ( "listbingo.model.category" );
		
		$country = $countrymodel->getCurrentCountry ( $params );
		$region = $regionmodel->getCurrentRegion ( $params );
		
		$catid = JRequest::getInt ( 'catid', 0 );
		
		//Import required libararies
		

		gbimport ( "gobingoo.currency" );
		
		$filter = new stdClass ();
		$filter->params = $params;
		
		if ($catid) {
			$filter->category_id = ( int ) $catid;
		} else {
			$filter->category_id = 0;
		}
		
		// slashes cause errors, <> get stripped anyway later on. # causes problems.
		$badchars = array ('#', '>', '<', '\\' );
		$searchword = addslashes ( trim ( str_replace ( $badchars, '', JRequest::getString ( 'q', '' ) ) ) );
		// if searchword enclosed in double quotes, strip quotes and do exact match
		if (substr ( $searchword, 0, 1 ) == '"' && substr ( $searchword, - 1 ) == '"') {
			$keyword = substr ( $searchword, 1, - 1 );
		
		} else {
			$keyword = $searchword;
		}
		
		$jfilter = new JFilterInput ( array (), array (), 1, 1, 1 );
		
		$filter->searchtxt = $jfilter->clean ( $keyword, "STRING" );
		$filter->alpha = $jfilter->clean ( substr ( JRequest::getVar ( 'alpha', '' ), 0, 6 ), "STRING" );
		
		$countrytitle = $countrymodel->getCountryTitle ( $params );
		$regiontitle = $regionmodel->getRegionTitle ( $params );
		if ($params->get ( 'enable_smart_country' )) {
			$countryObj = $countrymodel->findAndSetCountry ( $filter->searchtxt, false );
		
		} else {
			$countryObj = false;
		}
		
		if ($countryObj) {
			
			$country = $countryObj->cid;
			$regiontitle = $countryObj->title;
			$region = array ();
			
			$searchtext = empty ( $countryObj->text ) ? $params->get ( 'search_text_default', 'all' ) : $countryObj->text;
			$filter->searchtxt = $searchtext;
		
		} else {
			
			if ($params->get ( 'enable_smart_region' )) {
				
				$regionObj = $regionmodel->findAndSetRegion ( $filter->searchtxt, false );
			} else {
				
				$regionObj = false;
			}
			
			if ($regionObj) {
				
				$regiontitle = $regionObj->title;
				$region = $regionObj->rid;
				
				$searchtext = empty ( $regionObj->text ) ? $params->get ( 'search_text_default', 'all' ) : $regionObj->text;
				$filter->searchtxt = $searchtext;
			} else {
				$regiontitle = $regionmodel->getRegionTitle ( $params );
			}
		}
		
		if (! $catid) {
			if ($params->get ( 'enable_smart_category' )) {
				$catObj = $catmodel->find ( $filter->searchtxt, ( int ) $user->get ( 'aid', 0 ) );
				$filter->catObj = $catObj;
			} else {
				$filter->catObj = NULL;
			}
		} else {
			$filter->catObj = NULL;
		}
		
		if (JRequest::getInt ( 'countries', 0 )) {
			$country = JRequest::getInt ( 'countries', 0 );
			$countrydetails = $countrymodel->load ( $country );
			$region = 0;
			$countrytitle = $countrydetails->title;
			
			$mainframe->setUserState ( $option . 'country', $country );
			$mainframe->setUserState ( $option . 'region', 0 );
		}
		
		if (JRequest::getInt ( 'region_id', 0 )) {
			$region = JRequest::getInt ( 'region_id', 0 );	
			$mainframe->setUserState ( $option . 'region', $region );
			$regiondetails = $regionmodel->load ( $region );
			$regiontitle = $regiondetails->title;
		}
		
		$filter->country = $country;
		$filter->region = $region;
		
		$filter->limit = JRequest::getInt ( 'limit', $params->get ( 'ads_per_page' ) );
		$filter->limitstart = JRequest::getInt ( 'limitstart', 0 );
		
		$filter->price_from = JRequest::getVar ( 'search_from_price', '' );
		$filter->price_to = JRequest::getVar ( 'search_to_price', '' );
		
		$order = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order', 'order', $params->get ( 'layout_ordering' ), 'cmd' );
		$filter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order_Dir', 'dir', '', 'word' );
		
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
		
		$filter->countrytitle = ucfirst ( $countrytitle );
		$filter->regiontitle = ($countrytitle != "") ? ucfirst ( $regiontitle ) . ", " . ucfirst ( $countrytitle ) : ucfirst ( $regiontitle );
		$filter->checkExpiryDate = 1;
		$filter->access = ( int ) $user->get ( 'aid', 0 );
		
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
		
		/*** for sub category *****/
		
		$catfilter = new stdClass ();
		
		$catfilter->order = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order', 'order', 'c.title', 'cmd' );
		$catfilter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'listfilter_order_Dir', 'dir', '', 'word' );
		
		$catfilter->country = $country;
		$catfilter->region = $region;
		$catfilter->countrytitle = $countrytitle;
		$catfilter->regiontitle = $regiontitle;
		$catfilter->catid = $catid;
		$catfilter->access = ( int ) $user->get ( 'aid', 0 );
		
		$categories = $catmodel->getListForProduct ( true, $catfilter );
		
		if (! is_array ( $categories )) {
			
			$categories = array ($categories );
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
		
		$relatedtable = & JTable::getInstance ( 'relatedcategory' );
		$relatedtable->id = $catid;
		$relatedcat = $relatedtable->getRelatedCategoryLists ();
		
		$user = JFactory::getUser ();
		$userid = $user->get ( 'id' );
		
		$db = JFactory::getDBO ();
		$nulldate = $db->getNullDate ();
		
		if (isset ( $rows ) && count ( $rows ) > 0) {
			foreach ( $rows as &$row ) {
				
				$row->title = JFilterOutput::cleanText ( $row->title );
				$row->id = JFilterOutput::cleanText ( $row->id );
				
				$row->address1 = JFilterOutput::cleanText ( $row->address1 );
				$row->address2 = JFilterOutput::cleanText ( $row->address2 );
			
			}
		}
		
		JFilterOutput::objectHTMLSafe ( $pagenav );
		JFilterOutput::objectHTMLSafe ( $filter );
		JFilterOutput::objectHTMLSafe ( $params );
		JFilterOutput::objectHTMLSafe ( $categories );
		JFilterOutput::objectHTMLSafe ( $indcount );
		JFilterOutput::objectHTMLSafe ( $relatedcat );
		JFilterOutput::objectHTMLSafe ( $user );
		
		GApplication::triggerEvent ( 'onBeforeListDisplay', array (&$rows, &$params ) );
		$view = $this->getView ( 'ads', 'html' );
		$searchview = $this->getView ( 'search', 'html' );
		
		$view->assignRef ( 'rows', $rows );
		$view->assignRef ( 'pagination', $pagenav );
		$view->assignRef ( 'filter', $filter );
		$view->assignRef ( 'params', $params );
		$view->assignRef ( 'categories', $categories );
		$view->assignRef ( 'indcount', $indcount );
		$view->assignRef ( 'relatedcat', $relatedcat );
		$view->assign ( 'user', $user );
		$view->assign ( 'userid', $userid );
		$view->assign ( 'guest', $user->guest );
		$view->assign ( 'nulldate', $nulldate );
		//$searchview->display();
		

		$view->searchDisplay ();
		GApplication::triggerEvent ( 'onAfterListDisplay', array (&$rows, &$params ) );
	
	}
	
	function save() {
		
		global $mainframe, $option, $listitemid;
		if (! JRequest::checkToken ()) {
			throw new Exception ( "Invalid Token" );
		}
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		if ($mainframe->getUserState ( $option . 'forcepost', 0 )) {
			$params->set ( 'posting_scheme', 1 );
		}
		
		$post = JRequest::get ( 'post', array () );
		$session = &JFactory::getSession ();
		
		$session->set ( 'ad_save', json_encode ( $post ), $option );
		
		$edit = isset ( $post ['id'] ) ? $post ['id'] : 0;
		
		if (! $edit && $params->get ( 'enable_terms_conditions' )) {
			if (! $post ['terms_n_condtions']) {
				$link = JRoute::_ ( "index.php?option=$option&task=new&Itemid=$listitemid&catid=" . $post ['catid'] . "&time=" . time (), false );
				$msg = JText::_ ( 'NO_AGREEMENT' );
				GApplication::redirect ( $link, $msg, 'error' );
			}
		}
		
		$user = JFactory::getUser ();
		if ($user->guest) {
			$catid = JRequest::getInt ( 'catid' );
			if ($catid) {
				$cat = "&catid=" . $catid;
			}
			$returnurl = base64_encode ( JRoute::_ ( "index.php?option=$option&task=new$cat&Itemid=$listitemid&time=" . time (), false ) );
			
			$customloginlink = $params->get ( 'lb_custom_login', '' );
			if (! empty ( $customloginlink )) {
				$link = JRoute::_ ( $customloginlink . "&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			} else {
				$link = JRoute::_ ( "index.php?option=com_user&view=login&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			}
			
			$msg = JText::_ ( "LOGIN_TO_POST_AD" );
			$session->clear ( 'ad_save', $option );
			GApplication::redirect ( $link, $msg, "error" );
		}
		
		$post ['email'] = $user->get ( 'email' );
		$post ['user_id'] = $user->get ( 'id' );
		
		if (! $edit) {
			$post ['country_id'] = $mainframe->getUserState ( $option . 'country' );
			$post ['region_id'] = $mainframe->getUserState ( $option . 'region' );
		}
		
		$post ['category_id'] = JRequest::getInt ( 'catid', 0 );
		$post ['description'] = JRequest::getVar ( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$edit = isset ( $post ['id'] ) ? $post ['id'] : 0;
		
		$tasks = explode ( ".", JRequest::getCmd ( "task" ) );
		$task = array_pop ( $tasks );
		
		$images = JRequest::get ( 'files' );
		// Support for Attachment Element
		$attachments = isset ( $images ['field'] ) ? $images ['field'] : array ();
		
		$images = isset ( $images ['images'] ) ? $images ['images'] : array ();
		$fields = JRequest::getVar ( 'field', array () );
		$availableattach = JRequest::getVar ( 'available_attach', array () );
		
		try {
			GApplication::triggerEvent ( "onBeforFormProcess", array (&$post, &$params ) );
			GApplication::triggerEvent ( "onBeforeAdSave", array (&$post, &$params ) );
			
			$model = gbimport ( 'listbingo.model.ad' );
			$model->resetBasket ();
			
			$fields ['attachments'] = $attachments;
			$fields ['available_attachments'] = $availableattach;
			$id = $model->save ( $post, $images, $fields );
			
			if ($id) {
				
				$session->clear ( 'ad_save', $option );
				
				switch ($params->get ( 'posting_scheme' )) {
					case 1 :
						
						$userpostmodel = gbimport ( "listbingo.model.userpost" );
						$referenceid = 0;
						$userpostmodel->trackPost ( $user, $id, $referenceid, $params );
						break;
					
					case 2 :
						
						if (! $post ['edit']) {
							GApplication::triggerEvent ( "onTrackPost", array (&$user, &$id, &$params ) );
						}
						break;
				}
				
				$post ['id'] = $id;
				switch ($task) {
					case 'save' :
						
						$link = JRoute::_ ( "index.php?option=$option&task=myads&Itemid=$listitemid&time=" . time (), false );
						$msg = JText::_ ( "SAVED" );
						
						break;
					default :
						
						$link = JRoute::_ ( "index.php?option=$option&task=ads.edit&Itemid=$listitemid&catid=" . JRequest::getInt ( 'catid', 0 ) . "&ad_id[]=" . $id . "&time=" . time (), false );
						$msg = JText::_ ( "APPLIED" );
						break;
				
				}
				
				$row = $model->load ( $id );
				GApplication::triggerEvent ( "onAfterAdSave", array (&$row, &$params, &$post ) );
				
				GApplication::triggerEvent ( "onBeforeAdPublish", array (&$row, & $post, &$params ) );
				
				if ($model->isPayable ( $user, $row, $edit, $params )) {
					
					if ($params->get ( 'auto_submit_listing', 0 ) && ! $model->isPublished ( $row ) && ! $row->adpayable) {
						$model->publish ( 'publish', array ($row->id ) );
						GApplication::triggerEvent ( "onAdPublish", array (&$row, &$params, &$post ) );
					
					} else {
						
						$model->publish ( 'unpublish', array ($row->id ) );
					}
					
					$cart = new stdClass ();
					if (ListbingoHelper::isGCartAvailable ( $params, $cart )) {
						
						$link = $cart->cartlink;
						$msg = JText::_ ( "MAKE_PAYMENTS_BEFORE_PUBLISHING" );
					} else {
						$msg = JText::_ ( "CART_NOT_AVAILABLE" );
					}
				} elseif ($params->get ( 'auto_submit_listing', 0 ) && ! $model->isPublished ( $row )) {
					//echo "2";exit;
					if ($row->status != 2) {
						$model->publish ( 'publish', array ($row->id ) );
						GApplication::triggerEvent ( "onAdPublish", array (&$row, &$params, &$post ) );
					}
					$msg = JText::_ ( "SAVED" );
				} elseif (! $params->get ( 'auto_submit_listing', 0 ) && ! $model->isPublished ( $row ) && ! $edit) {
					//echo "3";exit;
					GApplication::triggerEvent ( "onAdModerated", array (&$row, &$params, &$post ) );
					$msg = JText::_ ( "UNDER_MODERATION" );
				} elseif ($params->get ( 'moderate_edit', 0 ) && $edit) {
					//echo "4";exit;
					$model->publish ( 'unpublish', array ($row->id ) );
					GApplication::triggerEvent ( "onAdModerated", array (&$row, &$params, &$post ) );
					$msg = JText::_ ( "UNDER_EDIT_MODERATION" );
				}
				
				$errortype = "success";
			}
		
		} catch ( DataException $e ) {
			if ($edit) {
				$link = JRoute::_ ( "index.php?option=$option&task=ads.edit&Itemid=$listitemid&catid=" . $post ['catid'] . "&adid=" . $post ['id'] . "&time=" . time (), false );
			} else {
				$link = JRoute::_ ( "index.php?option=$option&task=new&Itemid=$listitemid&catid=" . $post ['catid'] . "&time=" . time (), false );
			}
			
			$msg = $e->getMessage ();
			$errortype = "error";
		}
		
		$mainframe->setUserState ( $option . 'forcepost', 0 );
		$wherewasi = $mainframe->setUserState ( "hereiam", '' );
		GApplication::redirect ( $link, $msg, $errortype );
	}
	
	function uploadImages() {
		if (! JRequest::checkToken ()) {
			throw new Exception ( "Invalid Token" );
		}
		
		global $mainframe, $option, $listitemid;
		$adid = JRequest::getInt ( 'id' );
		$images = JRequest::get ( 'files' );
		$images = $images ['images'];
		
		$model = gbimport ( 'listbingo.model.ad' );
		
		if ($model->saveImages ( $images, $adid )) {
			$link = JRoute::_ ( 'index.php?option=' . $option . '&task=myads&Itemid=' . $listitemid . '&time=' . time (), false );
			$msg = JText::_ ( 'SAVED' );
			$msgtype = "success";
		} else {
			$link = JRoute::_ ( 'index.php?option=' . $option . '&task=myads&Itemid=' . $listitemid . '&time=' . time (), false );
			$msg = JText::_ ( 'FAILED' );
			$msgtype = "error";
		}
		GApplication::redirect ( $link, $msg, $msgtype );
	}
	
	function edit() {
		global $mainframe, $option, $listitemid;
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$user = JFactory::getUser ();
		
		$userid = $user->get ( 'id' );
		
		$adid = JRequest::getInt ( 'adid', 0 );
		$catid = JRequest::getInt ( 'catid', 0 );
		
		if ($userid) {
			$model = gbimport ( "listbingo.model.ad" );
			$row = $model->load ( $adid );
			
			if ($row->user_id != $userid) {
				$link = JRoute::_ ( "index.php", false );
				$msg = JText::_ ( 'UNAUTHORIZED_ACCESS' );
				GApplication::redirect ( $link, $msg, 'error' );
			}
			
			/*			$country = $mainframe->setUserState ( $option . "country", $row->country_id );
			$region = $mainframe->setUserState ( $option . "region", $row->region_id );*/
			
			JRequest::setVar ( 'view', 'new' );
			
			$this->item_type = 'Default';
			parent::display ();
		
		} else {
			$returnurl = base64_encode ( JRoute::_ ( "index.php?option=$option&task=ads.edit&Itemid=$listitemid&adid=$adid&time=" . time (), false ) );
			
			$customloginlink = $params->get ( 'lb_custom_login', '' );
			if (! empty ( $customloginlink )) {
				$link = JRoute::_ ( $customloginlink . "&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			} else {
				$link = JRoute::_ ( "index.php?option=com_user&view=login&return=$returnurl&Itemid=$listitemid&time=" . time (), false );
			}
			
			$msg = JText::_ ( "LOGIN_TO_EDIT_AD" );
			
			GApplication::redirect ( $link, $msg, 'error' );
		}
	
	}
	
	function loadef() {
		$cid = ( int ) JRequest::getInt ( 'catid', 0 );
		$ad_id = ( int ) JRequest::getInt ( 'adid', 0 );
		
		$catmodel = gbimport ( "listbingo.model.category" );
		$fields = $catmodel->getExtrafields ( $cid, $ad_id );
		$view = $this->getView ( 'ajaxinput', 'html' );
	}
	
	/*	function remove()
	 {

		global $mainframe,$option;
		$adid = JRequest::getInt('adid');
		$user = JFactory::getUser();
		$userid = $user->get('id');
		try
		{
		$model=gbimport("listbingo.model.ad");

		if($model->removeUserAd($adid,$userid))
		{
		$adcount = $model->getListCount();
		$array=array('deleted'=>true,'adcount'=>$adcount);
		echo json_encode($array);
		}
		else
		{
		$array=array('deleted'=>false);
		echo json_encode($array);
		}
		$msg=JText::_('DELETED');
		}
		catch(DataException $e)
		{
		$array=array('deleted'=>false);
		echo json_encode($array);
		}


		}*/
	
	function remove() {
		
		global $mainframe, $option, $listitemid;
		$adid = JRequest::getInt ( 'adid' );
		$user = JFactory::getUser ();
		$userid = $user->get ( 'id' );
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		try {
			$model = gbimport ( "listbingo.model.ad" );
			
			$row = $model->load ( $adid );
			
			if ($model->removeUserAd ( $adid, $userid )) {
				$msg = JText::_ ( 'DELETED' );
				GApplication::triggerEvent ( "onAfterAdDeleted", array (&$row, &$params ) );
				$msgtype = "success";
			} else {
				$msg = JText::_ ( 'DELETED_FAILED' );
				$msgtype = "error";
			}
		
		} catch ( DataException $e ) {
			$msg = $e->getMessage ();
			$msgtype = "error";
		}
		
		$link = JRoute::_ ( "index.php?option=$option&task=myads&time=" . time () . "&Itemid=$listitemid", false );
		GApplication::redirect ( $link, $msg, $msgtype );
	
	}
	
	function images() {
		global $mainframe, $option, $listitemid;
		
		$adid = JRequest::getInt ( 'adid', 0 );
		$user = JFactory::getUser ();
		$userid = $user->get ( 'id' );
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		if ($userid) {
			
			$model = gbimport ( "listbingo.model.ad" );
			$profilemodel = gbimport ( "listbingo.model.profile" );
			
			$row = $model->load ( $adid );
			
			if ($row->user_id != $userid) {
				$link = JRoute::_ ( "index.php?Itemid=$listitemid", false );
				$msg = JText::_ ( 'UNAUTHORIZED_ACCESS' );
				GApplication::redirect ( $link, $msg, 'error' );
			}
			
			$profile = $profilemodel->getUserProfile ( $userid, $params );
			
			switch ($params->get ( 'posting_scheme' )) {
				
				case 2 :
					if (is_object ( $profile->package )) {
						//$packageparams = new JParameter ( $profile->package->params );
						$maxlimit = $profile->package->number_of_images;
					} else {
						$maxlimit = $params->get ( 'images_number' );
					}
					break;
				
				case 0 :
				case 1 :
				default :
					$maxlimit = $params->get ( 'images_number' );
					break;
			
			}
			
			$view = $this->getView ( 'ads', 'html' );
			$view->assignRef ( 'row', $row );
			$view->assignRef ( 'params', $params );
			$view->assignRef ( 'profile', $profile );
			$view->assign ( 'maxlimit', $maxlimit );
			$view->setLayout ( 'images' );
			$view->customDisplay ();
		} else {
			$returnurl = base64_encode ( JRoute::_ ( "index.php?option=$option&task=new.images&Itemid=$listitemid&adid=$adid&format=raw", false ) );
			
			$customloginlink = $params->get ( 'lb_custom_login', '' );
			if (! empty ( $customloginlink )) {
				$link = JRoute::_ ( $customloginlink . "&return=$returnurl&Itemid=$listitemid&format=raw&time=" . time (), false );
			} else {
				$link = JRoute::_ ( "index.php?option=com_user&view=login&return=$returnurl&Itemid=$listitemid&format=raw&time=" . time (), false );
			}
			
			$msg = JText::_ ( "LOGIN_TO_MANAGE_YOUR_AD_IMAGES" );
			GApplication::redirect ( $link, $msg, "error" );
		}
	}
	
	function deleteImages() {
		global $mainframe, $option, $listitemid;
		$cid = JRequest::getVar ( 'cid', array (0 ), '', 'array' );
		JArrayHelper::toInteger ( $cid, array (0 ) );
		$user = JFactory::getUser ();
		$userid = $user->get ( 'id' );
		try {
			$model = gbimport ( "listbingo.model.adimage" );
			if ($model->remove ( $cid, $userid )) {
				$msg = JText::sprintf ( 'IMAGES_DELETED', (sizeof ( $cid )) );
				$msgtype = "success";
			} else {
				$msg = JText::_ ( 'DELETED_FAILED' );
				$msgtype = "error";
			}
		
		} catch ( DataException $e ) {
			$msg = $e->getMessage ();
			$msgtype = "error";
		}
		
		$link = JRoute::_ ( "index.php?option=$option&task=myads&Itemid=$listitemid", false );
		GApplication::redirect ( $link, $msg, $msgtype );
	}
	
	function publish() {
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		$post = JRequest::get ( 'post', array () );
		
		$model = gbimport ( "listbingo.model.ad" );
		$ad = $model->load ( $post ['ref_id'] );
		if ($ad->user_id == $post ['user_id']) {
			$model->publish ( 'publish', array ($post ['ref_id'] ) );
		}
		GApplication::triggerEvent ( 'onAdPublish', array (&$ad, &$params, & $post ) );
	
	}
	
	function update() {
		self::publish ();
		exit ();
	
	}
	
	function close() {
		$id = JRequest::getInt ( 'adid' );
		global $option, $listitemid;
		
		$model = gbimport ( "listbingo.model.ad" );
		try {
			if ($model->close ( $id )) {
				$msg = JText::sprintf ( 'CLOSED' );
				$msgtype = "success";
			} else {
				$msg = JText::_ ( 'CLOSED_FAILED' );
				$msgtype = "error";
			}
		
		} catch ( DataException $e ) {
			$msg = $e->getMessage ();
			$msgtype = "error";
		}
		
		$link = JRoute::_ ( "index.php?option=$option&task=myads&Itemid=$listitemid", false );
		GApplication::redirect ( $link, $msg, $msgtype );
	}

}

?>