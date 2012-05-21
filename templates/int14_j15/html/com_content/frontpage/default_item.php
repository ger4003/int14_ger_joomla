<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$canEdit	= ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>
	
<?php if ($this->item->params->get('show_title')) : ?>
	<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
	<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->item->params->get( 'pageclass_sfx' ); ?>">
		<h3><?php echo $this->item->title; ?></h3>
	</a>
	<?php else : ?>
	<h3><?php echo $this->escape($this->item->title); ?></h3>
	<?php endif; ?>
<?php endif; ?>
<h4>
	<a href="<?php echo ContentHelperRoute::getSectionRoute($this->item->sectionid); ?>"><?php echo $this->item->section ?></a>: 
	<a href="<?php echo ContentHelperRoute::getCategoryRoute($this->item->catid, $this->item->sectionid); ?>"><?php echo $this->item->category;?></a>
</h4>

<div class="itemText">
	<?php
	$maxWordCount = 23;
	$words = str_word_count(strip_tags($this->item->text), 1, 'äöüß01423456789&;');
	echo implode(" ", array_slice($words, 0, $maxWordCount));
	?>
</div>

<a href="<?php echo $this->item->readmore_link; ?>" class="readon<?php echo $this->item->params->get('pageclass_sfx'); ?>">
	<?php echo JText::sprintf('Read more...'); ?>
</a>

<div class="clear"><!--  --></div>