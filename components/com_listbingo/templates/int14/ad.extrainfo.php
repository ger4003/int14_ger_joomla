<?php
/**
 * @package gobingoo
 * @subpackage listbingo
 * extrainfo subtemplate
 * @author Bruce@gobingoo.com
 * code Bruce
 */
defined('_JEXEC') or die('Restricted access');

if(isset($this->extrainfo) && count($this->extrainfo)>0):
	foreach($this->extrainfo as $f):
		// info is not to be seen
		if(!$f->view_in_detail || $f->value=="")
		{
			continue;
		}
		
		
		switch ($f->type) {
			// set values for radio fields
			case "radio":
				switch($f->value) {
					case 0:
						$value = "Nein";
						break;
					case 1:
						$value = "Ja";
						break;
					default:
						$value = $f->value;
				}
				break;
			// default values
			default:
				$value = $f->value;
		}
		?>
		<li>
			<dl class="gb_ad_<?php echo strtolower($t-title); ?>">
				<dt>
					<?php if(!$f->hidecaption): ?>
					<?php echo $f->title;?>:
					<?php endif; ?>
				</dt>
				<dd>
					<?php 
					echo $f->view_prefix." ";
					GExtrafieldHelper::renderVal($f->type, $value);
					echo " ".$f->view_suffix;
					?>
				</dd>
			</dl>
		</li>
	<?php
	endforeach;
endif;
?>
