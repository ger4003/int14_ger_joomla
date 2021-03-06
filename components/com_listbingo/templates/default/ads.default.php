<?php
/**
 * List layout for default template
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */

defined('_JEXEC') or die('Restricted access');

ListbingoHelper::bakeCountryBreadcrumb();
ListbingoHelper::bakeRegionBreadcrumb();
ListbingoHelper::bakeCategoryBreadcrumb();

global $option, $listitemid;
$level=$this->params->get('category_depth',5);

$postlink = JRoute::_("index.php?option=$option&task=new&Itemid=$listitemid&catid=".JRequest::getInt('catid',0));

if(JRequest::getInt('catid',0))
{
	if(count($this->categories)>0&&$this->categories)
	{
		$this->render('categories.default',array('categories'=>$this->categories,'level'=>$level));
		?>
<br class="clear" />
		<?php
	}
	else
	{
		$this->render('regions.navigation');
	}

}
else
{
	$this->render('regions.navigation');
	
}

if(count($this->rows)>0)
{
	$this->render('filter');
}

?>
<div id="roundme" class="gb_round_corner">
<div class="gb_form_heading">

<div class="gb_views_post"><a class="gbdifferentviews"
	href="<?php echo $postlink;?>"  rel="nofollow"><span><?php echo JText::_("POST");?></span></a>
<div class="clear"></div>
</div>

<h3><?php echo JText::_('LISTINGS');?></h3>
</div>
<?php

if($this->params->get('default_listing_layout'))
{
	
	if(count($this->rows)>0)
	{
		
		?>

<div class="gbSearchWrapper">

<style>
.gb_thumbnail {
float:left;
width:<?php echo $this->params->get ( 'width_thumbnail_sml', 80 ); ?>px;
}
</style>
		
<?php


foreach($this->rows as $row)
{
	$ordering=true;
	$this->render('ads.item',array("row"=>$row));
}


?></div>
<?php
	}
	else
	{
		echo $this->params->get('search_no_results',JText::_('NO_CLASSFIEDS'));
	}
	?> <?php
}
else
{
	?>

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
	<?php
}
?>

<?php 
if($this->pagination->total>$this->pagination->limit)
{
	//echo "<br class=\"clear\" />";
	$this->render('pagination');
}
?>
</div>

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

