<?php
/**
 * @package gobingoo
 * @subpackage listbingo
 * post new ad subtemplate
 * @author Bruce@gobingoo.com
 * code Bruce
 */

//var_dump($this->row);

defined('_JEXEC') or die('Restricted access');

global $mainframe, $option, $maxlimit,$listitemid;
$editor=&JFactory::getEditor();

$adminbaseurl=JUri::root()."administrator/components/$option/";

$document = JFactory::getDocument();
$this->addCSS('moodalbox');

$this->addCSS('mootabs');
$this->addJSI('mootabs');

JHTML::_('behavior.calendar');

$managelink = JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=ads.images&adid='.$this->row->id.'&format=raw');
?>
<script	src="<?php echo $adminbaseurl."js/moodalbox.js"?>"></script>

<?php 

if($this->max_image_upload_limit>count($this->row->images)&&$this->params->get('enableimages'))
{
	$this->addJSI("adinput");
}


$suffix=$this->suffix;

?>
<script language="javascript" type="text/javascript">
//<!--
_EVAL_SCRIPTS=true;
var priceselectable = parseInt(<?php echo $this->params->get ('currency_selectable', 0); ?>);
var imgcount = parseInt(<?php echo count($this->row->images); ?>)+1;
var maxlimit = parseInt(<?php echo $this->max_image_upload_limit; ?>);
var alertmsg = '<?php echo JText::_('IMAGE_UPLOAD_EXCEED');?>';
var images = '<?php echo JText::_('IMAGES'); ?>';

var pricetypecat = new Array;

<?php
if($this->params->get('enable_field_price',0))
{
	if(count($this->pricetypecategories)>0)
	{
	
		foreach($this->pricetypecategories as $pt)
		{
			echo "pricetypecat[".$pt->id."] = $pt->hasprice;\n\t\t";
		}
	}
}

if($this->edit)
{
	?>
	window.addEvent('domready',function(){
		
		$('ef').setHTML('Loading...');
		url='<?php echo JRoute::_("index.php?option=$option&format=raw&task=categories.loadef&Itemid=0&cid=".$this->row->category_id."&adid=".$this->row->id,false)?>';
		
		req=new Ajax(url,{
			
			method:'get',
			evalScripts:true,
			onComplete: function( response ) {			           			
		            $('ef').setHTML( response );

		      
		    }

		});				
		req.request();

		<?php
		if($this->params->get('enable_field_price',0))
		{
		?>
			if(pricetypecat[<?php echo $this->row->category_id;?>]>0)
			{			
				
				$('pricetype-container').setStyle('display','block');
				$('price-container').setStyle('display','block');
				$('price').removeClass('required');
				$('currency').removeClass('required');
				
				<?php 
				if(!empty($this->row->pricetype))
				{
					?>
					if(<?php echo $this->row->pricetype;?>>1)
					{
						$('price-container').setStyle('display','none');
						$('price').setProperty('value','');
						$('currency').setProperty('value','');
						
					}
					else
					{
						
						$('price-container').setStyle('display','block');
						$('price').addClass('required');
						if(priceselectable==1)
						{
							$('currency').addClass('required');
						}
						
					}
					<?php 
				}		
				
				?>
			}
			else
			{
	
				$('price').removeClass('required');
				$('currency').removeClass('required');
				$('pricetype-container').setStyle('display','none');
				$('price-container').setStyle('display', 'none');
				$('price').setProperty('value', '');
				//$('currency').setProperty('value', '');
				
				
			}
		<?php		
		}
		?>				
		});	
	<?php 
	
}

?>
var catid = <?php echo JRequest::getInt('catid',0);?>;
var pricetype = <?php echo isset($this->row->pricetype)?$this->row->pricetype:0;?>;

var ad_id=<?php if($this->row->id) echo $this->row->id; else echo 0;?>;
window.addEvent('domready',function(){

	
	$('catid').addEvent('change',function(){

		<?php
				if($this->params->get('enable_field_price',0))
				{
					?>
		
		if(RadioCheck()<2)
		{
		
		if(pricetypecat[this.value]>0)
		{
			$('pricetype-container').setStyle('display','block');
			$('price-container').setStyle('display','block');
			$('price').addClass('required');

			if(priceselectable==1)
			{
				$('currency').addClass('required');
			}
			

		}
		else
		{
			$('price').removeClass('required');
			$('currency').removeClass('required');
			$('pricetype-container').setStyle('display','none');
			$('price-container').setStyle('display','none');
			$('price').setProperty('value','');
			//$('currency').setProperty('value','');

		}
		}
		<?php 
		}
		?>
		
		$('ef').setHTML('Loading...');
		url='<?php echo JRoute::_("index.php?option=$option&format=raw&task=categories.loadef&Itemid=0&adid=".$this->row->id,false)?>'+'&cid='+this.value;
			
		req=new Ajax(url,{
				
					method:'get',
					evalScripts:true,
					onComplete: function( response ) {			           			
				            $('ef').setHTML( response );

				      
				    }

				});				
		req.request();

			
	});
	
});

function RadioCheck() 
{

	var selection = document.adSubmitForm.pricetype;

	for (i=0; i<selection.length; i++)

	  if (selection[i].checked == true)
	  //alert(fruit[i] + ' are ' + selection[i].value + '.')
		return selection[i].value;

}


function checkPriceType(val)
{
	
	if(val>1)
	{
		
		$('price').removeClass('required');
		$('currency').removeClass('required');
		$('price-container').setStyle('display', 'none');
		$('price').setProperty('value', '');
		//$('currency').setProperty('value', '');
		
	}
	else
	{
		$('price').addClass('required');
		if(priceselectable==1)
		{
			$('currency').addClass('required');
		}
		$('price-container').setStyle('display','block');
		
	}
		
}
//-->
</script>


<div id="gbjosFormHolder">
	<form action="<?php echo JRoute::_('index.php?option='.$option.'&task=ads.save&Itemid='.$listitemid); ?>" method="post"	id="adSubmitForm" name="adSubmitForm" class="form-validate stnd" enctype="multipart/form-data">
		<input type="hidden" name="id" id="id" value="<?php echo $this->row->id?>" />		
		<input type="hidden" name="country_id" id="country_id" value="<?php echo $this->country;?>" /> 
		<input type="hidden" name="region_id" id="region_id" value="<?php echo $this->region;?>" />
		
		<?php echo $this->locationtext;?>	
		
		<fieldset class="input">
			<legend>Legend</legend>
			
			<!-- Titel -->
			<dl>
				<dt>
					<label id="titlemsg" for="title"><?php echo JText::_('TITLE');?></label>
					<span class="gb_required_field">&nbsp;*&nbsp;</span>
				</dt>
				<dd>
					<input name="title" type="text" class="inputbox required" id="title" value="<?php echo $this->row->title;?>" />
				</dd>
			</dl>
			
			<!-- Kategorie -->
			<dl class="column_last">
				<dt id="category_id-container">
					<label id="category_idmsg" for="category_id"><?php echo JText::_('CATEGORY');?></label>
					<span class="gb_required_field">&nbsp;*&nbsp;</span>
				</dt>
				<dd class="gb_category_id_holder">
					<?php echo $this->lists['categories'];?>				
				</dd>
			</dl>
			<div class="clear"><!--  --></div>
			
			<!-- Preis -->
			<?php if($this->params->get('enable_field_price',0)): ?>
			<dl id="pricetype-container">
				<dt>
					<label id="pricetypemsg" for="pricetype"><?php echo JText::_('PRICE_TYPE');?></label>
					<span class="gb_required_field">&nbsp;*&nbsp;</span>
				</dt>
				<dd class="gb_pricetype_holder">
					<?php echo $this->lists['pricetype'];?>
				</dd>
			</dl>
			
			<dl id="price-container" class="column_last">
				<dt>
					<label id="pricemsg" for="price"><?php echo JText::_('PRICE');?></label>
					<span class="gb_required_field">&nbsp;*&nbsp;</span>
				</dt>
				<dd>
					<?php echo $this->lists['price'];?>
					<small>(<?php echo JText::_('PRICE_INFO');?>)</small>
				</dd>
			</dl>
			<div class="clear"><!--  --></div>			
			<?php endif; ?>
			
			<!-- Bilder -->
			<?php if(($this->params->get('enableimages'))): ?>
			<dl>
				<dt>
					<label id="imagemsg" for="image">
						<?php echo JText::_('IMAGE');?><br />
						<span id="image_infobar">
							<span id="imgcounter"><?php	echo (count($this->row->images)+1) ?></span> / <?php echo $this->max_image_upload_limit." ".JText::_('IMAGES'); ?>
						</span>
					</label>
				</dt>
				<dd class="uploadWrapper">
					<?php if($this->max_image_upload_limit>count($this->row->images)): ?>					
					<div id="img_0" class="imageholder">
						<div class="image_upload_input">
							<input type="file" name="images[]" class="inputtextbox" />
						</div>
						<?php if($this->params->get('required_field_images',0)): ?>
						<span class="gb_required_field">&nbsp;*&nbsp;</span>
						<?php endif; ?>						
					</div>
					
					<div>
						<a class="gb_addmore_images" id="addb" href="javascript:void(0);"><?php echo JText::_("ADD_MORE_IMAGES");?></a>
						<?php if($this->edit): ?>
						&nbsp;|&nbsp;
						<a class="gb_gallery" href="<?php echo $managelink; ?>"	rel="moodalbox 650 400 nofollow" title="Manage Images"><?php echo JText::_('MANAGE_IMAGES');?></a>
						<?php endif; ?>
					</div>					
					<?php else: ?>
										
					<?php echo JText::_('Image upload limit exceed');?>					
					
					<?php endif;?>
				</dd>
			</dl>
			<?php endif; ?>		
			
			
		</fieldset>
				
			
		<?php 
		if($this->params->get('enable_field_address1',0))
		{
			$adrequired="";
			if($this->params->get('required_field_address1',0))
			{
				$adrequired="required";
			}
		?>
		<div><label id="address1msg" for="address1"><?php echo JText::_('ADDRESS1');?></label>
		<input type="text" name="address1" class="inputtextbox <?php echo $adrequired;?>" id="address1" value="<?php echo $this->row->address1;?>" />
		<?php if($this->params->get('required_field_address1',0))
		{
		?>
		<span class="gb_required_field">&nbsp;*&nbsp;</span>
		<?php 
		}
		?>
		<br />
		<label>&nbsp;</label><small>(<?php echo JText::_('ADDRESS_EX');?>)</small>
		</div>
		<?php 
		}
		?>
		<?php 
		if($this->params->get('enable_field_address2',0))
		{
			$ad2required="";
			if($this->params->get('required_field_address2',0))
			{
				$ad2required="required";
			}
		?>
		
		<div><label id="address2msg" for="address2"><?php echo JText::_('ADDRESS2');?></label>
		<input type="text" name="address2" class="inputtextbox <?php echo $ad2required;?>"
			id="address2"
			value="<?php echo $this->row->address2;?>" />
			<?php if($this->params->get('required_field_address2',0))
		{
		?>
		<span class="gb_required_field">&nbsp;*&nbsp;</span>
		<?php 
		}
		?>
		</div>
		<br class="clear" />
		<?php 
		}
		?>
		<?php 
		if($this->params->get('enable_field_zipcode',0))
		{
			$ziprequired="";
			if($this->params->get('required_field_zipcode',0))
			{
				$ziprequired="required";
			}
		?>
		
		
		<div><label id="zipcodemsg" for="zipcode"><?php echo JText::_('ZIP_POSTAL');?></label>
		<input name="zipcode" type="text"
			class="inputtextbox <?php echo $ziprequired;?>" id="zipcode"
			value="<?php echo $this->row->zipcode;?>" />
			<?php if($this->params->get('required_field_zipcode',0))
		{
		?>
		<span class="gb_required_field">&nbsp;*&nbsp;</span>
		<?php 
		}
		?>
		</div>
		<br class="clear" />
		<?php 
		}
		?>
		
		<?php GApplication::triggerEvent("onAdInput",array(&$this->row,&$this->params,&$this->user));?>
		
		
		<div><label id="descriptionmsg" for="description"><?php echo JText::_('DESCRIPTION');?></label>
		<!--<span class="gb_required_field" style="float:right; padding-right:150px;">&nbsp;*&nbsp;</span>
		--><?php echo $editor->display('description',$this->row->description,'51%','50','30','5',false,array("mode"=>"simple")); ?>
		
		</div>
		<br class="clear" />
		
		<div id="ef">
		</div>
		
		
		<?php 
		if(isset($this->lists['showcontact']))
		{
			?>
			<div class="radiobox"><label id="lblShowcontact" for="showcantact"><?php echo JText::_('Show Contact');?></label>
			<div class="holder">
		<?php echo $this->lists['showcontact'];?></div>
		</div>
		<br class="clear" />
			<?php 
		}
		?>
		
		<?php
		if($this->params->get('enable_field_tags'))
		{
			$tagrequired="";
			if($this->params->get('required_field_tags',0))
			{
				$tagrequired="required";
			}
			?>
		<div><label id="tagsmsg" for="tags"><?php echo JText::_('TAGS');?></label>
		<textarea name="tags" id="tags" class="inputtextarea <?php echo $tagrequired;?>"><?php echo strip_tags($this->row->tags);?></textarea>
		<?php if($this->params->get('required_field_tags',0))
		{
		?>
		<span class="gb_required_field">&nbsp;*&nbsp;</span>
		<?php 
		}
		?>
		</div>
		<br class="clear" />
			<?php
		}
		if($this->params->get('enable_field_metadesc'))
		{
		$descrequired="";
			if($this->params->get('required_field_metadesc',0))
			{
				$descrequired="required";
			}
			?>
		
		<div><label id="metadescmsg" for="metadesc"> <?php echo JText::_('EXCERPTS');?><br />
		<small><?php echo JText::_('EXCERPT_INFO');?></small> </label> <textarea
			name="metadesc" id="metadesc" class="inputtextarea <?php echo $descrequired;?>"><?php echo strip_tags($this->row->metadesc);?></textarea>
			<?php if($this->params->get('required_field_metadesc',0))
		{
		?>
		<span class="gb_required_field">&nbsp;*&nbsp;</span>
		<?php 
		}
		?>
		</div>
		<br class="clear" />
			<?php
		}
		
		if ((int)$this->showexpiry)
		{
			?>
			<div><label id="tagsmsg" for="tags"><?php echo JText::_('EXPIRY_DATE');?></label>
			<?php echo JHTML::_('calendar', $this->row->expiry_date, "expiry_date", 'expiry_date', '%Y-%m-%d', array('class'=>'inputtextbox ',  'maxlength'=>'19'));?>
			</div>
		    <br class="clear" />
			<?php
		}
		
		GApplication::triggerEvent("onAfterFormDisplay",array(&$this->row,&$this->params));
		?>
		<br />
		<div><?php echo JText::sprintf('REQUIRED_INFO',"<span  class=\"gb_required_field\">&nbsp;*&nbsp;</span>");?></div>
		<br />
		<?php 
		echo $this->lists['terms'];
		?>
		<br />
		
		<div class="gb_button_wrapper">
		<button id="adSubmitBtn" class="gbButton validate" type="submit"><?php echo JText::_('SAVE'); ?></button>
		<?php
		$cancellink = JRoute::_("index.php?option=$option&task=categories&cancel=1&Itemid=$listitemid&time=".rand(100000,999999));
		?>
		<button type ="button" onclick="location.href='<?php echo $cancellink;?>';" class="gbButton"><?php echo JText::_('CANCEL');?></button>
		</div>
		<input type="hidden" name="edit" value="<?php echo $this->edit; ?>" />
		<input type="hidden" name="option" value="<?php echo $option?>" /> 
		
		<input type="hidden" name="Itemid" value="<?php echo $listitemid; ?>" /> 
		<input type="hidden" name="task" value="ads.save" /> 
		
		<?php echo JHTML::_( 'form.token' ); ?>
		
	</form>
</div>
