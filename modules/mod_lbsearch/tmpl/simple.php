<?php
defined('_JEXEC') or die('Restricted access');
$basepathmodule=JUri::root()."modules/mod_lbsearch/";
$document = JFactory::getDocument();
$document->addStyleSheet($basepathmodule."css/default.css");

$searchtxt = JFilterOutput::cleanText(JRequest::getString('q',''));
global $option, $listitemid;

if($searchtxt=="")
{
	$searchtxt = JText::_('SEARCH_EXAMPLE');
}

JFilterOutput::cleanText ($searchtxt);

?>
<script language="javascript" type="text/javascript">
<!--

var searchexampletxt = '<?php echo JText::_('SEARCH_EXAMPLE'); ?>';

Window.onDomReady(function(){
	$('modq').addEvent('focus',function(){
		if(this.value=='<?php echo JText::_('SEARCH_EXAMPLE');?>')
		{
this.value="";
			}
		});
	
	$('modq').addEvent('blur',function(){
		if(this.value=='')
		{
this.value="<?php echo JText::_('SEARCH_EXAMPLE');?>";
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

-->
</script>

<div id="gb_simpleSearch_wrapper">	
	
	<div class="gb_search_simple">		
		<div class="pngFix clear-block" id="gb-search-form">		
			<form  onSubmit="modcheckForm()" name="modfrmGBSearch" id="modfrmGBSearch" method="get" action="<?php echo JRoute::_("index.php?option=com_listbingo&task=ads.search&Itemid=$listitemid");?>">
			<input type="hidden" name="option" value="com_listbingo">
			<input type="hidden" name="task" value="ads.search" />
			<input type="hidden" name="Itemid" value="<?php echo $listitemid;?>" />
		
			
			<div id="gb_search_keyword">
				<input type="text" value="<?php echo $searchtxt; ?>" name="q" id="modq" />
			</div>
				
			<div id="gb_search_submit">
				<div class="gb_submit_round_corner">
				<input type="submit" name="search" id="btnSubmit" value="Search"/>
				</div>
			</div>
        <br />
			</form>
		</div>
		
		
		<div id="gb_example">
		<?php 
		
		if($enable_searchtext)
		{
		?>
		<p><?php echo $searchtext;?></p>
		<?php 
		}
		?>
		</div>		
	</div>
</div>
<br class="clear" />