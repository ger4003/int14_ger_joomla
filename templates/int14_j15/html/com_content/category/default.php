<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>

<?php if ($this->params->get('show_page_title', 1)) : ?>
<div class="heading_wrap">
	<div class="componentheading <?php echo $this->params->get('pageclass_sfx');?>">
		<h2><?php echo $this->category->sectiontitle; ?></h2>
		<?php // TODO: get secion link ?>
	</div>
</div>
<div class="clear"><!--  --></div>
<?php endif; ?>

<div class="contentheading <?php echo $this->params->get('pageclass_sfx');?>">
	<h3><?php echo $this->escape($this->params->get('page_title')); ?></h3>
	<?php // TODO: get secion name and link ?>
</div>
<div class="clear"><!--  --></div>
	
<div class="contentpane <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<!-- contentdescription -->
	<div class="contentdescription <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->category->description; ?>
	</div>
	<div class="clear"><!--  --></div>
	
	<?php $this->items =& $this->getItems(); ?>
	
	<?php echo $this->loadTemplate('items'); ?>

	<?php if ($this->access->canEdit || $this->access->canEditOwn) : ?>
		<?php echo JHTML::_('icon.create', $this->category  , $this->params, $this->access); ?>
	<?php endif; ?>
</div>