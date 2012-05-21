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
class modElVenueHelper
{

	function getVenue($params)
	{
		$mainframe=JFactory::getApplication();
		$event_id = JRequest::getVar('id');

		$db=JFactory::getDBO();

		$query = "SELECT * FROM #__eventlist_venues WHERE id = (SELECT locid from #__eventlist_events WHERE id = $event_id)";
		$db->setQuery($query);

		$result = $db->loadObject();

		return $result;
	}

}
