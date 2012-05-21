<?php
/**
 * Joomla! 1.5 component Listbingo
 *
 * @version $Id: flag.php 2009-12-21 02:02:23 svn $
 * @author http://www.gobingoo.com
 * @package gobingoo
 * @subpackage flag
 * @license GNU/GPL
 *
 * A classsified ad Component from gobingoo.com
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
 * @subpackage		Listbingo
 */
class JTableFlag extends GTable {
	
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	var $item_id = null;
	
	var $flag_id = null;
	
	var $flag_date = null;
	
	var $ipaddress = null;
	
	var $comment = null;
	
	var $email = null;
	
	var $user_id = null;
	
	var $ordering = null;
	
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct ( '#__gbl_flag', 'id', $db );
	}
	
	function check() {
		
		$datenow = & JFactory::getDate ();
		$this->flag_date = $datenow->toFormat ( "%Y-%m-%d-%H-%M-%S" );
		
		$error = array ();
		
		if (!$this->flag_id) {
			$error [] = JText::_ ( 'FLAG_CRITERIA_REQUIRED' );
		
		}
		
		if (empty ( $this->email )) {
			$error [] = JText::_ ( 'EMPTY_EMAIL' );
		} else {
			if (eregi ( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->email )) {
				// do nothings			
			} else {
				$error [] = JText::_ ( 'INVALID_EMAIL' );
			}
		}
		
		if (empty ( $this->comment )) {
			$error [] = JText::_ ( 'COMMENTS_REQUIRED' );
		
		}
		
		if (count ( $error ) > 0) {
			$this->setError ( implode ( " | ", $error ) );
			return false;
		}
		
		
		return true;
	}

}
?>