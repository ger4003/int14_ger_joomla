<?php
/**
 * Advance Search layout for default template
 *
 * @package Gobingoo
 * @subpackage listbingo
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');
global $option, $listitemid;

?>
<script language="javascript" type="text/javascript">
//<!--
var searchexampletxt = "<?php echo JText::_('SEARCH_EXAMPLE');?>";

Window.onDomReady(function(){

	$('countries').addEvent('change',function(){
		
		$('search-locality').setHTML('Loading...');
		url='index.php?option=com_listbingo&format=raw&task=regions.loadForSearch&cid='+this.value;
		req=new Ajax(url,			
				{
					update:'search-locality',
					method:'get',
						evalscript:true
					}
				);
				
				req.request();
		
	});
	
	<?php 
	if($this->country>0)
	{
	?>
	$('search-locality').setHTML('Loading...');
	url='index.php?option=com_listbingo&format=raw&task=regions.loadForSearch&cid='+<?php echo $this->country?>;
	req=new Ajax(url,			
			{
				update:'search-locality',
				method:'get',
					evalscript:true
				}
			);
			
			req.request();
		
	<?php 
	}
	?>		
		
});

//-->
</script>

<?php
$this->addJSI('search');

$searchtxt = JFilterOutput::cleanText(JRequest::getString('q',''));

if($searchtxt=="")
{
	$searchtxt = JText::_('SEARCH_EXAMPLE');
}
JFilterOutput::cleanText ($searchtxt);

$min = JRequest::getVar('search_from_price');
if($min=="")
{
	$min = "min";
}

$max = JRequest::getVar('search_to_price');
if($max=="")
{
	$max = "max";
}
?>


<div id="gb_advSearchWrapper">


<h3><?php echo JText::_('SEARCH_SLOGAN');?></h3>
<div class="gb_advSearchOuterWrapper">
<form onSubmit="checkForm()" name="frmGBSearch" id="frmGBSearch" method="get" action="<?php echo JRoute::_("index.php?option=$option&task=ads.search&Itemid=$listitemid");?>">

<div class="mainSearch"><strong><?php echo JText::_("FIND");?></strong><br />
<input type="text" name="q" id="q" value="<?php echo $searchtxt;?>"/>
<br />
<?php echo JText::_('LOCATION_EXAMPLE');?>

</div>



<div class="prop_stage"><strong><?php echo JText::_("CATEGORIES");?></strong><br />
<?php echo $this->lists['categories'];?>
</div>

<div class="clear"></div>


<div class="search_from_country">
<strong><?php echo JText::_("COUNTRY");?></strong><br />
<?php echo $this->lists['countries']; ?>
</div>

<div class="search_from_region" id="search-locality">
<strong><?php echo JText::_("REGION");?></strong><br />
<?php echo JText::_('SELECT_COUNTRY_TO_LOAD_REGIONS');?>
</div>
<div class="clear"></div>

<div class="price_stage_adv"><strong><?php echo JText::_("PRICE_RANGE");?></strong><br />

<input type="text" id="search_from_price" name="search_from_price" size="10" value="<?php echo $min;?>" /> 

<input type="text" id="search_to_price" name="search_to_price" size="10" value="<?php echo $max;?>" />

</div>

<div class="searchBtnHolder">
<div class="gb_submit_round_corner">
<input type="submit" name="search" id="btnSubmit" value="<?php echo JText::_('SEARCH');?>" />
<input type="hidden" name="task" value="ads.search" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="Itemid" value="<?php echo $listitemid;?>" />
</div>
</div>
<div class="clear"></div>
                      
</form>
</div>
</div>