<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class FUALViewFrontendUserArticleList extends JView {

	function display($tpl = null) {
		global $mainframe, $option;

		$params = &$mainframe->getParams();
		$user = &JFactory::getUser();
		$uri = &JFactory::getURI();

		// Require the com_content helper library
		//require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'icon.php');
		require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
		
		//load stylesheet and javascript
		$document = &JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_frontenduserarticlelist/assets/css/style.css');
		$document->addScript(JURI::base(true).'/components/com_frontenduserarticlelist/assets/javascript/script.js');

		//total of columns to show
		$total_columns = 0;
		$total_columns += ($params->get('id_column')) ? 1 : 0;
		$total_columns += ($params->get('title_column')) ? 1 : 0;
		$total_columns += ($params->get('published_column')) ? 1 : 0;
		$total_columns += ($params->get('section_column')) ? 1 : 0;
		$total_columns += ($params->get('category_column')) ? 1 : 0;
		$total_columns += ($params->get('author_column')) ? 1 : 0;
		$total_columns += ($params->get('created_date_column')) ? 1 : 0;
		$total_columns += ($params->get('start_publishing_column')) ? 1 : 0;
		$total_columns += ($params->get('finish_publishing_column')) ? 1 : 0;
		$total_columns += ($params->get('hits_column')) ? 1 : 0;
		$total_columns += ($params->get('edit_alias_column')) ? 1 : 0;
		$total_columns += ($params->get('copy_column')) ? 1 : 0;
		$total_columns += ($params->get('edit_column')) ? 1 : 0;
		$total_columns += ($params->get('trash_column')) ? 1 : 0;

		// Get data from the model
		$itens = &$this->get('Data');
		$total = &$this->get('Total');
		$pagination = &$this->get('Pagination');
		
		// Create a user access object for the user
		$access = new stdClass();
		$access->canEdit = $user->authorize('com_content', 'edit', 'content', 'all');
		$access->canEditOwn = $user->authorize('com_content', 'edit', 'content', 'own');
		$access->canPublish = $user->authorize('com_content', 'publish', 'content', 'all');

		$lists = $this->_getLists();

		$this->assign('action', str_replace('&', '&amp;', $uri->toString()));
		$this->assignRef('params', $params);
		$this->assignRef('total_columns', $total_columns);
		$this->assignRef('itens', $itens);
		$this->assignRef('lists', $lists);
		$this->assignRef('access', $access);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('user', $user);

		parent::display($tpl);
	}
	
	
	function &getItem($index = 0, &$params) {
		$item =& $this->itens[$index];
		$item->text = $item->introtext;

		// Get the page/component configuration and article parameters
		$item->params = clone($params);
		$aparams = new JParameter($item->attribs);

		// Merge article parameters into the page configuration
		$item->params->merge($aparams);

		return $item;
	}

	function _getLists() {
		global $mainframe, $option;

		// Initialize variables
		$db = &JFactory::getDBO();

		// Get some variables from the request
		$sectionid = JRequest::getVar( 'sectionid', -1, '', 'int' );
		$redirect = $sectionid;
		$filter_order = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', 'c.id', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_state = $mainframe->getUserStateFromRequest($option.'filter_state', 'filter_state', '', 'word');
		$filter_catid = $mainframe->getUserStateFromRequest($option.'filter_catid', 'filter_catid', -1, 'int');
		$filter_authorid = $mainframe->getUserStateFromRequest($option.'filter_authorid', 'filter_authorid', 0, 'int');
		$filter_sectionid = $mainframe->getUserStateFromRequest($option.'filter_sectionid', 'filter_sectionid', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search', '', 'string');
		$search = JString::strtolower($search);

		$where = array();

		// get list of categories for dropdown filter
		if($filter_sectionid >= 0) {
			$where[] = 'cc.section = ' . $db->Quote($filter_sectionid);
		}

		// Build the where clause of the content record query
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		// get list of categories for dropdown filter
		$query = 'SELECT cc.id AS value, cc.title AS text, section' .
				' FROM #__categories AS cc' .
				' INNER JOIN #__sections AS s ON s.id = cc.section' .
				$where .
				' ORDER BY s.ordering, cc.ordering';

		$lists['catid'] = $this->filterCategory($query, $filter_catid);

		// get list of sections for dropdown filter
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['sectionid'] = JHTML::_('list.section', 'filter_sectionid', $filter_sectionid, $javascript);

		// get list of Authors for dropdown filter
		$query = 'SELECT c.created_by, u.name' .
				' FROM #__content AS c' .
				' INNER JOIN #__sections AS s ON s.id = c.sectionid' .
				' LEFT JOIN #__users AS u ON u.id = c.created_by' .
				' WHERE c.state <> -1' .
				' AND c.state <> -2' .
				' GROUP BY u.name' .
				' ORDER BY u.name';
		$authors[] = JHTML::_('select.option', '0', '- '.JText::_('Select Author').' -', 'created_by', 'name');
		$db->setQuery($query);
		$authors = array_merge($authors, $db->loadObjectList());
		$lists['authorid'] = JHTML::_('select.genericlist',  $authors, 'filter_authorid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'created_by', 'name', $filter_authorid);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['filter_search'] = $search;

		// state filter
		$lists['state'] = JHTML::_('grid.state',  $filter_state);

		return $lists;
	}

	function filterCategory($query, $active = NULL) {
		// Initialize variables
		$db = & JFactory::getDBO();

		$categories[] = JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -');
		$db->setQuery($query);

		$categories = array_merge($categories, $db->loadObjectList());

		$category = JHTML::_('select.genericlist',  $categories, 'filter_catid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active);

		return $category;
	}


	function getEditIcon($article, $params, $access, $attribs = array()) {
		$user =& JFactory::getUser();
		$uri =& JFactory::getURI();
		$ret = $uri->toString();

		if ($params->get('popup')) {
			return;
		}

		if ($article->state < 0) {
			return;
		}

		if (!$access->canEdit && !($access->canEditOwn && $article->created_by == $user->get('id'))) {
			return;
		}

		JHTML::_('behavior.tooltip');

		$url = 'index.php?view=article&id='.$article->slug.'&task=edit&ret='.base64_encode($ret);
		$icon = $article->state ? 'ico_edit.png' : 'ico_edit_unpublished.png';
		$text = JHTML::_('image.site', $icon, '/components/com_frontenduserarticlelist/assets/images/', NULL, NULL, JText::_('Edit'));

		if ($article->state == 0) {
			$overlib = JText::_('Unpublished');
		} else {
			$overlib = JText::_('Published');
		}
		$date = JHTML::_('date', $article->created);
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::_($article->groups);
		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= htmlspecialchars($author, ENT_COMPAT, 'UTF-8');

		$button = JHTML::_('link', JRoute::_($url), $text);

		$output = '<span class="hasTip" title="'.JText::_( 'Edit Item' ).' :: '.$overlib.'">'.$button.'</span>';
		return $output;
	}

}
?>
