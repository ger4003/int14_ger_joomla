<?php
/**
 * @version 1.1 $Id$
 * @package Joomla
 * @subpackage EventList
 * @copyright (C) 2005 - 2009 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * EventList is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php if (count((array)$this->attending)) : ?>
<h2><?php echo JText::_('REGISTERED TO'); ?></h2>

<script type="text/javascript">

	function tableOrdering( order, dir, view )
	{
		var form = document.getElementById("attending-events");

		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		form.submit( view );
	}
</script>

<form action="<?php echo $this->action; ?>" method="post" id="attending-events">

<table class="eventtable" width="<?php echo $this->params->get('tablewidth'); ?>" border="0" cellspacing="0" cellpadding="0" summary="Attending">

	<colgroup>
		<col width="<?php echo $this->params->get('datewidth'); ?>" class="el_col_date" />
		<?php if ($this->params->get('showtitle') == 1) : ?>
			<col width="<?php echo $this->params->get('titlewidth'); ?>" class="el_col_title" />
		<?php endif; ?>
		<?php if ($this->params->get('showlocate') == 1) :	?>
			<col width="<?php echo $this->params->get('locationwidth'); ?>" class="el_col_venue" />
		<?php endif; ?>
		<?php if ($this->params->get('showcity') == 1) :	?>
			<col width="<?php echo $this->params->get('citywidth'); ?>" class="el_col_city" />
		<?php endif; ?>
		<?php if ($this->params->get('showstate') == 1) :	?>
			<col width="<?php echo $this->params->get('statewidth'); ?>" class="el_col_state" />
		<?php endif; ?>
		<?php if ($this->params->get('showcat') == 1) :	?>
			<col width="<?php echo $this->params->get('catfrowidth'); ?>" class="el_col_category" />
		<?php endif; ?>
	</colgroup>

	<thead>
			<tr>
				<th id="el_date" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', $this->escape($this->params->get('datename')), 'a.dates', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
				<?php
				if ($this->params->get('showtitle') == 1) :
				?>
				<th id="el_title" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', $this->escape($this->params->get('titlename')), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
				<?php
				endif;
				if ($this->params->get('showlocate') == 1) :
				?>
				<th id="el_location" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', $this->escape($this->params->get('locationname')), 'l.venue', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
				<?php
				endif;
				if ($this->params->get('showcity') == 1) :
				?>
				<th id="el_city" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', $this->escape($this->params->get('cityname')), 'l.city', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
				<?php
				endif;
				if ($this->params->get('showstate') == 1) :
				?>
				<th id="el_state" class="sectiontableheader" align="left"><?php echo JHTML::_('grid.sort', $this->escape($this->params->get('statename')), 'l.state', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
				<?php
				endif;
				if ($this->params->get('showcat') == 1) :
				?>
				<th id="el_category" class="sectiontableheader" align="left"><?php echo $this->escape($this->params->get('catfroname')); ?></th>
				<?php
				endif;
				?>
			</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ((array) $this->attending as $row) :
	?>
  			<tr class="sectiontableentry<?php echo $i +1 . $this->params->get( 'pageclass_sfx' ); ?>" >

    			<td headers="el_date" align="left">
    				<strong>
    			    <?php if (ELHelper::isValidDate($row->dates)): ?>
	    					<?php echo ELOutput::formatdate($row->dates, $row->times); ?>
	    					
	    					<?php
	    					if ($row->enddates && $row->enddates != $row->dates) :
	    						echo ' - '.ELOutput::formatdate($row->enddates, $row->endtimes);
	    					endif;
	    					?>
    					<?php else: ?>
    						<?php echo JText::_('COM_EVENTLIST_OPEN_DATE'); ?>
    					<?php endif; ?>
    				</strong>
    				
					<?php
					if ($this->params->get('showtime') == 1) :
					?>
						<br />
						<?php
						echo ELOutput::formattime($row->dates, $row->times);
						
						if ($row->endtimes) :
							echo ' - '.ELOutput::formattime($row->enddates, $row->endtimes);
						endif;
					endif;
					?>
				</td>

				<?php
				//Link to details
				$detaillink = JRoute::_( EventListHelperRoute::getRoute($row->slug) );
				//title
				if (($this->params->get('showtitle') == 1 ) && ($this->params->get('showdetails') == 1) ) :
				?>

				<td headers="el_title" align="left" valign="top"><a href="<?php echo $detaillink ; ?>"> <?php echo $this->escape($row->title); ?></a></td>

				<?php
				endif;

				if (( $this->params->get('showtitle') == 1 ) && ($this->params->get('showdetails') == 0) ) :
				?>

				<td headers="el_title" align="left" valign="top"><?php echo $this->escape($row->title); ?></td>

				<?php
				endif;
				if ($this->params->get('showlocate') == 1) :
				?>

					<td headers="el_location" align="left" valign="top">
						<?php
						if ($this->params->get('showlinkvenue') == 1 ) :
							echo $row->locid != 0 ? "<a href='".JRoute::_('index.php?view=venueevents&id='.$row->venueslug)."'>".$this->escape($row->venue)."</a>" : '-';
						else :
							echo $row->locid ? $this->escape($row->venue) : '-';
						endif;
						?>
					</td>

				<?php
				endif;

				if ($this->params->get('showcity') == 1) :
				?>

					<td headers="el_city" align="left" valign="top"><?php echo $row->city ? $this->escape($row->city) : '-'; ?></td>

				<?php
				endif;

				if ($this->params->get('showstate') == 1) :
				?>

					<td headers="el_state" align="left" valign="top"><?php echo $row->state ? $this->escape($row->state) : '-'; ?></td>

				<?php
				endif;

				if ($this->params->get('showcat') == 1) :
				?>
				<td headers="el_category" align="left" valign="top">
					<?php
					$nr = count($row->categories);
					$ix = 0;
					foreach ($row->categories as $key => $category) :

						if ($this->params->get('catlinklist') == 1) :
						?>
								<a href="<?php echo JRoute::_('index.php?view=categoryevents&id='.$category->catslug); ?>">
									<?php echo $category->catname; ?>
								</a>
						<?php else : ?>

							<?php echo $category->catname; ?>

						<?php
						endif;
						
						$ix++;
						if ($ix != $nr) :
							echo ', ';
						endif;
					endforeach;
					?>
				</td>
				<?php
				endif;
				?>

			</tr>

  		<?php
  		$i = 1 - $i;
		endforeach;
		?>

	</tbody>
</table>
<p>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</p>
</form>

<div class="pageslinks">
  <?php echo $this->attending_pageNav->getPagesLinks(); ?>
</div>

<p class="pagescounter">
  <?php echo $this->attending_pageNav->getPagesCounter(); ?>
</p>
<?php endif; ?>