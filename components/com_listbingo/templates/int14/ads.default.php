<?php
/**
 * List layout for default template
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */

defined('_JEXEC') or die('Restricted access');

$countryBreadcrumb 			= ListbingoHelper::bakeCountryBreadcrumb();
$regionBreadcrumb 			= ListbingoHelper::bakeRegionBreadcrumb();
$categoryBreadcrumb 		= ListbingoHelper::bakeCategoryBreadcrumb();
$firstBreadcrumbElement = $categoryBreadcrumb->_pathway[0]->name;
$lastBreadcrumbElement 	= array_pop($categoryBreadcrumb->_pathway)->name;


global $option, $listitemid;
$level=$this->params->get('category_depth',5);

$postlink = JRoute::_("index.php?option=$option&task=new&Itemid=$listitemid&catid=".JRequest::getInt('catid',0));

$isFrontpage = ($mainframe->getPathway()->_count <= 1) ? true : false;

?>


<?php if($isFrontpage): ?>
<!-- 
********************
* Frontpage Layout * 
********************
-->
<div class="description_wrap">
	<div class="contentheading <?php echo $this->params->get('pageclass_sfx');?>">
		<h2><?php echo $firstBreadcrumbElement; ?></h2>
	</div>
	<div class="clear"><!--  --></div>
		
	<!-- contentdescription -->
	<div class="contentdescription <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		Beschreibung der Sections Börse
	</div>
	<div class="clear"><!--  --></div>
</div>

<?php $this->render('regions.navigation'); ?>
<div class="clear"><!--  --></div>

<?php else: ?>
<!-- 
********************
* Standard Layout * 
********************
-->
<div class="heading_wrap">
	<div class="componentheading <?php echo $this->params->get('pageclass_sfx');?>">
		<h2><?php echo $firstBreadcrumbElement; ?></h2>
	</div>
</div>
<div class="clear"><!--  --></div>

<div class="contentheading <?php echo $this->params->get('pageclass_sfx');?>">
	<h3><?php echo $lastBreadcrumbElement; ?></h3>
</div>
<div class="clear"><!--  --></div>
<?php endif;?>



<?php if($this->params->get('default_listing_layout')): ?>
	
<?php if(count($this->rows)>0): ?>
<div class="gbSearchWrapper">
	<ul class="category_list">
		<?php
		// get first element
		$first = array_pop(array_reverse($this->rows, true));
		foreach($this->rows as $key => $row)
		{
			$isFirst = false;
			if($first->id == $key) {
				$isFirst = true;
			}
			
			$ordering=true;
			$this->render('ads.item',array("row"=>$row, "isFirst" => $isFirst));
		}
		?>
	</ul>
</div>
<?php else: ?>
	<?php echo $this->params->get('search_no_results',JText::_('NO_CLASSFIEDS')); ?>
<?php endif;?> 

<?php else: ?>

<table class="gb_simple_list" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<?php 
		if($this->params->get('enableimages',0))
		{
		?>
		<th width="5%"><?php echo JText::_('IMAGE');?></th>
		<?php 
		}
		?>
		
		<th><?php echo JText::_('TITLE');?></th>
		<?php 
		if($this->params->get('enable_field_price',0))
		{
		?>
		<th><?php echo JText::_('PRICE');?></th>
		<?php 
		}
		?>
		<th><?php echo JText::_('POSTED_ON');?></th>
		<?php
		if($this->params->get('auto_expire_listings'))
		{
			?>
		<th><?php echo JText::_('EXPIRES_ON');?></th>
		<?php
		}
		?>
		
	</tr>
	<?php
	if(count($this->rows)>0)
	{
		$j=0;
		$i=0;
		foreach($this->rows as $row)
		{			
			$ordering=true;
			$checked=JHTML::_('grid.id',$i,$row->id);
			$this->render('simpleitem',array("row"=>$row,"j"=>$j));
			$j=1-$j;
			$i++;
		}
	}
	else
	{
		echo "<tr>";
		echo "<td colspan=\"4\">";
		echo $this->params->get('search_no_results',JText::_('NO_CLASSFIEDS'));
		echo "</td>";
		echo "</tr>";

	}
	?>
</table>
	<?php endif; ?>

<?php 
if($this->pagination->total>$this->pagination->limit)
{
	//echo "<br class=\"clear\" />";
	$this->render('pagination');
}
?>

<br class="clear" />

<?php

if(JRequest::getInt('catid',0))
{
	if($this->params->get('enable_related_categories'))
	{
		if(count($this->relatedcat)>0)
		{
			$this->render('categories.related',array('categories'=>$this->relatedcat));
		}
	}
}
?>

