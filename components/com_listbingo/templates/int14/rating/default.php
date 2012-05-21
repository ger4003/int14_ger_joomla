<?php
// Disallow direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );
gbaddons("rating.css.layout");
global $option, $listitemid;
$star_url= JRoute::_('index.php?option='.$option.'&task=addons.rating.front.saveItemRate&format=raw',false);
?>
Hey yaaa!!
<script type="text/javascript" language="javascript">
//<!--

var vote_txt = "<?php echo JText::_('VOTE');?>";
var votes_txt = "<?php echo JText::_('VOTES');?>";
var url = "<?php echo $star_url; ?>";
//-->
</script><?php
gbaddons("rating.js.ratingStar");

$com = explode("_",$option);

$star_class= $this->rating?'hover inactive_stars':'';
//$half_star_class= $this->rating?'hover inactive_half_stars':'';
if($this->rating){
	$points =$this->avg;
	$count = $this->count;
	$int=(int)$points;
	$float=(float)$points;
	$decimal=$float-$int;
}else{
	$points = 0;
	$count = 0;
	$int=0;
	$float=0;
	$decimal=0;
}
?><div class="gbratingblock"><?php
$weights = $weight = explode("|",$this->config->get('rating_weight'));
$total_weight= array_pop($weights);

for($cnt=0;$cnt<$this->config->get('stars_count');$cnt++)
{
	$half_star= '';
	$itemid='';
	if($cnt<$int)
	{
		$half_star= '';
		$itemid='';
	}
	else
	{
		if (($decimal>=0.5 && $decimal<1)  && $cnt==$int)
		{
			$half_star= ' inactive_half_stars';
			$itemid= ' itemid="1"';
		}
	}
	$star=$cnt+1;
	?><a class="active_stars star_rating <?php echo $this->disable_class;?> <?php  if($cnt < $int){ echo  $star_class;}?> <?php echo $half_star;?>" title="<?php echo $star.'/'.$this->config->get('stars_count');?>" id="gbrate_<?php echo $star;?>" rel="star_<?php echo (int)$this->rows->id;?>_<?php echo $star?>_<?php echo $com[1];?>" <?php echo $itemid;?> /><?php echo $star; ?></a><?php } ?><br class="clearall" /><div id="rate_<?php echo (int)$this->rows->id?>"><?php echo JText::_('RATING')?>:<span class="gbrate_text" style=""><?php echo $points;?></span>/<?php echo $this->config->get('stars_count');?></div><div><span id="vote_<?php echo(int)$this->rows->id;?>"><?php echo $count;?></span><span id="votetext_<?php echo(int)$this->rows->id;?>"><?php if($count>1){echo " ".JText::_('VOTES');}else{echo " ".JText::_('VOTE');}?></span></div><span id="loading_<?php echo (int)$this->rows->id;?>" class="<?php echo $this->config->get('rating_waiting')?>" style="display:none;"><?php echo $this->config->get('rating_waiting_message')?></span><input type="hidden" name="points" id="points"></div>