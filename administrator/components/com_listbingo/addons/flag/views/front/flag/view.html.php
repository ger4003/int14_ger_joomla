<?php
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
gbimport("gobingoo.addonsview");
class FlagViewFlag extends GAddonsView {
	
	
	function display($tpl=null)
	{		
		$configmodel=gbimport("listbingo.model.configuration");
		$params=$configmodel->getParams();
		$user = JFactory::getUser();
		
		if($params->get('enable_flag_for_guest',0) || !$user->guest)
		{
			$this->assignRef('params',$params);
			parent::display();
		}

	}
	
	function customDisplay($tpl=null)
	{
		parent::display();
	}
	
	
}
?>