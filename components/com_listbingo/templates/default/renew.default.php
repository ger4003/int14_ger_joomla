<?php
/**
 * @package gobingoo
 * @subpackage listbingo
 * post new ad subtemplate
 * @author Bruce@gobingoo.com
 * code Bruce
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

global $mainframe,$option, $listitemid;
?>
<div class="gb_form_heading">
<h3><?php echo JText::_('RENEW_YOUR_AD');?></h3>
</div>
<div id="gbjosFormHolder">
<form action="<?php	echo ListbingoHelper::lbroute ( 'index.php?option=' . $option . '&task=ads.renew&Itemid=' . $listitemid ); ?>" method="post" id="adSubmitForm" name="adSubmitForm" class="form-validate"
	enctype="multipart/form-data">
	<input type="hidden" name="adid" id="adid"	value="<?php echo $this->adid?>" /> 

	<div><label id="tagsmsg" for="tags"><?php
	echo JText::_ ( 'EXPIRY_DATE' );
	?></label>
	<?php
	echo JHTML::_ ( 'calendar', (string)date('Y-m-d H:i:s'), "expiry_date", 'expiry_date', '%Y-%m-%d', array ('class' => 'inputtextbox ', 'maxlength' => '19' ) );
	?>
	</div>
<br />

<div>
<button id="adSubmitBtn" class="gbButton validate" type="submit"><?php echo JText::_ ( 'SAVE' ); ?></button>
</div>
<input type="hidden" name="Itemid" value="<?php echo $listitemid;?>" />
<input type="hidden" name="option" value="<?php echo $option?>" /> 
<input type="hidden" name="task" value="ads.renew" /> 
<?php echo JHTML::_ ( 'form.token' ); ?>
</form>
</div>
