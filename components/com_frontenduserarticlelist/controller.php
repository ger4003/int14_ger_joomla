<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


class FUALController extends JController {

	/**
	 * Method to display the view
	 * @access public
	 */
	function display() {
		parent::display();
	}
	
	function edit() {
		$uri = &JFactory::getURI();
		$uri_query = $uri->getQuery();
		parse_str($uri_query, $uri_params);
		$uri_params['option'] = 'com_content';
		$new_query = $uri->buildQuery($uri_params);
		
		$this->setRedirect('index.php?' . $new_query);
	}
	
	function unPublish() {
		$cid = JRequest::getInt('cid');
		$itemid = JRequest::getInt('Itemid');
		$user = &JFactory::getUser();
		$params = JComponentHelper::getParams('com_frontenduserarticlelist');

		$can_publish = $user->authorize('com_content', 'publish', 'content', 'all');
		$override = false;
		if(($user->usertype == 'Editor' && $params->get('editors_publishes')) || ($user->usertype == 'Author' && $params->get('authors_publishes'))) {
			$override = true;
		}
		if($can_publish || $override) {

			require_once('models/frontenduserarticlelist.php');
			$fual_model = new FUALModelFrontendUserArticleList();
			$item_content = $fual_model->getItem($cid);

			$publica = false;
			if(is_object($item_content) && $override && $item_content->created_by == $user->id && !$can_publish) {
				$publica = true;
			}
			elseif(is_object($item_content) && $can_publish) {
				$publica = true;
			}

			if($publica) {
				//change state to published or unpublished
				$item_content->state = ($item_content->state == 0) ? 1 : 0;
				$fual_model->updateContent($item_content);
			}
		}
		
		$this->setRedirect("index.php?option=com_frontenduserarticlelist&view=frontenduserarticlelist&Itemid=$itemid");
	}

	function trash() {
		$cid = JRequest::getInt('cid');
		$itemid = JRequest::getInt('Itemid');
		$user = &JFactory::getUser();
		
		$can_edit = $user->authorize('com_content', 'edit', 'content', 'all');
		$can_edit_own = $user->authorize('com_content', 'edit', 'content', 'own');

		require_once('models/frontenduserarticlelist.php');
		$fual_model = new FUALModelFrontendUserArticleList();
		$item_content = $fual_model->getItem($cid);
		
		if(is_object($item_content) && ($can_edit || ($can_edit_own && $user->id == $item_content->created_by))) {
			//change state
			$item_content->state = ($item_content->state >= 0) ? -2 : 0;
			$fual_model->updateContent($item_content);
		}
		
		$this->setRedirect("index.php?option=com_frontenduserarticlelist&view=frontenduserarticlelist&Itemid=$itemid");
	}

	function saveAlias() {
		$user = &JFactory::getUser();
		$id_article = JRequest::getInt('id_article');

		$can_edit = $user->authorize('com_content', 'edit', 'content', 'all');
		$can_edit_own = $user->authorize('com_content', 'edit', 'content', 'own');
		
		require_once('models/frontenduserarticlelist.php');
		$fual_model = new FUALModelFrontendUserArticleList();
		$article = $fual_model->getItem($id_article);

		if(is_object($article) && ($can_edit || ($can_edit_own && $user->id == $article->created_by))) {
			$article->alias = JRequest::getString('alias');
			$fual_model->updateContent($article);
			
			echo 'ok';
			jexit();
		}

		echo 'error';
		jexit();
	}

	function copy() {
		$cid = JRequest::getInt('cid');
		$itemid = JRequest::getInt('Itemid');
		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();
			
		$can_copy = $user->authorize('com_content','edit','content','all');
		$can_copy_own = $user->authorize('com_content','edit','content','own');
			
		require_once('models/frontenduserarticlelist.php');
		$fual_model = new FUALModelFrontendUserArticleList();
		$item_content = $fual_model->getItem($cid);
			
		if($can_copy || ($can_copy_own && $user->id == $item_content->created_by)) {
			if (is_object($item_content)) {
				$item_content->id = null;
				$item_content->state = 0;
				$db->insertObject('#__content',$item_content,'id');
			}
		}
		$this->setRedirect("index.php?option=com_frontenduserarticlelist&view=frontenduserarticlelist&Itemid=$itemid");
	}


}
?>
