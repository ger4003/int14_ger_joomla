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

defined( '_JEXEC' ) or die( 'Restricted access' );

$pathway = JSite::getPathWay()->getPathwayNames();
?>
<div id="eventlist" class="el_categoriesdetailed">

<div class="heading_wrap">
	<div class="componentheading">
		<h2><?php echo $pathway[1]; ?></h2>
	</div>
</div>

<?php if ($this->params->get('show_page_title')) : ?>
<div class="contentheading">
	<h3><?php echo $this->escape($this->pagetitle); ?></h3>
</div>
<?php endif;

foreach($this->categories as $category) :
?>
	<h2 class="eventlist cat<?php echo $category->id; ?>">
		<?php echo JHTML::_('link', JRoute::_($category->linktarget), $this->escape($category->catname)); ?>
	</h2>

<div class="cat<?php echo $category->id; ?> floattext">

<?php if ($category->image) : ?>
	<div class="catimg">
	  	<?php
	  		echo JHTML::_('link', JRoute::_($category->linktarget), JHTML::image('images/stories/'.$category->image, $category->catname));
		?>
		<p>
			<?php
				echo JText::_( 'EVENTS' ).': ';
				echo JHTML::_('link', JRoute::_($category->linktarget), $category->assignedevents ? $category->assignedevents : '0');
			?>
		</p>
	</div>
<?php endif; ?>

	<div class="catdescription"><?php echo $category->catdescription; ?>
		<p>
			<?php
				echo JHTML::_('link', JRoute::_($category->linktarget), $category->linktext);
			?>
			(<?php echo $category->assignedevents ? $category->assignedevents : '0';?>)
		</p>
	</div>
	<br class="clear" />

</div>

<?php 
//only show this part if subcategries are available
if (count($category->subcats)) :
?>

<div class="subcategories">
<?php echo JText::_('SUBCATEGORIES'); ?>
</div>
<?php
$n = count($category->subcats);
$i = 0;
?>
<div class="subcategorieslist">
	<?php foreach ($category->subcats as $sub) : ?>
		<strong><a href="<?php echo JRoute::_( 'index.php?view=categoryevents&id='. $sub->slug ); ?>"><?php echo $this->escape($sub->catname); ?></a></strong> (<?php echo $sub->assignedevents != null ? $sub->assignedevents : 0; ?>)
		<?php 
		$i++;
		if ($i != $n) :
			echo ',';
		endif;
	endforeach; ?>
</div>

<?php endif; ?>

<!--table-->
<?php
//TODO: move out of template
$this->rows		= & $this->model->getEventdata( $category->id );
$this->categoryid = $category->id;

echo $this->loadTemplate('table');

endforeach;
?>

<!--pagination-->

<div class="pageslinks">
	<?php echo $this->pageNav->getPagesLinks(); ?>
</div>

<p class="pagescounter">
	<?php echo $this->pageNav->getPagesCounter(); ?>
</p>

<!--copyright-->

<p class="copyright">
	<?php echo ELOutput::footer( ); ?>
</p>
</div>