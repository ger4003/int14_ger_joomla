<?php 
include_once(JPATH_SITE.DS.'components'.DS.'com_eventlist'.DS.'helpers'.DS.'route.php');
// TODO: Insert Google Map
?>

<ul class="listing">
	<?php foreach($events as $event):?>
	<li>
		<?php
		$categorie 	= modElEventsHelper::getCategorie($event);
		$venue 			= modElEventsHelper::getVenue($event);
		$detaillink = JRoute::_( EventListHelperRoute::getRoute($event->id."-".$event->alias));
		?>
		
		<a href="<?php echo $detaillink?>">
			<h3 style="margin-bottom: 3px;"><?php echo $event->title;?></h3>
		</a>
		<p>
		<?php if($event->dates == date('Y-m-d')):?>
			Heute
		<?php else: ?>
			<?php /* Termin mit Enddatum */ ?>
			<?php if($event->enddates != ""):?>
			
			<?php /* Start- und Enddatum im selben Monat */ ?>
			<?php if(strftime('%b.',strtotime($event->dates)) == strftime('%b.',strtotime($event->enddates))):?>
			<?php echo strftime('%d.',strtotime($event->dates));?> - <?php echo strftime('%d. %b.',strtotime($event->enddates));?>
			<?php /* Start- und Enddatum in verschiedenen Monaten */ ?>
			<?php else:?>
			<?php echo strftime('%d. %b.',strtotime($event->dates));?> - <?php echo strftime('%d. %b.',strtotime($event->enddates));?>
			<?php endif;?>
			
			<?php /* Termin ohne Enddatum */ ?>
			<?php else:?>
			<?php echo strftime('%d. %b.',strtotime($event->dates));?>
			<?php if($event->times != ""):?>
			<?php echo $event->times;?> Uhr
			<?php endif;?>
			<?php endif; /* /Termin ohne Enddatum */ ?>
		<?php endif;?>
		</p>
		<!-- <p><?php echo $venue->state;?></p> -->
		
	</li>
	<?php endforeach; ?>
</ul>

<a href="<?php echo EventListHelperRoute::getRoute(1, 'categoryevents')?>" class="button icon btn_arrow" style="float: right">Alle Termine anzeigen</a>