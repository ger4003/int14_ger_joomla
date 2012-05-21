<?php
defined('_JEXEC') or die('Restricted access');
if(!empty($this->lists['regions']))
{
	?><strong><?php echo JText::_('STATE');?></strong><br /><?php
	echo $this->lists['regions'];
}
else
{
	echo JText::_('REGIONS_NOT_AVAILABLE');
}
?>