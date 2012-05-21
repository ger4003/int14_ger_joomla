<?php 

global $listitemid;

$basepathmodule=JUri::root()."modules/mod_lbsearch/";
$document = JFactory::getDocument();
$document->addStyleSheet($basepathmodule."css/default.css");

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
	if(JRequest::getInt('countries',0)>0)
	{
	?>
	$('search-locality').setHTML('Loading...');
	url='index.php?option=com_listbingo&format=raw&task=regions.loadForSearch&cid='+<?php echo JRequest::getInt('countries',0);?>;
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
	
	$('modq').addEvent('focus',function(){
		if(this.value==searchexampletxt)
		{
this.value="";
			}
		});
	
	$('modq').addEvent('blur',function(){
		if(this.value=='')
		{
this.value=searchexampletxt;
			}
		});
       

    $('modsearch_from_price').addEvent('focus',function(){
		if(this.value=='min')
		{
this.value="";
			}
		});

	$('modsearch_from_price').addEvent('blur',function(){
		if(this.value=='')
		{
this.value="min";
			}
		});
	
	$('modsearch_to_price').addEvent('focus',function(){
		if(this.value=='max')
		{
this.value="";
			}
		});

	$('modsearch_to_price').addEvent('blur',function(){
		if(this.value=='')
		{
this.value="max";
			}
		});

    
});


function modcheckForm()
{
	var searchtxt = document.modfrmGBSearch.q.value;
	if(searchtxt == searchexampletxt)
	{
		document.modfrmGBSearch.q.value = "";
	}
	
}

//-->
</script>

<?php

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

<div class="gb_advSearchOuterWrapper">
<form onSubmit="modcheckForm()" name="modfrmGBSearch" id="modfrmGBSearch" method="get" action="<?php echo JRoute::_("index.php?option=$option&task=ads.search&Itemid=$listitemid");?>">

<div class="mainSearch"><strong><?php echo JText::_("FIND");?></strong><br />
<input type="text" name="q" id="modq" value="<?php echo $searchtxt;?>" />

<?php 
if($enable_searchtext)
{
?>
<p><?php echo $searchtext;?></p>
<?php 
}
?>
</div>

<div class="prop_stage"><strong><?php echo JText::_("CATEGORIES");?></strong><br />
<?php echo $lists;?>
</div>

<div class="clear"></div>



<div class="search_from_country">
<strong><?php echo JText::_("COUNTRY");?></strong><br />
<?php echo $countries; ?>
</div>

<div class="search_from_region" id="search-locality">
<strong><?php echo JText::_("REGION");?></strong><br />
<?php echo JText::_('SELECT_COUNTRY_TO_LOAD_REGIONS');?>
</div>
<div class="clear"></div>

<div class="price_stage_adv"><strong><?php echo JText::_("PRICE_RANGE");?></strong><br />

<input type="text" id="modsearch_from_price" name="search_from_price" size="10" value="<?php echo $min;?>" /> 

<input type="text" id="modsearch_to_price" name="search_to_price" size="10" value="<?php echo $max;?>" />
</div>

<div class="searchBtnHolder_adv">
<div class="gb_submit_round_corner">
&nbsp;<br />
<input type="submit" name="search" id="btnSubmit" value="Search" />
<input type="hidden" name="task" value="ads.search" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="Itemid" value="<?php echo $listitemid;?>" />
</div>
</div>
<div class="clear"></div>
                      
</form>
</div>
</div>