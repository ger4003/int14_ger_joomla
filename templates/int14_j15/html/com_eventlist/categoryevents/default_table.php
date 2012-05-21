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


<table class="eventtable" width="<?php echo $this->params->get('tablewidth'); ?>" border="0" cellspacing="0" cellpadding="0" summary="eventlist">
<!--
	<colgroup>
		<col width="<?php echo $this->params->get('datewidth'); ?>" class="el_col_date" />
		<?php if ($this->params->get('showtitle') == 1) : ?>
			<col width="<?php echo $this->params->get('titlewidth'); ?>" class="el_col_title" />
		<?php endif; ?>
		<?php if ($this->params->get('showlocate') == 1) :	?>
			<col width="<?php echo $this->params->get('locationwidth'); ?>" class="el_col_venue" />
		<?php endif; ?>
		<?php if ($this->params->get('showstate') == 1) :	?>
			<col width="<?php echo $this->params->get('statewidth'); ?>" class="el_col_state" />
		<?php endif; ?>
		<?php if ($this->params->get('showcat') == 1) :	?>
			<col width="<?php echo $this->params->get('catfrowidth'); ?>" class="el_col_category" />
		<?php endif; ?>
		<?php if ($this->params->get('showatte') == 1) :	?>
			<col width="<?php echo $this->params->get('attewidth'); ?>" class="el_col_attendees" />
		<?php endif; ?>
	</colgroup>
-->
	<thead>
			<tr>
				<th id="el_date" class="sectiontableheader el_date" align="left">
					<?php echo $this->escape($this->params->get('datename')); ?>
				</th>
				<?php if ($this->params->get('showtitle') == 1) : ?>
				<th id="el_title" class="sectiontableheader el_title" align="left">
					<?php echo $this->escape($this->params->get('titlename')); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('showlocate') == 1) : ?>
				<th id="el_location" class="sectiontableheader el_location" align="left">
					<?php echo $this->escape($this->params->get('locationname')); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('showstate') == 1) : ?>
				<th id="el_state" class="sectiontableheader el_state" align="left">
					<?php echo $this->escape($this->params->get('statename')); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('showcat') == 1) : ?>
				<th id="el_category" class="sectiontableheader el_category" align="left">
					<?php echo $this->escape($this->params->get('catfroname')); ?>
				</th>
				<?php endif; ?>
				<?php if ($this->params->get('showatte') == 1) : ?>
				<th id="el_attendees" class="sectiontableheader el_attendees" align="left">
					<?php echo $this->escape($this->params->get('attename')); ?>
				</th>
				<?php endif;?>
			</tr>
	</thead>
	<tbody>
	<?php
	if ($this->noevents == 1) :
		?>
		<tr align="center"><td><?php echo JText::_( 'NO EVENTS' ); ?></td></tr>
		<?php
	else :

	$this->rows =& $this->getRows();

	foreach ($this->rows as $row) :
		?>
  			<tr class="sectiontableentry<?php echo ($row->odd +1 ) . $this->params->get( 'pageclass_sfx' ); ?>" >

    			<td headers="el_date" class="el_date" align="left">

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
				$detaillink 	= JRoute::_( EventListHelperRoute::getRoute($row->slug));
				//title
				if (($this->params->get('showtitle') == 1 ) && ($this->params->get('showdetails') == 1) ) : ?>
				<td headers="el_title" class="el_title" align="left" valign="top">
					<a href="<?php echo $detaillink ; ?>"> <?php echo $this->escape($row->title); ?></a>
				</td>

				<?php endif; ?>

				<?php if (( $this->params->get('showtitle') == 1 ) && ($this->params->get('showdetails') == 0) ) : ?>
				<td headers="el_title" class="el_title" align="left" valign="top">
					<?php echo $this->escape($row->title); ?>
				</td>
				<?php endif; ?>

				<?php if ($this->params->get('showlocate') == 1) : ?>

					<td headers="el_location" class="el_location" align="left" valign="top">
						<!-- location -->
						<?php
						if ($this->params->get('showlinkvenue') == 1 ) :
							echo $row->locid != 0 ? "<a href='".JRoute::_('index.php?view=venueevents&id='.$row->venueslug)."'>".$this->escape($row->venue)."</a>" : '-';
						else :
							echo $row->locid ? $this->escape($row->venue) : '-';
						endif;
						?>

						<!-- city -->
						<?php if ($this->params->get('showcity') == 1) :?>
						<br />
						<?php echo $row->city ? $this->escape($row->city) : '-'; ?>
						<?php endif; ?>

				<?php endif; ?>

				<!-- state/revier -->
				<?php if ($this->params->get('showstate') == 1) : ?>
					<td headers="el_state" class="el_state" align="left" valign="top"><?php echo $row->state ? $this->escape($row->state) : '-'; ?></td>
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
				if ($this->params->get('showatte') == 1) :
				?>

					<td headers="el_attendees" align="left" valign="top"><?php echo $row->regCount ? $this->escape($row->regCount) : '-'; ?></td>
				<?php
				endif;
				?>

			</tr>

  		<?php
		endforeach;
		endif;
		?>

	</tbody>
</table>