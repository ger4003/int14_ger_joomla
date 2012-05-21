<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class FUALModelFrontendUserArticleList extends JModel {

	var $_data;

	var $_total = null;

	var $_pagination = null;


	function __construct() {
		parent::__construct();

		global $mainframe, $option;

		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}


	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal() {
		// Lets load the content if it doesn't already exist
		if(empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination() {
		// Lets load the content if it doesn't already exist
		if(empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery() {
		// Get the WHERE and ORDER BY clauses for the query
		$where = $this->_buildContentWhere();
		$orderby = $this->_buildContentOrderBy();

		$query = "SELECT c.*, u.name AS author, u.usertype, cc.title AS category, s.title AS section, 
					CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(':', c.id, c.alias) ELSE c.id END as slug, 
					CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(':', cc.id, cc.alias) ELSE cc.id END as catslug, 
					g.name AS groups, s.published AS sec_pub, cc.published AS cat_pub, s.access AS sec_access, cc.access AS cat_access 
					FROM #__content AS c 
					LEFT JOIN #__categories AS cc ON cc.id = c.catid 
					LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = 'content' 
					LEFT JOIN #__users AS u ON u.id = c.created_by 
					LEFT JOIN #__groups AS g ON c.access = g.id $where $orderby";
		return $query;
	}

	function _buildContentOrderBy() {
		global $mainframe, $option;

		$filter_order = $mainframe->getUserStateFromRequest($option.'filter_order', 'filter_order', 'c.created', 'cmd');
		///TODO: resolver essa gambi (um dia ou nunca iauhaiuhaiu)
		$filter_order = ($filter_order == "c.ordering") ? "c.created" : $filter_order; //afffff
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.'filter_order_Dir', 'filter_order_Dir', 'desc', 'word');

		$orderby = "ORDER BY $filter_order $filter_order_Dir";

		return $orderby;
	}

	function _buildContentWhere() {
		global $mainframe, $option;

		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();

		$where = array();

		$filter_search = $mainframe->getUserStateFromRequest($option.'filter_search', 'filter_search', strtolower(JRequest::getString('filter_search')), 'string');
		$filter_search = $db->Quote( '%'.$db->getEscaped($filter_search, true ).'%', false );

		$filter_state = $mainframe->getUserStateFromRequest($option.'filter_state', 'filter_state', '', 'word');
		$filter_sectionid = $mainframe->getUserStateFromRequest($option.'filter_sectionid', 'filter_sectionid', -1, 'int');
		$filter_catid = $mainframe->getUserStateFromRequest($option.'filter_catid', 'filter_catid', -1, 'int');
		$filter_authorid = $mainframe->getUserStateFromRequest($option.'filter_authorid', 'filter_authorid', 0, 'int');

		if(strlen($filter_search) > 0) {
			$where2 = array();
			$where2[] = "c.title like $filter_search";
			$where2[] = "c.introtext like $filter_search";
			$where2[] = "c.fulltext like $filter_search";
			$where2[] = "c.metakey like $filter_search";
			$where2[] = "c.metadesc like $filter_search";
			$where[] = '((' . implode( ') OR (', $where2 ) . '))';
		}
		if($filter_state) {
			if ($filter_state == 'P') {
				$where[] = 'c.state = 1';
			}
			elseif($filter_state == 'U') {
				$where[] = 'c.state = 0';
			}
		}
		if($filter_sectionid >= 0) {
			$filter_s = ($filter_sectionid > 0) ? $db->Quote($filter_sectionid) : "''";
			$where[] = 'c.sectionid = '.$filter_s;
		}
		if($filter_catid > 0 && $filter_sectionid != 0) {
			$where[] = 'c.catid = '.$db->Quote($filter_catid);
		}
		if($filter_authorid) {
			$where[] = "c.created_by = '$filter_authorid'";
		}
		if($user->usertype == 'Author') {
			$where[] = "c.created_by = '{$user->id}'";
		}

		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

		return $where;
	}

	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getData() {
		if(empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}
	
	function getItem($id_content) {
		$db = &JFactory::getDBO();
		$user = &JFactory::getUser();

		$sql = "SELECT * from #__content where id = '$id_content'";
		$db->setQuery($sql);
		$item = $db->loadObject();
		
		return $item;
	}

	function updateContent($content) {
		$db = &JFactory::getDBO();
		$db->updateObject('#__content', $content, 'id');
	}

}
