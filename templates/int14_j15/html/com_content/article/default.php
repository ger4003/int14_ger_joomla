<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));

/* check for static content */
$isStaticContent = false;

if(!is_null($this->article)
		&& array_key_exists('sectionid', $this->article)
		&& array_key_exists('catid', $this->article)
		&& JRequest::getCmd('view') != "frontpage")
{
	// check sectionid and categoryid
	if($this->article->sectionid == 0 && $this->article->catid == 0)
	$isStaticContent = true;
}


?>

<div id="content_wrap" class="<?php echo $this->params->get('pageclass_sfx');?>">

	<?php /* *********** COMMON CONTENT *********** */
	if(!$isStaticContent): ?>

	<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
	<div class="heading_wrap">
		<div class="componentheading">
			<h2><?php echo $this->article->section; ?></h2>

			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<p class="subheader"><?php echo $this->article->category; ?></p>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>


	<?php if ($canEdit) : ?>
	<div class="button_wrap button_wrap_top">
		<?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?>
	</div>
	<?php endif; ?>


	<?php if ($this->params->get('show_title')) : ?>
	<div class="contentheading <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<h3><?php echo $this->escape($this->article->title); ?></h3>
	</div>
	<?php endif; ?>

	<?php  if (!$this->params->get('show_intro')) : ?>
		<?php echo $this->article->event->afterDisplayTitle; ?>
	<?php endif; ?>

	<?php echo $this->article->event->beforeDisplayContent; ?>

	<div class="contenttext">
		<span class="intro"><?php echo $this->article->introtext; ?></span>
		<?php echo $this->article->fulltext; ?>
	</div>

	<?php /* *********** STATIC CONTENT *********** */
	else: ?>

	<?php if ($this->params->get('show_page_title', 1)) : ?>
	<div class="contentheading">
		<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>

		<!--
		<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
			<div class="imageWrap">
				<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'.  $this->section->image;?>" align="<?php echo $this->section->image_position;?>" hspace="6" alt="<?php echo $this->section->image;?>" />
			</div>
		<?php endif; ?>
		 -->
	</div>
	<div class="clear"><!--  --></div>
	<?php endif; ?>

	<div class="contentpane">
		<?php if ($this->article->fulltext == ''): ?>
			<?php echo $this->article->introtext; ?>
		<?php else: ?>
			<span class="intro"><?php echo $this->article->introtext; ?></span>
			<?php echo $this->article->fulltext; ?>
		<?php endif; ?>
		<div class="clear"><!--  --></div>
	</div>

	<?php endif; ?>
</div><!-- /content_wrap -->