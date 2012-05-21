<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$isOpen = false;
$itemsPerRow = $this->params->get('num_columns', 2); 
$itemTotal = $this->params->get('num_leading_articles', 10);
if(count($this->items) < $itemTotal) {
	$itemTotal = count($this->items); 
}
?>

<div class="contentpane">
	<?php //TODO: Headline should be dynamic ?>
	<?php //TODO: Headline should be multilingual ?>
	<h2>Neues auf der Seite</h2>
	
	<?php if($itemTotal > $itemsPerRow): ?>
	<div class="scrollable-navi">
		<ul class="navi-items">
			<li class="browse"><a class="prev">prev</a></li>
			<?php $rowCount = ceil($itemTotal / $itemsPerRow);
			for($i=1; $i <= $rowCount; $i++): ?>
			<li class="navi-item <?php if($i==1):?>active<?php endif;?>">
				<a href="#<?php echo $i; ?>"><?php echo $i; ?></a>
			</li>
			<?php endfor; ?>
			<li class="browse"><a class="next">next</a></li>
		</ul>
	</div>
	<?php endif; ?>
	
	<div class="mod_content_wrap scrollable">
		<div class="items">			
			<?php for($key=0; $key < $itemTotal; $key++): ?>
			
			
			<?php if($key % $itemsPerRow == 0 && $isOpen): $isOpen = false; ?>
			</ul>
			<?php endif; ?>
	
			<?php if($key % $itemsPerRow == 0): $isOpen = true; ?>
			<ul class="item_list">
			<?php endif; ?>
				<li class="<?php if($key % $itemsPerRow == 0): ?>firstinrow<?php endif; ?>">		
					<?php
					$this->item =& $this->getItem($key, $this->params);
					echo $this->loadTemplate('item');
					?>					
				</li>			
			<?php endfor; ?>
		</div>
	</div>
	<div class="clear"><!--  --></div>
		
</div>
