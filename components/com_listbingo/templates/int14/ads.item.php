<?php 


defined('_JEXEC') or die('Restricted access');
global $option, $listitemid;
$editlink 		= JRoute::_('index.php?option=' . $option . '&Itemid='.$listitemid.'&task=ads.edit&catid=' . $this->row->category_id . '&adid=' . $this->row->id);
$suffix 			= $this->params->get ( $this->params->get ( 'listlayout_thumbnail' ) );
$link 				= JRoute::_ ( "index.php?option=$option&Itemid=$listitemid&task=ads.view&adid=" . $this->row->slug );
$noimage 			= JUri::root () . $this->params->get ( 'path_default_profile_noimage' );
$baseurl 			= JUri::root ();
$adminbaseurl = JUri::root () . "administrator/components/$option/images/";
$basepath 		= JPATH_ROOT . DS;

$currency = new GCurrency ( $this->row->price, $this->row->currencycode, $this->row->currency, $this->params->get ( 'currency_format' ), $this->params->get ( 'decimals' ), $this->params->get ( 'decimal_separator' ), $this->params->get ( 'value_separator' ) );

switch ($this->row->pricetype) {
	
	case 2 :
		$price = JText::_ ( 'FREE' );
		break;
	case 3 :
		$price = JText::_ ( 'PRICE_NEGOTIABLE' );
		break;
	
	default :
		if ($this->row->price > 0) {
			$price = $currency->toString ();
		} else {
			$price = JText::_ ( 'FREE' );
		}
		break;
}


$address = array ();
$regions = array ();

if (! empty ( $this->row->address->address )) {
	$address [] = $this->row->address->address;
}

if (! empty ( $this->row->address->street )) {
	$address [] = $this->row->address->street;
}

if (! empty ( $this->row->address->region )) {
	$regions [] = $this->row->address->region;
}

if (! empty ( $this->row->address->state )) {
	$regions [] = $this->row->address->state;
}

if (! empty ( $this->row->address->zipcode )) {
	$regions [] = $this->row->address->zipcode;
}

$adaddress = implode ( ", ", $address );
$adregion = implode ( ", ", $regions );
$class = "";

if (isset ( $this->row->classsuffix )) {
	$class = $this->row->classsuffix;
}

?>

<li class="<?php if($this->isFirst):?>first<?php endif; ?>">

	<div class="gb_listings_content" >
		<div class="gb_listing normal_listing">
			<div class="gb_wrapper <?php echo $class; ?>">
				<dl class="gb_double_wrapper">
					<dt>
						<?php if($this->params->get('enableimages',0)): ?>
						<!-- images -->
						<div class="gb_thumbnail">
							<div class="gb_thumbnail_wrapper" title="<?php echo $this->row->title; ?>">
								<a class="gb_title_link" title="<?php echo $this->row->title; ?>" href="<?php echo $link; ?>">
									<?php if (file_exists ( $basepath . $this->row->image . $suffix . "." . $this->row->extension )): ?> 
									<img 
										src="<?php echo $baseurl . $this->row->image . $suffix . "." . $this->row->extension; ?>"
										alt="<?php echo $this->row->title; ?>" /> 
								 <?php else : ?>
								  <img 
								  	src="<?php echo $adminbaseurl . "noimage.png"?>"  
								  	alt="<?php echo $this->row->title; ?>" /> 
								<?php endif; ?>
								</a>
							</div>
						</div>
						<?php endif; ?>
					</dt>
					<dd>
						<div class="gb_normal_section">
							<div class="gb_listing_header">
								<a href="<?php echo $link; ?>"><h4><?php echo $this->row->title; ?></h4></a>
							</div>
							
							<?php if (JRequest::getInt ( 'catid' ) == 0): ?>
							<!-- Category -->
							<div class="gb_item_category">
								<?php echo $this->row->category; ?>
							</div>
							<?php endif; ?>
							
							<div class="gb_listing_body">
								<?php  if ($this->params->get ( 'enable_listing_introtext' )) :
									$introtext_length = $this->params->get ( 'listing_introtext_length' ); ?>
								<!-- Introtext -->
								<?php echo GHelper::trunchtml ( $this->row->description, $introtext_length ); ?>
								<?php endif; ?>
							</div>
							<div class="clear"><!--  --></div>
							
							<?php if(isset($this->row->extrafields) && count($this->row->extrafields) > 0): ?>
								<!-- itemextrainfo -->
								<?php $this->render ( 'itemextrainfo', array ("extrainfo" => $this->row->extrafields ) ); ?>
							<?php endif; ?>
							
							<!-- extra info -->
							<div class="gb_item_extrainfo">
								<?php if ($this->row->hasprice && $this->params->get('enable_field_price')): ?>
								<!-- Price -->
								<span class="price">
									<strong>Preis:</strong> <?php	echo $price; ?>
								</span>
								<?php endif; ?>
							</div><!-- /gb_item_extrainfo -->
							
							<!-- listing actions -->
							<div class="gb_listing_actions">
								<ul class="gb_popup_action">
									<?php if (($this->userid == $this->row->uid) && ! $this->guest): ?>
									<!-- edit link -->
									<!--
									<li class="gb_listing_ad_edit">
										<a href="<?php echo $editlink; ?>"><?php echo JText::_ ( 'EDIT' ); ?></a>
									</li>
									-->
									<?php endif; ?>
									<!-- view details link -->
									<li class="gb_listing_viewdetails">
										<a href="<?php echo $link; ?>" class="more"><?php echo JText::_ ( 'VIEW_DETAIL' ); ?></a>
									</li>
								</ul>
							</div><!-- gb_listing_actions -->
						
						</div><!-- /gb_normal_section -->
						
						<div class="gb_onafteritemdisplay">
							<?php
							//GApplication::triggerEvent ( 'onLoadProfile', array (& $this->user, & $this->params ) );
							GApplication::triggerEvent ( 'onAfterItemDisplay', array (&$this->row, &$this->params ) );
							?>
						</div>
					</dd>
				</dl><!-- /gb_double_wrapper -->
				<div class="clear" /></div>
			</div><!-- /gb_wrapper -->
		</div><!-- /gb_listing normal_lis"ting -->
	</div><!-- /gb_listings_content -->

</li>
