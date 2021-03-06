<?php
defined('JPATH_BASE') or die();

JToolBarHelper::title(JText::_('Listbingo - Classified Flag Addon'), 'flag.png');
ListbingoHelper::cpanel('default','home');

gbaddons("flag.css.icons");
gbaddons("flag.css.layout");
?>
<form action="index.php" method="post" name="adminForm">
<fieldset class="adminform"><legend><?php echo JText::_('FLAG_LIST');?></legend>
<table width="100%" class="adminlist ">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('SN'); ?></th>
			<th class="title"><?php echo JText::_('AD'); ?></th>
			<th class="title"><?php echo JText::_('FLAGGED_AS'); ?></th>
			<th class="30%"><?php echo JText::_('FLAGGED_BY'); ?></th>
			<th class="30%"><?php echo JText::_('FLAGGED_DATE'); ?></th>
			<th class="30%"><?php echo JText::_('ACTION'); ?></th>
		</tr>
	</thead>
	<?php
	if(count($this->flaglist))
	{
	$i=0;
	foreach($this->flaglist as $fl)
	{
		$returnurl = base64_encode(JRoute::_('index.php?option='.$option.'&task=ads.edit&cid[]='.$fl->item_id,false));

		$approvelink = JRoute::_('index.php?option='.$option.'&task=addons.flag.admin.approve&cid='.$fl->item_id.'&fid='.$fl->id.'&returnurl='.$returnurl,false);
		$unapprovelink = JRoute::_('index.php?option='.$option.'&task=addons.flag.admin.unapprove&cid='.$fl->item_id.'&fid='.$fl->id.'&returnurl='.$returnurl,false);

		$i++;
		?>
	<tr>
		<td width="5%"><?php echo $i; ?></td>
		<td width="10%"><a href="<?php echo JRoute::_("index.php?option=$option&task=addons.flag.admin.view&id=".$fl->id);?>"><?php echo $fl->atitle; ?></a></td>
		<td width="30%" valign="top" class="key"><?php echo $fl->flag_id;
		
		?></td>
		<td><?php 
		if($fl->user_id)
		{
			echo $fl->username;
		}
		else
		{
			echo $fl->email." (".JText::_('GUEST').") ";
		}
		?></td>
		<td><?php echo date("d M Y",strtotime($fl->flag_date));?></td>
		<td><a href="<?php echo $approvelink;?>"><?php echo JText::_('APPROVE');?></a>
		&nbsp;|&nbsp; <a href="<?php echo $unapprovelink;?>"><?php echo JText::_('UNAPPROVE');?></a>
		</td>

	</tr>
	<?php
	}
}
else
{
	?>
	<tr>
	<td colspan="6"><?php echo JText::_('FLAGGED_ITEMS_NOT_AVAILABLE');?></td>
	</tr>
	<?php
}
	?>
</table>
</fieldset>
<input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
</form>



