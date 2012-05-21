<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// if user hasn't permission, redirect to index.php
$user = &JFactory::getUser();
if(!$user->get('id') || $user->usertype == 'Registered') {
	header('location: index.php');
}

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

//require content language
$lang = &JFactory::getLanguage();
$lang->load('com_content');

// Create the controller
$classname = 'FUALController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();

?>