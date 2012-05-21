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

// Get the breadcrumbs
$pathwaySite	= $mainframe->getPathway();
$pathway_reverse = array_reverse($pathwaySite->getPathwayNames());
?>

<div id="eventlist" class="event_id<?php echo $this->row->did; ?> el_details">
	<div class="heading_wrap">
		<div class="componentheading">
			<h2><?php echo $pathway_reverse[2];?></h2>
			<p class="subheader"><?php echo $pathway_reverse[1];?></p>
		</div>
	</div>

	<?php if ($this->params->def( 'show_page_title', 1 )) : ?>
	<div class="contentheading">
		<h3><?php echo $this->escape($this->row->title); ?></h3>
	</div>
	<?php endif; ?>
	<div class="clear"><!--  --></div>

	<div class="contentpane">
		<!-- Details EVENT -->
		<?php echo ELOutput::editbutton($this->item->id, $this->row->did, $this->params, $this->allowedtoeditevent, 'editevent' ); ?>

		<?php //flyer
		# echo ELOutput::flyer( $this->row, $this->dimage, 'event' );
		?>

		<dl class="definitionlist">
			<dt class="when"><?php echo JText::_( 'WHEN' ).':'; ?></dt>
			<dd class="when">
				<?php
				if (ELHelper::isValidDate($this->row->dates))
				{
					echo ELOutput::formatdate($this->row->dates, $this->row->times);

	    		if (ELHelper::isValidDate($this->row->enddates) && $this->row->enddates != $this->row->dates) :
	    			echo ' - '.ELOutput::formatdate($this->row->enddates, $this->row->endtimes);
	    		endif;
				}
				else {
					echo JText::_('COM_EVENTLIST_OPEN_DATE');
				}

	    		if ($this->elsettings->showtimedetails == 1) :

					echo '&nbsp;'.ELOutput::formattime($this->row->dates, $this->row->times);

					if ($this->row->endtimes) :
						echo ' - '.ELOutput::formattime($this->row->enddates, $this->row->endtimes);
					endif;
				endif;
				?>
			</dd>
		</dl>
		<div class="clear"><!--  --></div>

		<dl class="definitionlist">
	  		<?php
	  		if ($this->row->locid != 0) :
	  		?>
			    <dt class="where"><?php echo JText::_( 'WHERE' ).':'; ?></dt>
			    <dd class="where">
	    		<?php if (($this->elsettings->showdetlinkvenue == 1) && (!empty($this->row->url))) : ?>

				    <a href="<?php echo $this->row->url; ?>" target="_blank"><?php echo $this->escape($this->row->venue); ?></a>

				<?php elseif ($this->elsettings->showdetlinkvenue == 2) : ?>

				    <a href="<?php echo JRoute::_( 'index.php?view=venueevents&id='.$this->row->venueslug ); ?>"><?php echo $this->row->venue; ?></a>

				<?php elseif ($this->elsettings->showdetlinkvenue == 0) :

					echo $this->escape($this->row->venue).' - ';

				endif; ?>

				</dd>
			</dl>
			<div class="clear"><!--  --></div>

		<?php if ($this->row->state != "") : ?>
		<dl class="definitionlist">
	    <dt class="state">Revier:</dt>
	    <dd class="state">
				<?php echo $this->row->state; ?>
			</dd>
		</dl>
		<div class="clear"><!--  --></div>
		<?php endif; ?>


			<?php endif;
			$n = count($this->categories);
			?>
			<dl class="definitionlist">
				<dt class="category"><?php echo $n < 2 ? JText::_( 'CATEGORY' ) : JText::_( 'CATEGORIES' ); ?>:</dt>
	    		<dd class="category">
	    			<?php
					$i = 0;
	    			foreach ($this->categories as $category) :
	    			?>
						<a href="<?php echo JRoute::_( 'index.php?view=categoryevents&id='. $category->slug ); ?>"><?php echo $this->escape($category->catname); ?></a>
					<?php
						$i++;
						if ($i != $n) :
							echo ',';
						endif;
					endforeach;
	    			?>
				</dd>
		</dl>
		<div class="clear"><!--  --></div>

	 	<?php if ($this->elsettings->showevdescription == 1 && $this->row->datdescription != '' && $this->row->datdescription != '<br />') : ?>
		<div class="description event_desc">
			<?php echo $this->row->datdescription; ?>
	  </div>
	 	<?php endif; ?>


		<?php /* if ($this->row->registra == 1) : ?>
			<!-- Registration -->
			<?php echo $this->loadTemplate('attendees'); ?>
		<?php endif; */ ?>

		<?php echo $this->row->pluginevent->onEventDetailsEnd; ?>
	</div>
</div>