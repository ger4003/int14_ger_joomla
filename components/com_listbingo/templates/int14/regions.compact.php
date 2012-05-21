<?php
/**
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');

global $option, $listitemid;

if($this->params->get('expand_directory'))
{
	$level=2;	
}
else
{
	$level = 1;
}
$this->render('navigation',array("params"=>$this->params));

?>

<div id="roundme" class="gb_round_corner">
<div class="gb_form_heading">
<h3><?php echo JText::_('SELECT_REGIONS');?></h3>
</div>


<?php
$n=count($this->regions);
if($n>0)
{

	?>
	<ul class="gbHorizontalList">
	<?php
	foreach($this->regions as $r)
	{
		if($r)
		{
			
			?>		
			<li><a href="<?php echo $r->link;?>" class="gblink"><span><?php echo JText::_($r->title);?></span></a>
			<?php
			$region="subregions".($level-1);
			if(isset($r->child)&&count($r->child)>0)
			{
				echo $this->render('regions.expanded',array($region=>$r->child,'level'=>$level-1));
			}
			
			?>
			</li>
			<?php
		}
	
	}
	?>	
	</ul>

<?php
}
else
{
	echo JText::_("NO_REGIONS");

}
?>
</div>