<?php

/**
 * @Copyright Copyright (C) 2010 parvus
 * @license GNU/GPL GPLv3 http://www.gnu.org/copyleft/gpl.html
 *
 * This file is part of the Joomla! extension plugin
 * `attachments for eventlist´
 *
 * `attachments for eventlist´ is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * `attachments for eventlist´ is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with `attachments for eventlist´. If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @version $Id: com_eventlist.php 19 2011-01-16 08:32:43Z parvus $
 * @package `attachments for eventlist´
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.helper' );

class AttachmentsPlugin_com_eventlist extends AttachmentsPlugin
{
  /**
   * Constructor
   */
  function __construct()
  {
    parent::__construct( 'attachments_for_eventlist', 'com_eventlist', 'eventlist_event' );
  }

  /**
   * Get a URL to view the entity.
   * @param int $parent_id the ID for this parent object
   * @param string $parent_entity the type of entity for this parent type
   * @return a URL to view the entity (non-SEF form)
   */
  function getEntityViewURL( $parent_id, $parent_entity = 'default' )
  {
    switch ( $parent_entity )
    {
    case 'eventlist_category':
      $url = JRoute::_( 'index.php?view=categoryevents&id=' . $parent_id . '&option=com_eventlist', 'categoryevents' );
      break;

    case 'eventlist_location':
      $url = JRoute::_( 'index.php?view=venueevents&id=' . $parent_id . '&option=com_eventlist', 'venueevents' );
      break;

    case 'eventlist_event':
    case 'default':
    default:
      $url = JRoute::_( EventListHelperRoute::getRoute( $parent_id ), false, -1 );
      break;
    }

    //dump(array($parent_id, $parent_entity, $url), 'getEntityViewURL');
    return $url;
  }

  /**
   * Check to see if a custom title applies to this parent.
   * @note this function assumes that the parent_id's match
   * @param string $parent_entity parent entity for the parent of the list
   * @param string $rtitle_parent_entity the entity of the candidate attachment
   * list title (from params)
   * @return true if the custom title should be used
   */
  function checkAttachmentsListTitle( $parent_entity, $rtitle_parent_entity)
  {
    /** @todo */
    return ( $rtitle_parent_entity == 'eventlist_event' );
  }

  /**
   * Check to see if the parent is published.
   * @param int $parent_id the ID for this parent object
   * @param string $parent_entity the type of entity for this parent type
   * @return true if the parent is published
   */
  function isParentPublished( $parent_id, $parent_entity = 'default' )
  {
    $published = false;
    $object = self::_GetDbObject( $parent_id, $parent_entity );
    if ( $object )
    {
      if ( isset( $object->published ) )
      {
        $published = (bool)$object->published;
      }
    }
    //dump(array($parent_id, $parent_entity, $published), 'isParentPublished');
    return $published;
  }

  /**
   * Check if the parent may be viewed by the user.
   * @param int $parent_id the ID for this parent object
   * @param string $parent_entity the type of entity for this parent type
   * @return true if the parent may be viewed by the user
   */
  function userMayViewParent( $parent_id, $parent_entity='default' )
  {
    /* Check if the user has access to the details of the event. */
    require_once( $path . 'helpers/helper.php' );
    $eventListSettings =& ELHelper::config();
    $allowed = ( $elsettings->showdetails != 0 );

    //dump(array($parent_id, $parent_entity, $allowed), 'userMayViewParent');
    return $allowed;
  }

  /**
   * Check if the attachments should be hidden for this parent.
   * @param &object &$parent The object for the parent that onPrepareContent gives
   * @param int $parent_id The ID of the parent the attachment is attached to
   * @param string $parent_entity the type of entity for this parent type
   * @param &object &$params The Attachments component parameters object
   * @note this generic version only implements the 'frontpage' option. All
   *       other options should be handled by the derived classes for other
   *       content types.
   * @return true if the attachments should be hidden for this parent
   */
  function attachmentsHiddenForParent( &$parent, $parent_id, $parent_entity, &$params )
  {
    /* Only the 'frontpage' special keyword makes sense here:
     * @todo It is not implemented here (yet?) to ignore specific id's
     */
    $hidden = parent::attachmentsHiddenForParent( $parent, $parent_id, $parent_entity, $params );

    //dump(array($parent, $parent_id, $parent_entity, $params, $hidden), 'attachmentsHiddenForParent');
    return $hidden;
  }

  /**
   * Return true if the user may add an attachment to this parent.
   * @note all of the arguments are assumed to be valid; no sanity checking is
   * done. It is up to the caller to validate these objects before calling this
   * function.
   * @param int $parent_id The ID of the parent the attachment is attached to
   * @param string $parent_entity the type of entity for this parent type
   * @param bool $new_parent If true, the parent is being created and does not
   * exist yet
   * @return true if this user may add attachments to this parent
   */
  function userMayAddAttachment( $parent_id, $parent_entity, $new_parent=false )
  {
    jimport( 'joomla.application.component.helper' );
    $params =& JComponentHelper::getParams( 'com_attachments' );

    $plugin = &JPluginHelper::getPlugin( 'attachments', 'attachments_for_eventlist' );
    $pluginParams = new JParameter( $plugin->params );

    $user =& JFactory::getUser();

    /* At the very minimum, if no other accessright scheme is to be followed,
     * a user must be logged in to have allowance.
     */
    $allowed = ( $user->guest == 0 );

    if ( $allowed )
    {
      /* Is the plugin allowed to allow? */
      switch ( $parent_entity )
      {
      case 'eventlist_category':
        $allowed = $pluginParams->get( 'allowAttachmentsOnCategories', true );
        break;

      case 'eventlist_location':
        $allowed = $pluginParams->get( 'allowAttachmentsOnLocations', true );
        break;

      case 'eventlist_event':
        $allowed = $pluginParams->get( 'allowAttachmentsOnEvents', true );
        break;

      default:
        $allowed = false;
        break;
      }
    }

    if ( $allowed )
    {
      /* Must eventlist rules be followed? */

      if ( $new_parent )
      {
        /* Eventlist can not deny the author - who is still creating his event -
         * to attach an attachment, irresepective any setting made.
         */
      }
      else if ( $pluginParams->get( 'useEventListAccessrightRules', true ) )
      {
        /* Ensure the user may also edit the event. */
        $allowed = self::_EventlistAllowsToEditParent( $parent_id, $parent_entity );
      }
    }

    if ( $allowed )
    {
      /* Must attachments rules be followed? */

      if ( $new_parent )
      {
        /* Attachments can not deny the author - who is still creating his event -
         * to attach an attachment, irresepective any setting made.
         */
      }
      else if ( $pluginParams->get( 'useAttachmentsAccessrightRules', true ) )
      {
        $allowed = false;
        $object = self::_GetDbObject( $parent_id, $parent_entity );
        if ( $object )
        {
          if ( $parent_entity == 'eventlist_category' )
          {
            /* This is considered a special case. Only say yes to users that
             * may edit content.
             */
            $allowed = $user->authorize( 'com_content', 'edit', 'content', 'all' );
          }
          else
          {
            /* Check permission according to attachments */
            $whoCanAdd = $params->get( 'who_can_add', 'author' );
            if ( $whoCanAdd == 'author' )
            {
              /* Here, authors can only add attachments if they created the parent.
               * And everybody who is allowed to edit content, is allowed here too.
               */
              $allowed = self::_JoomlaAllowsToEditParent( $parent_id, $parent_entity );
            }
            else if ( $whoCanAdd == 'logged_in' )
            {
              /* Here, any logged in user can add attachments.
               * That check has already been made above.
               */
            }
            else
            {
              /* Here, the setting is either 'editor and above' or 'no_one'.
               * The setting 'no_one' is checked for by attachments self, so here
               * the setting 'editor and above' is assumed.
               * Generally everybody who is not yet mentioned above
               * but still is allowed to create content, is also allowed
               * to add attachments.
               */
              $allowed = self::_JoomlaAllowsToEditParent( 0, $parent_entity );
            }
          }
        }
      }
    }

    //dump(array($parent_id, $parent_entity, $new_parent, $allowed), 'userMayAddAttachment');
    //return true;
    return $allowed;
  }

  /**
   * Return true if this user may edit (modify/update/delete) this attachment
   * for this parent
   * @note All of the arguments are assumed to be valid; no sanity checking is
   * done. It is up to the caller to validate the arguments before calling this
   * function.
   * @param record $attachment database record for the attachment
   * @param int $parent_id The ID of the parent the attachment is attached to
   * @param $params The Attachments component parameters object
   * @return true if this user may edit this attachment
   */
  function userMayEditAttachment( &$attachment, $parent_id, &$params )
  {
    $plugin = &JPluginHelper::
        getPlugin( 'attachments', 'attachments_for_eventlist' );
    $pluginParams = new JParameter( $plugin->params );

    $user =& JFactory::getUser();

    /* At the very minimum, if no other accessright scheme is to be followed,
     * a user must be logged in to have allowance.
     */
    $allowed = ( $user->guest == 0 );

    if ( $allowed )
    {
      /* Must eventlist rules be followed? */
      if ( $pluginParams->get( 'useEventListAccessrightRules', true ) )
      {
        $allowed = self::_EventlistAllowsToEditParent( $attachment->parent_id, $attachment->parent_entity );
      }
    }

    if ( $allowed )
    {
      /* Must attachments rules be followed? */
      if ( $pluginParams->get( 'useAttachmentsAccessrightRules', true ) )
      {
        /* Attachments does not have specific rules to determine who may edit an
         * attachment. It may wrongly use the 'who_can_add' parameter, but I
         * choose not to: adding != editing.
         */
        $allowed = self::_JoomlaAllowsToEditParent( $attachment->parent_id, $attachment->parent_entity );
      }
    }

    /* And of course, the author of an attachment may always edit his own attachments. */
    //dump(array($attachment, $parent_id, $params, $allowed), 'userMayEditAttachment');
    return $allowed;
  }

  /**
   * Check to see if the user may access (see/download) the attachments
   * @param $attachment database record for the attachment
   * @return true if access is okay (false if not)
   */
  function userMayAccessAttachment( &$attachment )
  {
    jimport( 'joomla.application.component.helper' );
    $params =& JComponentHelper::getParams( 'com_attachments' );

    $plugin = &JPluginHelper::
        getPlugin( 'attachments', 'attachments_for_eventlist' );
    $pluginParams = new JParameter( $plugin->params );

    $user =& JFactory::getUser();

    /* If no other accessright scheme is to be followed, everyone should have
     * allowance.
     */
    $allowed = true;

    if ( $allowed )
    {
      /* Must eventlist rules be followed? */
      if ( $pluginParams->get( 'useEventListAccessrightRules', true ) )
      {
        /* Check if the user has access to the details of the event.
         * An attachment is considered a part of that.
         */
        require_once( $path . 'helpers/helper.php' );
        $eventListSettings =& ELHelper::config();
        $allowed = $elsettings->showdetails != 0;
      }
    }

    if ( $allowed )
    {
      /* Must attachments rules be followed? */
      if ( $pluginParams->get( 'useAttachmentsAccessrightRules', true ) )
      {
        $whoCanSee = $params->get( 'who_can_see', 'logged_in' );

        if ( $whoCanSee == 'anyone' )
        {
          $allowed = true;
        }
        else if ( $whoCanSee == 'logged_in' )
        {
          $allowed = ( $user->guest == 0 );
        }
      }
    }

    /* Always say yes to users that may access the back-end. */
    if ( $user->gid >= 23 )
    {
      $allowed = true;
    }

    //dump(array($attachment, $allowed), 'userMayAccessAttachment');
    return $allowed;
  }

  /**
   * Determine the parent entity
   * From the view and the class of the parent (row of onPrepareContent plugin),
   * determine what the entity type is for this entity.
   * Derived classes must overrride this if they support more than 'default'
   * entities.
   * @param &object &$parent The object for the parent (row) that
   * onPrepareContent gets
   * @return the correct entity (eg, 'default', 'section', etc) or false if this
   * entity should not be displayed.
   */
  function determineParentEntity( &$parent )
  {
    /* This is a major mess.
     * It is pretty hard to determine the entity: is the given class object
     * (parent) meant to display an event, a location or a category?
     *
     * - A parent meant to display a list of events of the same category can be
     * detected since it is the only one with catdescription set. This is
     * checked for first.
     *
     * - When displaying an event and the corresponding location at the same
     * page, the object used to display the contents is simply re-used in the
     * eventlist code. This was surely a fast way to get it working; but a bad
     * design decision remains just that. It is impossible to tell, just by
     * looking at the contents and the structure of the object, what this will
     * be used for.
     * I now (hopefully always validly) assume that when both an event and its
     * corresponding location are shown on one page, the event is always shown
     * first.
     * I simply add the property _IsSeenByAttachmentsForEventList when this
     * function is called the first time - the name of the property is
     * deliberately made long and wieldy; the chance that this name will ever
     * be used in code of eventlist itself is near 0.
     * The second time this function is called, I can detect its presence and
     * therefore conclude this is about displaying a location.
     *
     * _ Based on the decision (event or location) I also copy the did id
     * property resp. locid id property to the new property id, which is used
     * by the attachments extension to determine the parent id, given as
     * argument in subsequent calls.
     *
     * - When displaying a location only, it can best be detected by checking
     * the existence of catdescription and the non-existence of datdescription.
     * In the code below, it is therefore checked for in the last if-else
     * branch.
     */
    if ( isset( $parent->catdescription ) )
    {
      $entity = 'eventlist_category';
      /* $parent->id is already set & correct */
    }
    else if ( isset( $parent->datdescription ) )
    {
      if ( isset( $parent->_IsSeenByAttachmentsForEventList ) )
      {
        $entity = 'eventlist_location';
        $parent->id = $parent->locid;
      }
      else
      {
        $parent->_IsSeenByAttachmentsForEventList = 1;
        $entity = 'eventlist_event';
        $parent->id = $parent->did;
      }
    }
    else if ( isset( $parent->locdescription ) )
    {
      $entity = 'eventlist_location';
      /* $parent->id is already set & correct */
    }
    else
    {
      $entity = false;
    }

    //dump(array($parent, $entity), 'determineParentEntity');
    return $entity;
  }

  /**
   * Given the parent id and the attachment id, some general rules are checked
   * to determine whether the current user may edit the given attachment.
   * The author of the parent, the author of the attachment and any editor or
   * above are always allowed to edit attachments, regardless of the add
   * permissions.
   * @param $parent_id May be 0. If equal to 0, the function only determines if
   * this user may edit any content according to Joomla - and as I see it thus
   * also attachments.
   * @param $parent_entity
   * @return bool
   */
  function _JoomlaAllowsToEditParent( $parent_id, $parent_entity )
  {
    $allowed = false;

    $user =& JFactory::getUser();
    if ( $user->authorize( 'com_content', 'edit', 'content', 'all' ) )
    {
      $allowed = true;
    }
    else
    {
      $object = self::_GetDbObject( $parent_id, $parent_entity );
      if ( $user->id == $object->created_by )
      {
        $allowed = true;
      }
    }

    //dump(array($parent_id, $parent_entity, $allowed), '_JoomlaAllowsToEditParent');
    return $allowed;
  }

  /**
   * Given the parent id and parent entity, it is determined whether the current
   * user is allowed to edit the parent entity. This is largely based on the
   * rules eventlist itself adheres.
   * @param $parent_id
   * @param $parent_entity
   * @return bool
   */
  function _EventlistAllowsToEditParent( $parent_id, $parent_entity )
  {
    /* Always say yes to users that may access the back-end. */
    $user =& JFactory::getUser();
    $allowed = $user->gid >= 23;

    if ( $allowed == false )
    {
      $object = self::_GetDbObject( $parent_id, $parent_entity );
      if ( $object )
      {
        if ( isset( $object->created_by ) )
        {
          global $mainframe;
          $path = ( $mainframe->isAdmin() ) ? '..' : '.';
          $path .= '/components/com_eventlist/';
          require_once( $path . 'helpers/helper.php' );
          $eventListSettings =& ELHelper::config();
          require_once( $path . 'classes/user.class.php' );
          $allowed = ELUser::editaccess( $eventListSettings->eventowner,
              $object->created_by, $eventListSettings->eventeditrec,
              $eventListSettings->eventedit );
        }
      }
    }

    //dump(array($parent_id, $parent_entity, $allowed), '_EventlistAllowsToEditParent');
    return $allowed;
  }

  /**
   * Given the parent id and parent entity, retrieves the eventlist object
   * from the database.
   * @param $parent_id
   * @param $parent_entity
   * @return The database object or false when it could not be found.
   */
  function _GetDbObject( $parent_id, $parent_entity )
  {
    $parent_entity = JString::strtolower( $parent_entity );
    $map = array( 'eventlist_category' => 'categories',
        'eventlist_location' => 'venues',
        'eventlist_event' => 'events',
        'default' => 'events' );
    if ( array_key_exists( $parent_entity, $map ) )
    {
      $db =& JFactory::getDBO();
      $query = 'SELECT e.* FROM #__eventlist_' . $map[ $parent_entity ]
          . ' AS e WHERE e.id = ' . $db->Quote( $parent_id );
      $db->setQuery( $query );
      $event = $db->loadObject();
    }
    $object = isset( $event ) ? $event : false;

    //dump(array($parent_id, $parent_entity, $object), '_GetDbObject');
    return $object;
  }
}

?>
