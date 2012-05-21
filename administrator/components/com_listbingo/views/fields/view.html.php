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
 * A classified ad component from gobingoo.com.
 *
 * code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Import Joomla! libraries
gbimport ( "gobingoo.view" );
class ListbingoViewFields extends GView {
	function display($tpl = null) {
		global $mainframe, $option;
		$filter = new stdClass ();
		
		$filter->limit = $mainframe->getUserStateFromRequest ( $option . 'fieldslimit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'cmd' );
		$filter->limitstart = $mainframe->getUserStateFromRequest ( $option . 'fieldslimitstart', 'limitstart', JRequest::getVar ( 'limitstart', 0 ), 'cmd' );
		
		$filter->order = $mainframe->getUserStateFromRequest ( $option . 'fieldfilter_order', 'filter_order', 'ordering', 'cmd' );
		$filter->order_dir = $mainframe->getUserStateFromRequest ( $option . 'fieldfilter_order_Dir', 'filter_order_Dir', '', 'word' );
		
		$search = $mainframe->getUserStateFromRequest ( $option . '.fieldsfilterkeyword', 'keyword', '', 'string' );
		$search = JString::strtolower ( $search );
		$filter->keyword = $search;
		
		$filter->published = JRequest::getInt ( 'lb_fieldsfilterpublished', -1);
		$filter->type = JRequest::getString ( 'lb_fieldsfiltertype','');
		
		$lists = array ();
		$status = array ();
		$status [] = JHTML::_ ( 'select.option', -1, JText::_ ( 'ALL' ), 'id', 'title' );
		$status [] = JHTML::_ ( 'select.option', '1', JText::_ ( 'PUBLISHED' ), 'id', 'title' );
		$status [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'UNPUBLISHED' ), 'id', 'title' );
		$lists ['published'] = JHTML::_ ( 'select.genericlist', $status, 'lb_fieldsfilterpublished', 'class="inputbox"', 'id', 'title', $filter->published );
		
		$ftype [] = JHTML::_ ( 'select.option', '', JText::_ ( 'Select Type' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'text', JText::_ ( 'Text' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'textarea', JText::_ ( 'TextArea' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'select', JText::_ ( 'Select' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'radio', JText::_ ( 'Radio' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'checkbox', JText::_ ( 'Checkbox' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'date', JText::_ ( 'Date' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'country', JText::_ ( 'Country' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'email', JText::_ ( 'Email' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'url', JText::_ ( 'Url' ), 'value', 'text' );
		$ftype [] = JHTML::_ ( 'select.option', 'attachment', JText::_ ( 'Attachment' ), 'value', 'text' );
		$lists ['types'] = JHTML::_ ( 'select.genericlist', $ftype, 'lb_fieldsfiltertype', 'class="inputbox" size="1"', 'value', 'text', $filter->type );
		
		$model = gbimport ( "listbingo.model.field" );
		$rows = $model->getList ( false, $filter );
		$total = $model->getListCount ( false );
		jimport ( 'joomla.html.pagination' );
		
		$pagenav = new JPagination ( $total, $filter->limitstart, $filter->limit );
		
		JFilterOutput::objectHTMLSafe ( $rows );
		JFilterOutput::objectHTMLSafe ( $filter );
		JFilterOutput::objectHTMLSafe ( $pagenav );
		
		$this->assignRef ( 'rows', $rows );
		$this->assignRef ( 'pagination', $pagenav );
		$this->assignRef ( 'filter', $filter );
		$this->assignRef ( 'lists', $lists );
		parent::display ( $tpl );
	
	}
}
?>