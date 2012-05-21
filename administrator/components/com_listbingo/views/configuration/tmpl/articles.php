<?php 
require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_content".DS."elements".DS."article.php");

$element=new JElementArticle(); 

$param=null;


$terms=array();
$terms[]=JHTML::_('select.option', '0', JText::_('No'), 'id', 'title');
$terms[]=JHTML::_('select.option', '1', JText::_('Yes'), 'id', 'title');
$termsoption=JHTML::_('select.radiolist',  $terms, 'config[enable_terms_conditions]', array('class'=>"inputbox",'onclick'=>'return isTermsEnabled(this.value)'), 'id', 'title',$this->config->get('enable_terms_conditions') );
?>

<script type="text/javascript">
<!--
_EVAL_SCRIPTS=true

	
	function isTermsEnabled(terms)
	{
		if(terms==0)
		{
			document.getElementById('terms_conditions_tr').style.display = 'none';
		}
		else
		{
			document.getElementById('terms_conditions_tr').style.display = '';
		}
	}

-->
</script>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'TERMS_N_CONDITIONS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
		
			<tr>
				<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'ENABLE_TERMS_N_CONDITIONS' ); ?>::<?php echo JText::_('ENABLE_TERMS_N_CONDITIONS'); ?>">
				<?php echo JText::_( 'ENABLE_TERMS_N_CONDITIONS' ); ?> </span></td>
				<td valign="top"><?php echo $termsoption; ?></td>
			</tr>
			
			<tr id="terms_conditions_tr" style="<?php echo $this->config->get('enable_terms_conditions',0)?"":"display:none"?>">
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'ARTICLE_ID_TERMS_N_CONDITIONS' ); ?>::<?php echo JText::_('ARTICLE_ID_TERMS_N_CONDITIONS'); ?>">
						<?php echo JText::_( 'ARTICLE_ID_TERMS_N_CONDITIONS' ); ?>
					</span>
				</td>
				<td valign="top"><?php 
				
				//echo $element->fetchElement("terms_conditions_id", $this->config->get('terms_conditions_id'), $param, "config");
				echo JElementLbArticle::fetchElement("terms_conditions_id", $this->config->get('terms_conditions_id'), $param, "config");
				?>
			
				</td>
			</tr>
			
				
			
			
		</tbody>
	</table>
</fieldset>