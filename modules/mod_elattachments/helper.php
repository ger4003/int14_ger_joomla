<?php
/**
 * @version		$Id: helper.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.base.tree');

jimport('joomla.utilities.simplexml');

/**
 * mod_elvenue Helper class
 *
 * @static
 * @package		Joomla
 * @since		1.5
 */
class modElAttachmentHelper
{

	function getAttachments($params)
	{
		$mainframe=JFactory::getApplication();
		$event_id = JRequest::getVar('id');
		
		switch(JRequest::getVar('view')) {
			default:
				$elObject = 'event'.$event_id;
		}

		$db=JFactory::getDBO();

		$query = "SELECT * FROM #__eventlist_attachments WHERE `object`='$elObject'";
		$db->setQuery($query);

		$result = $db->loadObjectList();
		return $result;
	}

}
