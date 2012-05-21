<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

?>

<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
	<ul class="item_list">
		<?php foreach ($this->items as $key => $item) : ?>
		<li class="<?php if($key % 2 != 0): ?>odd<?php endif; ?> <?php if($key == 0): ?>first<?php endif; ?>">
			<?php if ($this->params->get('show_create_date')) : ?>
			<span class="create_date"><?php echo $item->created;  ?></span>
			<?php endif; ?>
			
			<h4>
				<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
			</h4>
			<div class="clear"><!--  --></div>
			
			<?php if ($this->params->get('show_author')) : ?>
			<h5><?php echo JText::sprintf('Written by', ($item->created_by_alias ? $item->created_by_alias : $item->author)); ?></h5>
			<?php endif; ?>
			
			<div class="itemText"><?php echo $item->introtext; ?></div>
			
			<a class="more" href="<?php echo $item->link; ?>"><?php echo JText::_('More')?></a>
		</li>
		<?php endforeach; ?>
	</ul>

	<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
	<input type="hidden" name="sectionid" value="<?php echo $this->category->sectionid; ?>" />
	<input type="hidden" name="task" value="<?php echo $this->lists['task']; ?>" />
	<input type="hidden" name="filter_order" value="" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="limitstart" value="0" />
</form>
