<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

if(JRequest::getCmd('option') == 'com_eventlist' && JRequest::getCmd('view') == 'details') {
	$attachments = modElAttachmentHelper::getAttachments($params);
}
else {
	$attachments = false;
}

require(JModuleHelper::getLayoutPath('mod_elattachments'));

?>
