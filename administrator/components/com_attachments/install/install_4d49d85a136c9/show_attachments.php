<?php
/**
 * System plugin to display the existing attachments in the editor
 *
 * @package Attachments
 * @subpackage Show_Attachments_in_Editor_Plugin
 *
 * @copyright Copyright (C) 2009-2010 Jonathan M. Cameron, All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://joomlacode.org/gf/project/attachments/frs/
 * @author Jonathan M. Cameron
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.event.plugin');


/**
 * Show Attachments in Editor system plugin
 *
 * @package Attachments
 */
class plgSystemShow_attachments extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param &object &$subject The object to observe
	 * @param array	 $config	An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgShow_attachments(&$subject, $config) 
	{
		parent::__construct($subject, $config);
	}


	/**
	 * Inserts the attachments list above the row of xtd-buttons
	 *
	 * @access	public
	 * @since	1.5
	 */
	function onAfterRender()
	{
		// Make sure this we can handle this
		global $option;
		$parent_type = $option;
		$parent_entity = 'default';
		$editor = 'article';

		// Handle sections and categories specially (since they are really com_content)
		if ($option == 'com_sections') {
			$parent_type = 'com_content';
			$parent_entity = 'section';
			$editor = 'section';
			}
		if ($option == 'com_categories') {
			$parent_type = 'com_content';
			$parent_entity = 'category';
			$editor = 'category';
			}

		// Get the article/parent handler
		if ( !JPluginHelper::importPlugin('attachments', 'attachments_plugin_framework') ) {
			// Exit if the framework does not exist (eg, during uninstallaton)
			return false;
			}
		if ( !function_exists('getAttachmentsPluginManager') ) {
			// Exit if the function does not exist (eg, during uninstallaton)
			return false;
			}
		$apm =& getAttachmentsPluginManager();
		if ( !$apm->attachmentsPluginInstalled($parent_type) ) {
			// Exit if there is no Attachments plugin to handle this parent_type
			return false;
			}

		// Get the article ID (strip off any SEF name appended with a colon)
		$parent_id = JRequest::getVar('id');
		if ( strpos($parent_id, ':') > 0 ) {
			$parent_id = substr($parent_id,0,strpos($parent_id,':'));
			if ( is_numeric($parent_id) ) {
				$parent_id = intval($parent_id);
				}
			else {
				$parent_id = null;
				}
			}

		// If that fails, try to get the id via 'cid'
		if (!$parent_id) {
			$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
			@$parent_id = $cid[0];
			if ( is_numeric($parent_id) ) {
				$parent_id = intval($parent_id);
				}
			else {
				$parent_id = null;
				}
			}

		// If we cannot determine the article ID, do not show any attachments
		if (!$parent_id) {
			return;
			}

		$task = JRequest::getCmd('task');
		$view = JRequest::getCmd('view');
		$layout = JRequest::getWord('layout');

		if ( $task =='edit' OR ( ($view == 'article') AND ( $layout=='form') ) ) {

			// Load the code from the attachments plugin to create the list
			require_once(JPATH_SITE.DS.'components'.DS.'com_attachments'.DS.'helper.php');

			// Get the article/parent handler
			$parent =& $apm->getAttachmentsPlugin($parent_type);
			$user_can_add = $parent->userMayAddAttachment($parent_id, $parent_entity);

			// Construct the attachment list
			$Itemid = JRequest::getInt( 'Itemid', 1);
			$from = 'editor';
			$attachments = AttachmentsHelper::attachmentListHTML($parent_id, $parent_type, $parent_entity,
																 $user_can_add, $Itemid, $from, false, false);

			// Insert the attachments, if any
			// NOTE: Assume that anyone editing the article can see its attachments
			if ( $attachments ) {
				$body = JResponse::getBody();
				$body = str_replace('<div id="editor-xtd-buttons">',
									$attachments . '<div id="editor-xtd-buttons">', $body);
				JResponse::setBody($body);
				}
			}
		else {
			return;
			}
	}
}

?>
