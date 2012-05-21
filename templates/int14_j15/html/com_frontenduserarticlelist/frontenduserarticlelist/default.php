<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$itemid = JRequest::getInt('Itemid');
?>

<div class="heading_wrap">
	<div class="componentheading <?php echo $this->params->get('pageclass_sfx');?>">
		<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>
	</div>
</div>

<form name="adminForm" method="post" action="<?php echo $this->action; ?>">

<table width="100%" cellpadding="3" cellspacing="3" border="1" id="table_frontend_user_article_list">
<thead>
	<tr>
		<?php
		if($this->params->get('id_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'ID', 'c.id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('title_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Title', 'c.title', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('published_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Published', 'c.state', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('section_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Section', 'section', $this->lists['order_Dir'], $this->lists['order']); ?>
				<?php if($this->params->get('category_column')) :?>
				/
				<?php echo JHTML::_('grid.sort', 'Category', 'category', $this->lists['order_Dir'], $this->lists['order']); ?>
				<?php endif;?>
			</th>
			<?php
		endif;
		?>		
		<?php
		if($this->params->get('author_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Author', 'author', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('created_date_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'CREATED_DATE', 'c.created', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('start_publishing_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Start publishing', 'c.publish_up', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('finish_publishing_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Finish publishing', 'c.publish_down', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('hits_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'Hits', 'c.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<th style="" align="center"><?php echo JText::_('EDIT'); ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="<?php echo $this->total_columns; ?>" align="center">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
<tbody>

	<?php
	$count_itens = count($this->itens);

	//without article
	if(!$count_itens) { ?>
		<tr>
			<td colspan="<?php echo $this->total_columns; ?>" align="center">
				<?php echo JText::_('NO_ARTICLES_FOUND'); ?>
			</td>
		</tr>
	<?php
	}
	else {
		$k = 0;
		for($i=0; $i < $count_itens; $i++) {
			$row = &$this->getItem($i, $this->params);

			if($this->access->canEdit || ($this->access->canEditOwn && $this->user->id == $row->created_by)) :
			?>
	
			<tr class="<?php echo "fual_row$k"; ?>">
				<?php if($this->params->get('id_column')) : ?>
				<td align="center">
					<?php echo $row->id; ?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('title_column')) : ?>
				<td style="font-weight:bold;">
					<?php
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->id, $row->catslug, $row->sectionid));
					if($row->state > 0) {
						echo "<a href='$link'>{$row->title}</a>";
					}
					else {
						echo $row->title;
					}
					echo "<input type='hidden' id='fual_{$row->id}_title' value='{$row->title}' />";
					echo "<input type='hidden' id='fual_{$row->id}_alias' value='{$row->alias}' />";
					?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('published_column')) : ?>
				<td align="center">
					<?php
						$override = false;
						if(($this->user->usertype == 'Editor' && $this->params->get('editors_publishes'))
							|| ($this->user->usertype == 'Author' && $this->params->get('authors_publishes'))) {
							$override = true;
						}
						if(($this->access->canPublish && $row->state != -2) ||
							($this->user->id == $row->created_by && $override)) {
							
							$url = "index.php?option=com_frontenduserarticlelist&view=frontenduserarticlelist&task=unPublish&cid={$row->id}&Itemid=" . JRequest::getInt('Itemid');
							$link = JRoute::_($url);
							echo "<a href='$link'>";
						}
						$img = $this->baseurl . "/components/com_frontenduserarticlelist/assets/images/";
						$img .= ($row->state > 0) ? "publish_g.png" : "publish_r.png";
						$alt = ($row->state > 0) ? JText::_('Published') : JText::_('Unpublished');
						
						echo "<img src='$img' alt='$alt' title='$alt' />";

						if(($this->access->canPublish && $row->state != -2) ||
							($this->user->id == $row->created_by && $override)) {
							echo '</a>';
						}
					?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('section_column')) : ?>
				<td>
					<a href="<?php echo ContentHelperRoute::getSectionRoute($row->sectionid); ?>" style="font-weight:normal;">
						<?php echo $row->section; ?>
					</a>						
					<?php if($this->params->get('category_column')) :?>
					&frasl; <br /> 
					<a href="<?php echo ContentHelperRoute::getCategoryRoute($row->catid, $row->sectionid); ?>" style="font-weight:normal;">
						<?php echo $row->category; ?>
					</a>
					<?php endif;?>
				</td>
				<?php endif; ?>				
				
				<?php if($this->params->get('author_column')) : ?>
				<td>
					<?php
					if(strlen(trim($row->created_by_alias))) {
						echo $row->created_by_alias;
						echo "<br />({$row->author})";
					}
					else {
						echo $row->author;
					}
					?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('created_date_column')) : ?>
				<td align="center">
					<?php echo JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('start_publishing_column')) : ?>
				<td align="center">
					<?php echo JHTML::_('date', $row->publish_up, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('finish_publishing_column')) : ?>
				<td align="center">
					<?php
					if($row->publish_down == '0000-00-00 00:00:00') {
						echo JText::_('Never');
					}
					else {
						echo JHTML::_('date', $row->publish_down, JText::_('DATE_FORMAT_LC4'));
					}
					?>
				</td>
				<?php endif; ?>
				
				<?php if($this->params->get('hits_column')) : ?>
				<td align="center">
					<?php echo $row->hits; ?>
				</td>
				<?php endif; ?>
				
				<td align="center">
					<?php 
					/* edit alias */
					if($this->params->get('edit_alias_column') && $row->state != -2) : 
						$img = $this->baseurl . "/components/com_frontenduserarticlelist/assets/images/ico_alias.png";
						$alt = JText::_('EDIT_ALIAS');
						$title = $alt . ' :: ' . $row->alias;
						echo "<a href='javascript:void(0);' onclick='fualEditAlias({$row->id},event);'>";
						echo "<img src='$img' alt='$alt' title='$title' class='lhasTip' id='img_alias_{$row->id}' />";
						echo "<a/>";
					endif; 
					
					/* copy */ 
					if($this->params->get('copy_column') && $row->state != -2) :
						$text = JHTML::_('image.site', 'ico_copy.png', '/components/com_frontenduserarticlelist/assets/images/', NULL, NULL, JText::_('Copy'));
						$url = "index.php?option=com_frontenduserarticlelist&controller=&task=copy&cid={$row->id}&Itemid=$itemid";
						$msg_confirm = JText::_('WOULD_YOU_LIKE_TO_CREATE_AN_ARTICLE_COPY', true);
						$attr = array('onclick'=>"if(!confirm('$msg_confirm')) { return false; }",
								"title"=>JText::_('CREATE_A_COPY'));
						echo JHTML::_('link', JRoute::_($url), $text, $attr);
					endif; 
					
					/* edit */
					if($this->params->get('edit_column')) :
						echo $this->getEditIcon($row, $row->params, $this->access);
					endif; 
					
					/* trash */
					if($this->params->get('trash_column')) :
						if($row->state == -2) {
							$msg_confirm = JText::_('TEM_CERTEZA_QUE_DESEJA_RESTAURAR_ESTE_ITEM', true);
							$img = $this->baseurl . "/components/com_frontenduserarticlelist/assets/images/ico_restore.png";
							$alt = JText::_('RESTAURAR_ITEM');
						}
						else {
							$msg_confirm = JText::_('TEM_CERTEZA_QUE_DESEJA_ENVIAR_ESTE_ITEM_PARA_A_LIXEIRA', true);
							$img = $this->baseurl . "/components/com_frontenduserarticlelist/assets/images/ico_trash.png";
							$alt = JText::_('MOVER_PARA_A_LIXEIRA');
						}
						$link = JRoute::_("index.php?option=com_frontenduserarticlelist&controller=&task=trash&cid={$row->id}&Itemid=$itemid");

						echo "<a href='$link' onclick=\"if(!confirm('$msg_confirm')) { return false; }\">";
						echo "<img src='$img' alt='$alt' title='$alt' />";
						echo "</a>";
						
					endif;
					?>
				</td>
			</tr>
			
			<?php
			$k = 1 - $k;
			endif;
		}
	}
	?>

</tbody>
</table>

<input type="hidden" name="option" value="com_frontenduserarticlelist" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="frontenduserarticlelist" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

<?php echo JHTML::_('form.token'); ?>
</form>

<?php
//load template form edit alias
echo $this->loadTemplate('edit_alias');
?>
