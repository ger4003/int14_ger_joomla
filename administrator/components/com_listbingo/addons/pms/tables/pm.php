<?php
/**
 * Joomla! 1.5 component Listbingo
 *
 * @version $Id: pm.php 2009-12-21 02:02:23 svn $
 * @author http://www.gobingoo.com
 * @package gobingoo
 * @subpackage Agent
 * @license GNU/GPL
 *
 * A Classified Ad Component from gobingoo.com
 *
 * Code Bruce
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
gbimport ( "gobingoo.table" );
// Include library dependencies
jimport ( 'joomla.filter.input' );

/**
 * Table class
 *
 * @package          Joomla
 * @subpackage		EstateBingo
 */
class JTablePm extends GTable {
	
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $subject = null;
	
	var $ad_id = null;
	
	var $message_to = null;
	
	var $message_from = null;
	
	var $message = null;
	
	var $status = null;
	
	var $replyto = null;
	
	var $contact_name = null;
	
	var $contact_email = null;
	
	var $message_date = null;
	
	var $contact_phone = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct ( '#__gbl_messages', 'id', $db );
	}
	
	function check() {
		$datenow = & JFactory::getDate ();
		$this->message_date = $datenow->toFormat ( "%Y-%m-%d-%H-%M-%S" );
		
		$error = array ();
		
		if (empty ( $this->contact_name )) {
			$error [] = JText::_ ( 'NAME_REQUIRED' );
		
		}
		
		if (empty ( $this->contact_email )) {
			$error [] = JText::_ ( 'EMPTY_EMAIL' );
		} else {
			if (eregi ( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->contact_email )) {
				// do nothings			
			} else {
				$error [] = JText::_ ( 'INVALID_EMAIL' );
			}
		}
		
		if (empty ( $this->message )) {
			$error [] = JText::_ ( 'MESSAGE_REQUIRED' );
		
		}
		if (count ( $error ) > 0) {
			$this->setError ( implode ( " | ", $error ) );
			return false;
		}
		
		return true;
	}

}
?>