<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: articles.php 2010-01-10 00:57:37 svn $
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

gbimport ( "gobingoo.controller" );

class ListbingoControllerArticles extends GController {
	
	function __construct($config = array()) {
		parent::__construct ( $config );
	}
	
	function showArticles() {
			
		if (JRequest::getCmd ( 'view' ) == '') {
			JRequest::setVar ( 'view', 'articles' );
		}
		$this->item_type = 'Default';
		parent::display ();
		
	}
	
	
}
?>