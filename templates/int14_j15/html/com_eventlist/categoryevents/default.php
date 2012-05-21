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
<div id="eventlist" class="el_categoryevents">

<div class="heading_wrap">
	<div class="componentheading">
		<h2><?php echo $pathway_reverse[2]; ?></h2>
		<p class="subheader"><?php echo $pathway_reverse[1]; ?></p>
	</div>
</div>

<?php if ($this->params->def( 'show_page_title', 1 )) : ?>
<div class="contentheading">
	<h3><?php echo $this->task == 'archive' ? $this->escape($this->category->catname.' - '.JText::_('ARCHIVE')) : $this->escape($this->category->catname); ?></h3>
</div>
<?php endif; ?>
<div class="clear"><!--  --></div>

<div class="contentpane">
	<div class="contentdescription">
		<?php echo $this->catdescription; ?>
	</div>

	<!--subcategories-->
	<?php 
	//only show this part if subcategries are available
	if (count($this->categories) && $this->category->id > 0) :
	?>
	<?php echo $this->loadTemplate('subcategories'); ?>
	<?php endif; ?>

	<?php echo $this->loadTemplate('attachments'); ?>

</div>

<form action="<?php echo $this->action; ?>" method="post" id="adminForm">
<!--table-->
<?php echo $this->loadTemplate('table'); ?>
<p>
<input type="hidden" name="option" value="com_eventlist" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="view" value="categoryevents" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->item->id;?>" />
</p>
</form>

<!--pagination-->

<?php if (!$this->params->get( 'popup' ) ) : ?>
<div class="pageslinks">
	<?php echo $this->pageNav->getPagesLinks(); ?>
</div>

<p class="pagescounter">
	<?php echo $this->pageNav->getPagesCounter(); ?>
</p>
<?php endif; ?>

</div>