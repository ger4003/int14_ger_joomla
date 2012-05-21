<?php
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
gbimport("gobingoo.addonsview");
class PmsViewCompose extends GAddonsView {
	
	
	function display($tpl=null)
	{	
		$currentuser = JFactory::getUser();
		
		if($this->params->get('enable_pms_for_guest',0) || !$currentuser->guest)
		{
			$this->assignRef('currentuser',$currentuser);
			parent::display();
		}
		
		
	}
	
	
}
?>