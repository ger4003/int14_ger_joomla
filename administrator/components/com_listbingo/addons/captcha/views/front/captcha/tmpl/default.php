<?php
// Disallow direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$captchalink = JRoute::_('index.php?option='.$option.'&task=addons.captcha.front.generateCaptcha&time='.time(),false);
$imgurl = JUri::root()."administrator/components/$option/addons/captcha/images/refresh.png";
$logourl = JUri::root()."administrator/components/$option/addons/captcha/images/logo.png";
$logolink = "http://www.gobingoo.com";
gbaddons("captcha.css.layout");
if($this->params->get('enable_ajaxcaptcha',0))
{
	gbaddons("captcha.js.ajaxcaptcha");
}
?>

<script language="javascript" type="text/javascript">
//<!--

function reloadCaptcha()
{

	src=document.getElementById('captcha').src;
	if(src.indexOf('?')>0)
	{
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '&amp;' +new Date();
	}
	else
	{
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
	}
	
} 
//-->
</script>


<div><label id="security_numbermsg" for="security_number"><?php echo JText::_('SECURITY_CODE');?></label>
<div class="gb_captcha_round_wrapper">

<div class="gb_captcha_three_wrapper">
<div class="gb_captcha_image_wrapper">
<img id="captcha" title="Click to reload image" alt="Click to reload image"	src="<?php echo $captchalink; ?>" />
<div id="captchaimageloading"></div>
</div>

<div class="gb_captcha_verification">
<input type="text" value="" size="10" name="security_number" id="security_number" class="inputbox-security required validate-captchaverify" />
<?php 
if($this->msg!="")
{
	echo "<br />";
	echo $this->msg; 
}
?>

<div id="captcha_processing"></div>
</div>

<div class="gb_captcha_refresh">
<img id="refreshCaptchaBtn" onclick="javascript:reloadCaptcha()" alt="Click to reload image" src="<?php echo $imgurl; ?>" />
</div>
</div>
<div class="gb_captcha_poweredby">
<?php echo JText::_('POWERED_BY'); ?>
<br /><a href="<?php echo $logolink; ?>"><img src="<?php echo $logourl;?>" /></a>
</div>

<br class="clear" />

</div>
<span class="gb_required_field">&nbsp;*&nbsp;</span>
</div>
<br class="clear" />