<?php
/**
 * @version 1.1 $Id$
 * @package Joomla
 * @subpackage EventList
 * @copyright (C) 2005 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * EventList is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );
?>
<fieldset class="el_fldst_attachments">
<legend><?php echo JText::_('COM_EVENTLIST_EVENT_ATTACHMENTS_TAB'); ?></legend>
<table class="adminform" id="el-attachments">
	<thead>
		<tr>
			<th style="width:15%"><?php echo JText::_('COM_EVENTLIST_ATTACHMENT_FILE'); ?></th>
			<th style="width:25%"><?php echo JText::_('COM_EVENTLIST_ATTACHMENT_NAME'); ?></th>
			<th style="width:40%"><?php echo JText::_('COM_EVENTLIST_ATTACHMENT_DESCRIPTION'); ?></th>
			<th style="width:20px"><?php echo JText::_('COM_EVENTLIST_ATTACHMENT_ACCESS'); ?></th>
			<th style="width:5px">&nbsp;</th>
		</tr>
	</thead>
	<tbody>

		<?php if (is_array($this->row->attachments)): ?>
		<?php foreach ($this->row->attachments as $file): ?>
		<tr>
			<td>
				<a title="<?php echo $file->file ?>"><?php echo substr($file->file, 0, 10); ?>...<?php echo substr($file->file, stripos($file->file, '.') - 5)?></a>
				<input type="hidden" name="attached-id[]" value="<?php echo $file->id; ?>"/>
			</td>
			<td><input type="text" name="attached-name[]" style="width: 100%" value="<?php echo $file->name; ?>" /></td>
			<td><input type="text" name="attached-desc[]" style="width: 100%" value="<?php echo $file->description; ?>" /></td>
			<td><?php echo JHTML::_('select.genericlist', $this->access, 'attached-access[]', 'class="inputbox" size="3"', 'value', 'text', $file->access); ?></td>
			<td><?php echo JHTML::image('administrator/images/publish_x.png', JText::_('COM_EVENTLIST_REMOVE_ATTACHEMENT')
			                         , array('id' => 'attach-remove'.$file->id,'class' => 'attach-remove')); ?></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
		<tr class="el-new-file">
			<td>
				<input type="file" name="attach[]" class="attach-field" size="10"></input>
			</td>
			<td>
				<input type="text" name="attach-name[]" class="inputbox" value="" />
			</td>
			<td>
				<input type="text" name="attach-desc[]" class="inputbox" value="" />
			</td>
			<td>
				<?php echo JHTML::_('select.genericlist', $this->access, 'attach-access[]', 'class="inputbox" size="3"', 'value', 'text', 0); ?>
			</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
</fieldset>