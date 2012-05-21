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
class ListbingoViewArticles extends GView {
	function display($tpl = null) {
		
		global $mainframe;
		
		// Initialize variables
		$db = &JFactory::getDBO ();
		$nullDate = $db->getNullDate ();
		
		$document = & JFactory::getDocument ();
		$document->setTitle ( JText::_ ( 'Article Selection' ) );
		
		JHTML::_ ( 'behavior.modal' );
		
		$template = $mainframe->getTemplate ();
		
		//$document->addStyleSheet ( JUri::root()."administrator/templates/$template/css/general.css" );
		

		$limitstart = JRequest::getVar ( 'limitstart', '0', '', 'int' );
		
		$model = gbimport ( "listbingo.model.article" );
		
		$lists = $model->_getLists ();
		
		require_once (JPATH_ROOT . DS . "administrator" . DS . "components" . DS . "com_content" . DS . "models" . DS . "element.php");
		$articlemodel = new ContentModelElement ();
		
		//Ordering allowed ?
		$ordering = ($lists ['order'] == 'section_name' && $lists ['order_Dir'] == 'ASC');
		
		$rows = $articlemodel->getList ();
		$page = $articlemodel->getPagination ();
		JHTML::_ ( 'behavior.tooltip' );
		
		
		$this->assignRef ( 'lists', $lists );
		$this->assignRef ( 'page', $page );
		$this->assignRef ( 'rows', $rows );
		
		parent::display ( $tpl );
	}
}
?>