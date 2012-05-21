<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
<?php if ($this->params->get('show_page_title', 1)) : ?>
<!-- <div class="heading_wrap"> -->
	<div class="contentheading <?php echo $this->params->get('pageclass_sfx');?>">
		<h2><?php echo $this->escape($this->params->get('page_title')); ?></h2>
		
		<!-- 
		<?php if ($this->params->get('show_description_image') && $this->section->image) : ?>
			<div class="imageWrap">
				<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'.  $this->section->image;?>" align="<?php echo $this->section->image_position;?>" hspace="6" alt="<?php echo $this->section->image;?>" />
			</div>
		<?php endif; ?>
		 -->
	</div>
<!-- </div> -->
<div class="clear"><!--  --></div>
<?php endif; ?>

<div class="contentpane <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<!-- contentdescription -->
	<div class="contentdescription <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php if ($this->params->get('show_description') && $this->section->description) : ?>
			<?php echo $this->section->description; ?>
		<?php endif; ?>
	</div>
	
	<!-- categories -->
	<?php if ($this->params->get('show_categories', 1)) : ?>
	<ul class="category_list">
		<?php foreach ($this->categories as $category) : ?>
		<?php if (!$this->params->get('show_empty_categories') && !$category->numitems) continue; ?>
		<li <?php if ($category->ordering == 1):?>class="first"<?php endif;?>>
			<dl>
				<dt class="<?php echo $category->image_position; ?>">
					<?php if($category->image):?>
					<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'.  $category->image;?>" align="<?php echo $this->category->image_position;?>" hspace="6" alt="<?php echo $category->image;?>" />
					<?php endif; ?>
				</dt>
				<dd>			
					<a href="<?php echo $category->link; ?>" class="category">
						<h3><?php echo $category->title;?></h3>
					</a>
					
					<?php if ($this->params->get('show_cat_num_articles')) : ?>
						<span class="small">
							( <?php if ($category->numitems==1) {
							echo $category->numitems ." ". JText::_( 'item' );}
							else {
							echo $category->numitems ." ". JText::_( 'items' );} ?> )
						</span>
					<?php endif; ?>
					
					<?php if ($this->params->def('show_category_description', 1) && $category->description) : ?>
						<?php echo $category->description; ?>
					<?php endif; ?>
					
					<a class="more" href="<?php echo $category->link; ?>"><?php echo JText::_('More')?></a>
				</dd>
			</dl>
		</li>
		<?php endforeach; ?>
	</ul>
	<div class="clear"><!--  --></div>
	<?php endif; ?>
</div>