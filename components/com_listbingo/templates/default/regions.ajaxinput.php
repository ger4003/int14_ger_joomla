<?php
defined('_JEXEC') or die('Restricted access');
if(!empty($this->lists['regions']))
{
	?><label id="region_idmsg" for="region_id"><?php echo JText::_('STATE');?></label><?php
	echo $this->lists['regions'];
}
else
{
	echo JText::_('REGIONS_NOT_AVAILABLE');
}
?>