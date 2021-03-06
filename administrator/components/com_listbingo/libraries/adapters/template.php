<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Template installer
 *
 * @package		gobingoo.Framework
 * @subpackage	Installer
 * @since		1.5
 *
 *
 */
class JInstallerTemplate extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

	/**
	 * Custom install method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function install()
	{
		global $option;
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */


		// Set the extensions name
		$name =& $this->manifest->getElementByPath('name');
		$name = JFilterInput::clean($name->data(), 'string');
		$this->set('name', $name);

		$basePath = JPATH_ROOT;

		// Set the template root path
		$this->parent->setPath('extension_root', $basePath.DS.'components'.DS.$option.DS.'templates'.DS.strtolower(str_replace(" ", "_", $this->get('name'))));

		/*
		 * If the template directory already exists, then we will assume that the template is already
		 * installed or another template is using that directory.
		 */
		if (file_exists($this->parent->getPath('extension_root')) && !$this->parent->getOverwrite()) {
			JError::raiseWarning(100, JText::_('Template').' '.JText::_('Install').': '.JText::_('Another template is already using directory').': "'.$this->parent->getPath('extension_root').'"');
			return false;
		}

		// If the Template directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Addon').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		// If we created the template directory and will want to remove it if we have to roll back
		// the installation, lets add it to the installation step stack
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all the necessary files
		if ($this->parent->parseFiles($this->manifest->getElementByPath('files'), -1) === false) {
			// Install failed, rollback changes
			$this->parent->abort();
			return false;
		}
		if ($this->parent->parseFiles($this->manifest->getElementByPath('images'), -1) === false) {
			// Install failed, rollback changes
			$this->parent->abort();
			return false;
		}
		if ($this->parent->parseFiles($this->manifest->getElementByPath('css'), -1) === false) {
			// Install failed, rollback changes
			$this->parent->abort();
			return false;
		}

		// Parse optional tags
		/*$this->parent->parseFiles($root->getElementByPath('media'), $clientId);
		$this->parent->parseLanguages($root->getElementByPath('languages'));
		$this->parent->parseLanguages($root->getElementByPath('administration/languages'), 1);
		*/
		// Get the template description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort(JText::_('Template').' '.JText::_('Install').': '.JText::_('Could not copy setup file'));
			return false;
		}

		// Load template language file
		$lang =& JFactory::getLanguage();
		$lang->load('tpl_'.$name);

		return true;
	}

	/**
	 * Custom uninstall method
	 *
	 * @access	public
	 * @param	int		$path		The template name
	 * @param	int		$clientId	The id of the client
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function uninstall( $name, $clientId )
	{
		global $option;
		
		// Initialize variables
		$retval	= true;

		// For a template the id will be the template name which represents the subfolder of the templates folder that the template resides in.
		if (!$name) {
			JError::raiseWarning(100, JText::_('Template').' '.JText::_('Uninstall').': '.JText::_('Template id is empty, cannot uninstall files'));
			return false;
		}

		// Get the template root path
		$client =& JApplicationHelper::getClientInfo( $clientId );
		if (!$client) {
			JError::raiseWarning(100, JText::_('Template').' '.JText::_('Uninstall').': '.JText::_('Invalid application'));
			return false;
		}
		$this->parent->setPath('extension_root', $client->path.DS.'components'.DS.$option.DS.'templates'.DS.$name);
		$this->parent->setPath('source', $this->parent->getPath('extension_root'));

		$manifest =& $this->parent->getManifest();
		if (!is_a($manifest, 'JSimpleXML')) {
			// Make sure we delete the folders
			JFolder::delete($this->parent->getPath('extension_root'));
			JError::raiseWarning(100, JTEXT::_('Template').' '.JTEXT::_('Uninstall').': '.JTEXT::_('Package manifest file invalid or not found'));
			return false;
		}
		$root =& $manifest->document;

		// Remove files
		$this->parent->removeFiles($root->getElementByPath('media'), $clientId);
		//$this->parent->removeFiles($root->getElementByPath('languages'));
		//$this->parent->removeFiles($root->getElementByPath('administration/languages'), 1);

		// Delete the template directory
		if (JFolder::exists($this->parent->getPath('extension_root'))) {
			$retval = JFolder::delete($this->parent->getPath('extension_root'));
		} else {
			JError::raiseWarning(100, JText::_('Template').' '.JText::_('Uninstall').': '.JText::_('Directory does not exist, cannot remove files'));
			$retval = false;
		}
		return $retval;
	}
}
