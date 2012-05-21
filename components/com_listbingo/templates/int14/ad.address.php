<?php
/**
 * @package gobingoo
 * @subpackage listbingo
 * Address subtemplate
 * @author Bruce@gobingoo.com
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');


if ($this->params->get ( 'enable_field_address1', 0 ) || $this->params->get ( 'enable_field_address2', 0 )): 
?>
<li>
	<dl class="gb_ad_address">
		<dt>
			Standort:
			<?php #echo JText::_('LOCATION');?>
		</dt>
		<dd>
			<?php echo $this->address; ?>
			
			<?php if(isset($this->regions)): ?>
			<!-- regions -->
			<br /><?php echo $this->regions; ?>
			<?php endif; ?>
			
			<?php if(isset($this->row->country) && !empty($this->row->country)): ?>
			<!-- country -->
			<br /><?php echo $this->row->country; ?>
			<?php endif; ?>
		</dd>
	</dl>
</li>
<?php endif; ?>