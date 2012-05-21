<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: article.php 2010-01-10 00:57:37 svn $
 * @author GOBINGOO
 * @package Joomla
 * @subpackage LISTBINGO
 * @license GNU/GPL
 *
 * A classified ad component from gobingoo.com.
 *
 * code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Import Joomla! libraries
gbimport ( "gobingoo.model" );
jimport ( 'joomla.filter.input' );

class ListbingoModelArticle extends GModel {
	
	function __construct() {
		parent::__construct ();
	}
	
	function _getLists() {
		global $mainframe;
		
		// Initialize variables
		$db = &JFactory::getDBO ();
		
		// Get some variables from the request
		$sectionid = JRequest::getVar ( 'sectionid', - 1, '', 'int' );
		$redirect = $sectionid;
		$option = JRequest::getCmd ( 'option' );
		$filter_order = $mainframe->getUserStateFromRequest ( 'articleelement.filter_order', 'filter_order', '', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest ( 'articleelement.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_state = $mainframe->getUserStateFromRequest ( 'articleelement.filter_state', 'filter_state', '', 'word' );
		$catid = $mainframe->getUserStateFromRequest ( 'articleelement.catid', 'catid', 0, 'int' );
		$filter_authorid = $mainframe->getUserStateFromRequest ( 'articleelement.filter_authorid', 'filter_authorid', 0, 'int' );
		$filter_sectionid = $mainframe->getUserStateFromRequest ( 'articleelement.filter_sectionid', 'filter_sectionid', - 1, 'int' );
		$limit = $mainframe->getUserStateFromRequest ( 'global.list.limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest ( 'articleelement.limitstart', 'limitstart', 0, 'int' );
		$search = $mainframe->getUserStateFromRequest ( 'articleelement.search', 'search', '', 'string' );
		if (strpos ( $search, '"' ) !== false) {
			$search = str_replace ( array ('=', '<' ), '', $search );
		}
		$search = JString::strtolower ( $search );
		
		// get list of categories for dropdown filter
		$filter = ($filter_sectionid >= 0) ? ' WHERE cc.section = ' . $db->Quote ( $filter_sectionid ) : '';
		
		// get list of categories for dropdown filter
		$query = 'SELECT cc.id AS value, cc.title AS text, section' . ' FROM #__categories AS cc' . ' INNER JOIN #__sections AS s ON s.id = cc.section' . $filter . ' ORDER BY s.ordering, cc.ordering';
		
		$lists ['catid'] = $this->filterCategory ( $query, $catid );
		
		// get list of sections for dropdown filter
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists ['sectionid'] = JHTML::_ ( 'list.section', 'filter_sectionid', $filter_sectionid, $javascript );
		
		// table ordering
		$lists ['order_Dir'] = $filter_order_Dir;
		$lists ['order'] = $filter_order;
		
		// search filter
		$lists ['search'] = $search;
		
		return $lists;
	}
	
	function filterCategory($query, $active = NULL) {
		// Initialize variables
		$db = & JFactory::getDBO ();
		
		$categories [] = JHTML::_ ( 'select.option', '0', '- ' . JText::_ ( 'Select Category' ) . ' -' );
		$db->setQuery ( $query );
		$categories = array_merge ( $categories, $db->loadObjectList () );
		
		$category = JHTML::_ ( 'select.genericlist', $categories, 'catid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active );
		
		return $category;
	}

}
?>