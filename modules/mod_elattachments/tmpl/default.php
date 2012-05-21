<?php if(!$attachments): ?>
<p>Keine Anhänge verfügbar</p>
<?php else: ?>
<ul>
	<?php foreach($attachments as $attachment):?>
	<li>
		<dl class="icon_list">
			<dt>
				<?php echo JHTML::link(
											'index.php?option=com_eventlist&task=getfile&format=raw&file='.$attachment->id, 
											JHTML::image(
												'components/com_eventlist/assets/images/download_16.png', 
												JText::_('Download')
											),
											array('title' => $attachment->description)
										); ?>
			</dt>
			<dd>
				<?php echo $attachment->name; ?>
			</dd>
		</dl>
		<div class="clear"><!--  --></div>
	</li>
	<?php endforeach;?>
</ul>

<?php endif; ?>