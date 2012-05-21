<?php // no direct access
defined('_JEXEC') or die('Restricted access');

global $listitemid;

$basepathmodule = JUri::root()."modules/mod_lbcategories/";
$document = JFactory::getDocument();


$document->addStylesheet($basepathmodule."css/default.css");
$option = "com_listbingo";
$indexing = array();

$k=0;
foreach($list as $category) {
	$indexing[(int)$category->id]= $k;
	foreach($category->child as $c)
	{
		$indexing[(int)$c->id]= $k;
	}
	$k++;
}

if(!JRequest::getInt('catid'))
{
	$indexid = 0;
}
else
{
	
	if(!array_key_exists(JRequest::getInt('catid'),$indexing))
	{
		$indexid = 0;
	}
	else
	{
		$indexid = $indexing[JRequest::getInt('catid')];
	}
}

?>


<?php if(count($list)>0): ?>
<ul class="menu">

	<?php 
	foreach($list as $category):
		$adcount = 0;
		foreach($indcount as $i => $val)
		{
			if(in_array($i,$category->adcat))
			{
				$adcount = $adcount+$val;
			}
		}
	?>
		
				
	<li id="lb_item_<?php echo $category->id ?>" class="<?php if(JRequest::getInt('catid') == $category->id):?>active<?php endif;?>">
		<a <?php echo $active;?> href="<?php echo JRoute::_("index.php?option=$option&Itemid=$listitemid&task=ads&catid=".$category->id); ?>">
			<?php echo $category->title; ?>
			<?php if($params->get('enable_count')): ?>
				(<?php echo $adcount; ?>)
			<?php endif;?>
		</a>
	</li>
	
	<?php endforeach; ?>

</ul>
<?php endif; ?>
