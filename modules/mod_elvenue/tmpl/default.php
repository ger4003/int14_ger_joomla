<?php 
// TODO: Insert Google Map
?>

<?php if(!$venue): ?>
	Diese Modul ist nur auf der Detailansicht der Eventlist-Komponente verfügbar
<?php else: ?>

<p><?php echo $venue->venue;?><br /><br /></p>
<p>
	<u>Adresse:</u><br />
	<?php echo $venue->street;?><br />
	<?php echo $venue->plz;?>, <?php echo $venue->city;?><br />
</p>

<a href="<?php echo JRoute::_( 'index.php?view=venueevents&id='.EventListHelperRoute::getRoute($venue->id, 'venueevents') ); ?>" class="more">mehr</a>
<div class="clear"><!--  --></div>
<?php endif; ?>