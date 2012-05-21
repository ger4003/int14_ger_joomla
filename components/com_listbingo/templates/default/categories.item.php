<?php
defined('_JEXEC') or die('Restricted access');

global $option, $listitemid;

$variablename='category'.$this->level;

if(($this->level>0))
{
	
	if(count($this->$variablename)>0)
	{
		?>	
		<ul class="gbInnerHorizontalList"><?php 
		foreach($this->$variablename as $cat)
		{
			$link=JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.select&catid='.$cat->slug.'&time='.time());
			?>
			<li>
			<a href="<?php echo $link; ?>">
			<span><?php echo $cat->title; ?>
			<?php 
			if($this->params->get('enable_adcount'))
			{
				echo "(".$cat->adcount.")";
			}
			?>
			</span>		
			</a>
			<?php 
			$category="category".($this->level-1);
			if(isset($cat->child)&&count($cat->child)>0)
			{
				echo $this->render('categories.item',array($category=>$cat->child,'level'=>$this->level-1));
			}
			?>
			</li>
			<?php			
		}
		?>
		</ul>
		<?php
	}
}

