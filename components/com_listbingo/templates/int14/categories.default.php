<?php
/**
 * List layout for default template
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );


$level=$this->params->get('category_depth',5);
global $option, $listitemid;

$basepath=JUri::root();
?>
<div class="gb_category_listing_wrapper">

<div id="roundme" class="gb_round_corner">

<div class="gb_form_heading">
	<h3><?php echo JText::_ ( 'BROWSE_CATEGORY' ); ?></h3>
</div>

<?php $this->render ( 'regions.navigation' ); ?>

<?php
$n=count($this->categories);
if($n>0)
{
	 
	foreach($this->categories as $cat)
	{
		if($cat)
		{
		
			?>
			<div class="gb_category_listing <?php echo JRequest::getInt('catid',0)?"gb_category_listing_inner":""; ?>">
			<?php
	
			if(!empty($this->wherewasi))
			{
				if(!$this->params->get('enable_root_cat_post'))
				{
					if($cat->parent_id)
					{
						$link=JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.select&catid='.$cat->slug.'&time='.time(),false);
					}
					else
					{
						$link = "#";
					}
				}
				else
				{					
					$link=JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.select&catid='.$cat->slug.'&time='.time());
				}
			}
			else
			{
				$link=JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.select&catid='.$cat->slug.'&time='.time());
			}
			
			
			$style = "";
			if($cat->logo && $this->params->get('category_enable_logo')) {
				$style = "background: url(".$basepath.$cat->logo.") no-repeat left top; padding:7px 0px 7px 30px";
			} ?>
			
			<a href="<?php echo $link; ?>" style="<?php echo $style; ?>">
				<?php echo $cat->title; ?> 
				<?php if($this->params->get('enable_adcount')): ?>
				(<?php echo $cat->adcount ?>)
				<?php endif; ?>
			</a>
				
			<?php
			$category="category".($level-1);
			if(isset($cat->child)&&count($cat->child)>0)
			{
				echo $this->render('categories.item',array($category=>$cat->child,'level'=>$level-1));
			}
			?>
		</div>
		<?php 
		}
	}
}

?>
<br class="clear" />
</div>
</div>

