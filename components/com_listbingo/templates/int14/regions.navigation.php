<?php
/**
 *
 * @package Gobingoo
 * @subpackage Listbingo
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');
global $option;
global $listitemid;
?>

<div class="gbNavigation">
<?php if($this->params->get('country_selection')):
	$changelink=JRoute::_("index.php?option=$option&task=countries&time=".time()."&Itemid=$listitemid");
	?>
	 
	<a href="<?php echo $changelink;?>">
	<?php 
	if($this->params->get('country_tab_text')!="")
	{
		echo $this->params->get('country_tab_text',JText::_('CHANGE_COUNTRY'));	
	}
	else
	{
		echo JText::_('CHANGE_COUNTRY');	
	}
	?>
	</a>&nbsp;
<?php endif; ?>

<?php if($this->params->get('region_selection')):
	global $mainframe;
	$cid =$mainframe->getUserState($option.'country',0);
	$changelink=JRoute::_("index.php?option=$option&task=regions&cid=$cid&time=".time()."&Itemid=$listitemid");
	?> 
	
	<a href="<?php echo $changelink;?>">
	<?php 
	if($this->params->get('region_tab_text')!="")
	{
		echo $this->params->get('region_tab_text',JText::_('CHANGE_REGION'));	
	}
	else
	{
		echo JText::_('CHANGE_COUNTRY');	
	}
	?>
	</a>&nbsp;	

<?php endif; ?>


<?php if(JRequest::getInt('catid',0) || JRequest::getInt('adid',0) || JRequest::getCmd('task')=='ads' || JRequest::getCmd('task')=='ads.search'): ?>
	
	<dl>
		<dt>
			<a href="/boerse/anzeigen.html">
			<!-- <a href="<?php echo JRoute::_('index.php?option='.$option.'&task=categories&time='.time()."&Itemid=$listitemid");?>"> -->
				<?php echo $this->params->get('category_tab_text',JText::_('CHANGE_CATEGORY')); ?>
			</a>
		</dt>
		<dd>
			<ul class="bullet_list">
			<?php foreach($this->categories as $categorie): ?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option='.$option.'&Itemid='.$listitemid.'&task=categories.select&catid='.$categorie->slug.'&time='.time());?>">
						<?php echo $categorie->title; ?>
					</a>
				</li> 
			<?php endforeach;?>
			</ul>	
		</dd>
	</dl>
	
	
	<!--  
	<a href="<?php echo JRoute::_('index.php?option='.$option.'&task=categories&time='.time()."&Itemid=$listitemid");?>">
	<?php 
	if($this->params->get('category_tab_text')!="")
	{
		echo $this->params->get('category_tab_text',JText::_('CHANGE_CATEGORY'));	
	}
	else
	{
		echo JText::_('CHANGE_CATEGORY');	
	}
	?>
	</a>
	 -->
<?php endif; ?>

</div>
