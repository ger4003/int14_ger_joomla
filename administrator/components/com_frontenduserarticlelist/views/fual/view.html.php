<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class FUALViewFUAL extends Jview
{
	public function display($tpl = null){

		$document = &JFactory::getDocument();

		$document->addStyleSheet(JURI::base().'/components/com_frontenduserarticlelist/assets/css/style.css');

		JToolBarHelper::title(JText::_('Frontend User Article List'), 'fual');
		JToolBarHelper::preferences('com_frontenduserarticlelist', '450', '480');

		parent::display($tpl);

	}

}
?>
