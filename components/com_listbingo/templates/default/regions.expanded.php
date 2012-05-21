<?php
defined('_JEXEC') or die('Restricted access');

global $option, $listitemid;

$variablename='subregions'.$this->level;

if(($this->level>0))
{
	
	if(count($this->$variablename)>0)
	{

		foreach($this->$variablename as $sub)
		{	
			if(isset($sub->child)&&count($sub->child)>0)
			{
				$link=JRoute::_("index.php?option=$option&Itemid=$listitemid&task=regions&cid=$sub->country_id&rid=$sub->slug&time=".time());
			}
			else
			{
				$link=JRoute::_("index.php?option=$option&Itemid=$listitemid&task=regions.region&rid=$sub->slug&time=".time());
			}
			?>
			<ul class="gbInnerHorizontalList">
			<li>
			<a href="<?php echo $link; ?>">
			<span><?php echo $sub->title; ?></span>		
			</a>
			<?php 
			$region="subregions".($this->level-1);
			if(isset($sub->child)&&count($sub->child)>0)
			{
				echo $this->render('regions.expanded',array($region=>$r->child,'level'=>$this->level-1));
			}
			?>
			</li>
			</ul>
			<?php			
		}
		
	}
}

