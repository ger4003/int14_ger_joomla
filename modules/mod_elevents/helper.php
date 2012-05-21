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
class modElEventsHelper
{

	function getEvents($params = array())
	{
		$mainframe=JFactory::getApplication();

		$db=JFactory::getDBO();

		$query = "SELECT * FROM #__eventlist_events WHERE dates >= CURDATE() AND published = 1 ORDER BY dates ASC, times ASC LIMIT 3";
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}
	
	/**
	 * Get categorie for the given event
	 * @param Object $event
	 */
	function getCategorie($event)
	{
		$mainframe=JFactory::getApplication();

		$db=JFactory::getDBO();

		$query = "SELECT * FROM #__eventlist_categories AS cat WHERE id = ( SELECT catid FROM #__eventlist_cats_event_relations AS rel WHERE rel.itemid = ".$event->id.")";
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result[0];
	}
	
	/**
	 * Get venue for the given event
	 * @param Object $event
	 */
	function getVenue($event)
	{
		$mainframe=JFactory::getApplication();

		$db=JFactory::getDBO();

		$query = "SELECT * FROM #__eventlist_venues WHERE id = ".$event->locid;
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result[0];
	}

}
