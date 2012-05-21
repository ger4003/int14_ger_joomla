<?php
/**
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * @author bruce@gobingoo.com
 * @copyright www.gobingoo.com
 *
 * pms default listing view for admin
 *
 * code Bruce
 */

defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('Listbingo - Flag Details'), 'flag.png');
GHelper::cpanel('default','home');
JToolBarHelper::divider();
JToolBarHelper::cancel("addons.flag.admin");
JToolBarHelper::custom( 'addons.flag.admin.approve', 'default.png', 'default_f2.png', 'Approve', false, false );
JToolBarHelper::custom( 'addons.flag.admin.unapprove', 'default.png', 'default_f2.png', 'Unapprove', false, false );
gbaddons("flag.css.icons");

?>
<form name="adminForm" id="adminForm" action="index.php" method="post"
	enctype="multipart/form-data"><input type="hidden" name="id" id="id"
	value="<?php echo $this->row->id?>" />
<div class="col width-50">
<fieldset class="adminform"><legend><?php echo JText::_('FLAG_DETAILS');?></legend>
<table width="100%" cellpadding="5" class="admintable">
	<tr>
		<td width="10%" valign="top" class="key"><?php echo JText::_('AD');?></td>
		<td width="40%"><?php echo $this->row->ad->title;?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="10%" valign="top" class="key"><?php echo JText::_('FLAGGED_AS');?></td>
		<td width="40%">
		<?php 
		echo $this->row->flag_id;
	
		?>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="10%" valign="top" class="key"><?php echo JText::_('FLAGGED_BY');?></td>
		<td width="40%">
		<?php 
		if($this->row->user_id)
		{
			echo $this->row->username;
		}
		else
		{
			echo $this->row->email." (".JText::_('GUEST').") ";
		}
		?>
		</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td width="10%" valign="top" class="key"><?php echo JText::_('FLAGGED_DATE');?></td>
		<td width="40%">
		<?php echo ListbingoHelper::getDate($this->row->flag_date,$this->params->get('dateonlyformat'),false);?></td>
		<td>&nbsp;</td>
	</tr>

</table>
</fieldset>
</div>

<div class="col width-50">
<fieldset class="adminform"><legend><?php echo JText::_('COMMENTS');?></legend>
<table width="100%" cellpadding="5" class="admintable">
	<tr colspan="3">
		<td width="40%"><?php echo $this->row->comment;?></td>
	</tr>
</table>
</fieldset>
</div>
<input type="hidden" name="option" value="<?php echo $option?>" /> 
<input type="hidden" name="task" value="" />
<input type="hidden" name="cid" value="<?php echo $this->row->item_id?>" />
<input type="hidden" name="fid" value="<?php echo $this->row->id;?>" />
</form>