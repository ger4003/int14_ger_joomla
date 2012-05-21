<?php
defined('JPATH_BASE') or die();
global $listitemid;
JHTML::_('behavior.formvalidation');
$adid = JRequest::getInt('adid',0);
$baseurl=JUri::root()."administrator/components/$option/";

$flagwidth = $this->params->get('flag_popup_width',500);
$flagheight = $this->params->get('flag_popup_height',350);

?>
<script src="<?php echo $baseurl."js/moodalbox.js"?>"></script>
<script type="text/javascript">
//<!--
_EVAL_SCRIPTS=true;

//-->
</script>
<link href="<?php echo $baseurl."css/moodalbox.css"?>" type="text/css" rel="stylesheet"/>
<?php
gbaddons("flag.css.layout");

$flagrel="";
if($this->params->get('enable_flag_moodalbox'))
{
	$link = JRoute::_('index.php?option='.$option.'&task=addons.flag.front.showFlagForm&adid='.$adid.'&format=raw');
	$flagrel = "rel = \"moodalbox $flagwidth $flagheight\"";	
}
else
{
	$link = JRoute::_('index.php?option='.$option.'&task=addons.flag.front.showFlagForm&adid='.$adid.'&Itemid='.$listitemid);
	$flagrel = "";
}


?>

<li>
<img src="<?php echo JUri::root()."administrator/components/$option/addons/flag/images/flag.png"?>" />
<a href="<?php echo $link;?>" <?php echo $flagrel; ?> title="Flag this item"><?php echo JText::_('FLAG_AD');?></a>
</li>