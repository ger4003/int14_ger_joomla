<?php
/**
 * List layout for default template
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */

defined('_JEXEC') or die('Restricted access');

global $option, $listitemid;

$baseurl=JUri::root()."administrator/components/$option/";
$document = JFactory::getDocument();
$document->addStyleSheet($baseurl."css/moodalbox.css");

?>
<script src="<?php echo $baseurl."js/moodalbox.js"?>" type="text/javascript"></script>
<script type="text/javascript">

//<!--
_EVAL_SCRIPTS=true;
window.addEvent('domready',function() {
	//for every record...
	$$('a.gb_deletebtn').each(function(el) {
		el.addEvent('click',function(e) {


			var e=new Event(e);
		

		if(!confirm("<?php echo JText::_("ARE_YOU_SURE");?>"))
		{
			e.stop();
			return false;
		}
		else
		{
			var id= el.getProperty('id').replace('a-','');
			var url = this.href;
			
			window.location = url;			
			return true;
		}
			
					
		});
	});


	$$('a.gb_closetn').each(function(el) {
		
		el.addEvent('click',function(e) {

			var e=new Event(e);

		if(!confirm('<?php echo JText::_("ARE_YOU_SURE"); ?>'))
		{
			return false;
		}
		else
		{
			var id= el.getProperty('id').replace('close-','');
			var url = this.href;
			window.location = url;			
			return true;
		}
			
					
		});
	});
	
});

//-->
</script>

<?php 
$this->render('filter');
?>
<div id="roundme" class="gb_round_corner">

<style>
.gb_thumbnail {
float:left;
width:<?php echo $this->params->get ( 'width_thumbnail_sml', 80 ); ?>px;
}
</style>

<div class="gb_form_heading">
<h3><?php echo JText::_('MY_ADS');?></h3>
</div>
<?php
if(count($this->rows)>0)
{
	$k=0;
	for($i=0, $n=count($this->rows);$i<$n;$i++)
	{
		$ordering=true;
		$row=&$this->rows[$i];
		$checked=JHTML::_('grid.id',$i,$row->id);

		$link=JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.edit&cid[]='.$row->id);
		$this->render('item',array("row"=>$row));

	}
}
else
{
	echo JText::_('NO_CLASSFIEDS');
}

echo $this->render('ads.pagination');

?>

</div>
