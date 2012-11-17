<?php
/**
 * ad layout for default template
 *
 * @subpackage Listbingo
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');

$basepath=JUri::root();
global $option;

?>

<div class="heading_wrap">
	<div class="componentheading ">
		<h2>Börse</h2>
		<p class="subheader">Kategorie</p>
	</div>
</div>

<div class="contentheading ">
		<h3>
			<?php echo $this->row->title;?>
			<?php GApplication::triggerEvent('onAfterLoadTitle',array(& $this->row,& $this->params)); ?>
		</h3>
</div>


<!-- detail part starts-->
<div class="gb_item_detail_wrapper <?php echo isset($this->row->classname)?$this->row->classname:''; ?>">

	<?php if($this->expired): ?>
	<!-- expierd -->
	<div class="lb-adexpired">
		<img src="<?php echo JUri::root()."components/$option/templates/default/images/expired.png"; ?>" />
	</div>
	<?php endif; ?>


	<div class="gb_detail_section">
		<div class="gb_images">
			<?php  // add images
			if($this->params->get('enableimages',0)) {
				echo $this->adimages;
			}
			?>

			<?php GApplication::triggerEvent('onAfterLoadDetail',array(& $this->row,& $this->params)); ?>
			<?php GApplication::triggerEvent('onAfterDisplayContent',array(& $this->row,& $this->params)); ?>

			<div class="gb_listing_normal_attributes">
				<ul>
					<!-- extra info -->
					<?php $this->render('extrainfo',array("extrainfo"=>$this->row->extrafields));?>

					<!-- address -->
					<?php $this->render('address',array("address"=>$this->address,"regions"=>$this->regions)); ?>

					<?php if($this->row->hasprice && $this->params->get('enable_field_price',0)): ?>
					<!-- price -->
					<li>
						<dl class="gb_ad_price">
							<dt>
								<?php if($this->row->pricetype < 3): ?>
								<?php echo JText::_('PRICE'); ?>:
								<?php endif;?>
							</dt>
							<dd><?php echo $this->price; ?></dd>
						</dl>
					</li>
					<?php endif; ?>

					<?php if($this->showcontact): ?>
					<!-- contact info -->
					<li>
						<dl class="gb_ad_contact">
							<dt><?php echo JText::_('EMAIL'); ?></dt>
							<dd><?php echo $this->row->aduser->email; ?></dd>
						</dl>
					</li>
					<?php endif; ?>
					<?php GApplication::triggerEvent('onBeforeDisplayTitle',array(& $this->row,& $this->params)); ?>
				</ul>
			</div>
		</div>
		<div class="clear"><!-- --></div>

		<div class="gb_listing_normal_attributes">
			<div class="gb_ad_description">
				<h5 class="gb_ad_description_title"><?php echo JText::_('DESCRIPTION');?></h5>
				<?php echo $this->row->description;?>
			</div>
		</div>

		<?php
		if($this->params->get('enable_field_tags',0))
		{
			echo $this->row->tags;
		}
		?>
		<br class="clear" />


	</div>

</div>
