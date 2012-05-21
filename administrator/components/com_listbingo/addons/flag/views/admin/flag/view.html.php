<?php
/**
 * Joomla! 1.5 component Listbingo
 *
 * @version $Id: view.html.php 2009-11-17 10:19:05 svn $
 * @author www.gobingoo.com
 * @package Joomla
 * @subpackage listbingo
 * @license GNU/GPL
 *
 * Code Bruce
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
gbimport("gobingoo.addonsview");
class FlagViewFlag extends GAddonsView {
	function display($tpl = null) {

		$id = JRequest::getInt( 'id',0 );
		
		$model=gbaddons('flag.model.flag');
		$row = $model->load($id);
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		$this->assignRef('row',$row);
		$this->assignRef('params',$params);
		parent::display($tpl);
	}
}
?>