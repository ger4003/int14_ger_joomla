<?php
/**
 * Joomla! 1.5 component Listbingo
 *
 * @version $Id: type.php 2009-10-13 00:39:06 svn $
 * @author www.gobingoo.com
 * @package Joomla
 * @subpackage Listbingo
 * @license GNU/GPL
 * @code Bruce
 *
 *
 *
 *
 */
defined('_JEXEC') or die('Restricted access');

gbimport("gobingoo.controller");

class ListbingoControllerDefault extends GController
{


	function display()
	{
		$view=$this->getView('default','html');
		$view->display();
	
	}
}
?>