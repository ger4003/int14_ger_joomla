<?php // no direct access
defined('_JEXEC') or die('Restricted access');

global $listitemid;

$baseurl = JUri::root();
$basepathmodule = $baseurl."modules/mod_lbcategories/";
$document = JFactory::getDocument();

$document->addStylesheet($basepathmodule."css/default.css");

$midthumb = "thm";
$smlthumb = "sthm";
$lrgthumb = "lthm";

$categories = modLbSummaryHelper::getCategories($params);

?>

<div class="scrollable-wrap">

	<div class="scrollable-tabnavi">
		<ul class="navi-items">
			<?php foreach($categories as $key => $category):?>
			<li class="navi-item <?php if($key==0):?>first active<?php endif;?>">
				<?php echo $category->title; ?>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
	<div class="clear"><!--  --></div>

	<div class="scrollable scrollable-tabs">
		<div class="browse">
			<a href="<?php echo JRoute::_("index.php?option=com_listbingo&Itemid=$listitemid&task=ads"); ?>">alle anzeigen</a>
		</div>
		<div class="items">
			<?php foreach($categories as $category):?>
			<ul class="item_list">
				<?php foreach(modLbSummaryHelper::getAds($category->id) as $ad):?>
				<li>
					<dl>
						<dt>
							<?php
							//FIXME: Link has to be build dynamicly
							$link = "boerse/ads/view/" . $ad->id . "-" . $ad->alias ;
							$images = modLbSummaryHelper::getAdImages($ad->id);
							if($images):?>
							<a href="<?php echo $link; ?>">
								<img src="<?php echo $baseurl.$images[0]->image.$smlthumb.".".$images[0]->extension;?>" class="thumbnail" />
							</a>
							<?php endif;?>
						</dt>
						<dd>
							<?php
							$baujahr = modLbSummaryHelper::getAdExtraField('Baujahr', $ad->id);
							$design = modLbSummaryHelper::getAdExtraField('Design', $ad->id);
							?>

							<a href="<?php echo $link; ?>"><strong><?php echo $ad->title; ?></strong></a><br />

							<?php if(!empty($design->title)):?>
							<span class="label"><?php echo $design->title;?>:</span> <?php echo $design->field_value;?><br />
							<?php endif;?>

							<?php if(!empty($baujahr->title)):?>
							<!-- <span class="label"><?php echo $baujahr->title;?>:</span> <?php echo $baujahr->field_value;?><br /> -->
							<?php endif;?>

							<span class="label">Preis:</span>
							<?php if($ad->pricetype == 1): // priceable ?>
							<?php echo number_format($ad->price,0); ?> <?php echo $ad->currencycode; ?>
							<?php elseif($ad->pricetype == 2): // free ?>
							frei
							<?php elseif($ad->pricetype == 3): // verhandelbar ?>
							VHB
							<?php endif;?>
						</dd>
					</dl>

				</li>
				<?php endforeach;?>
			</ul>
			<?php endforeach;?>
		</div>
	</div>


</div><!-- /scrollable-wrap -->

