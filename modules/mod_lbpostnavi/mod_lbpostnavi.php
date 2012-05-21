<?php


// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DS.'helper.php');

$config=modLbPostnaviHelper::getConfiguration();
$list=modLbPostnaviHelper::getCategories($params);
$productcount=modLbPostnaviHelper::_countTotalProducts($config);

$indcount = array();
if(count($productcount)>0)
{
	foreach($productcount as $ac)
	{
		$indcount[$ac->id] = $ac->adCount;
	}
}


/*echo "<pre>";
 print_r($adcount);
 exit;*/

require(JModuleHelper::getLayoutPath('mod_lbpostnavi'));
