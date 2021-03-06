<?php
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
$cropfrom=array();

$cropfrom[]=JHTML::_('select.option', 'R', JText::_('Left'), 'id', 'title');
$cropfrom[]=JHTML::_('select.option', 'L', JText::_('Right'), 'id', 'title');
$cropfrom[]=JHTML::_('select.option', 'B', JText::_('Top'), 'id', 'title');
$cropfrom[]=JHTML::_('select.option', 'T', JText::_('Bottom'), 'id', 'title');
$cropfrom[]=JHTML::_('select.option', 'TBLR', JText::_('Center'), 'id', 'title');

$convertto=array();

$convertto[]=JHTML::_('select.option', 'png', JText::_('PNG'), 'id', 'title');
$convertto[]=JHTML::_('select.option', 'jpg', JText::_('JPG'), 'id', 'title');
$convertto[]=JHTML::_('select.option', 'gif', JText::_('GIF'), 'id', 'title');


$displaynames = array ();

$displaynames [] = JHTML::_ ( 'select.option', 'name', JText::_ ( 'Real Name' ), 'id', 'title' );
$displaynames [] = JHTML::_ ( 'select.option', 'username', JText::_ ( 'Username' ), 'id', 'title' );
?>

<fieldset class="adminform"><legend><?php echo JText::_( 'Profile Image Settings' ); ?></legend>
<table class="admintable" cellspacing="1">
	<tbody>
	<tr>
			<td class="key"><span class="hasTip"
				title="<?php
				echo JText::_ ( 'Display Name' );
				?>::<?php
				echo JText::_ ( 'Username/Realname to be used in listings' );
				?>">
				<?php
				echo JText::_ ( 'Display Name' );
				?> </span></td>
			<td valign="top"><?php
			echo JHTML::_ ( 'select.genericlist', $displaynames, 'config[lb_display_name]', 'class="inputbox"', 'id', 'title', $this->config->get ( 'lb_display_name' ) );
			;
			?></td>
		</tr>
		<tr>
			<td class="key" style="width:250px;"><span class="hasTip"
				title="<?php echo JText::_( 'Enable Profile bingo' ); ?>::<?php echo JText::_('If Yes, Profile will be manage from profilebingo'); ?>">
				<?php echo JText::_( 'Enable Profile bingo' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[enable_profilebingo]' , null ,  $this->config->get('enable_profilebingo') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Enable Gravatar' ); ?>::<?php echo JText::_('If Yes, Gravatar will be displayed if profile image is not found'); ?>">
				<?php echo JText::_( 'Enable Gravatar' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[profile_enable_gravatar]' , null ,  $this->config->get('profile_enable_gravatar') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Enable User Image' ); ?>::<?php echo JText::_('If Yes, Profile Image will be displayed'); ?>">
				<?php echo JText::_( 'Enable User Image' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[profile_enable_image]' , null ,  $this->config->get('profile_enable_image') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Convert To' ); ?>::<?php echo JText::_('Convert Images to'); ?>">
				<?php echo JText::_( 'Convert Thumbnails to' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.genericlist',  $convertto, 'config[profile_convertto]', 'class="inputbox"', 'id', 'title',  $this->config->get('profile_convertto') );;?>
			</td>
		</tr>
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Width' ); ?>::<?php echo JText::_('Width of Small thumbnail'); ?>">
				<?php echo JText::_( 'Width' ); ?> </span></td>
			<td valign="top"><input type="text"
				name="config[width_profile_image]"
				value="<?php echo $this->config->get('width_profile_image' );?>"
				size="10" /></td>
		</tr>
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Height' ); ?>::<?php echo JText::_('Height of Small thumbnail'); ?>">
				<?php echo JText::_( 'Height' ); ?> </span></td>
			<td valign="top"><input type="text"
				name="config[height_profile_image]"
				value="<?php echo $this->config->get('height_profile_image' );?>"
				size="10" /></td>
		</tr>


		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Resize/Crop in proportion to width' ); ?>::<?php echo JText::_('Resize/Crop in proportoin to width'); ?>">
				<?php echo JText::_( 'Resize/Crop in proportion to width' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[x_profile_image]' , 1 ,  $this->config->get('x_profile_image') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>

		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Resize/Crop in proportion to Height' ); ?>::<?php echo JText::_('Resize/Crop in proportoin to Height'); ?>">
				<?php echo JText::_( 'Resize/Crop in proportion to height' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[y_profile_image]' , 1 ,  $this->config->get('y_profile_image') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>
		
		
			<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Crop' ); ?>::<?php echo JText::_('Crop Image for thumbnail'); ?>">
				<?php echo JText::_( 'Crop' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[crop_profile_image]' , 1 ,  $this->config->get('crop_profile_image') , JText::_('Yes') , JText::_('No') ); ?></td>
		</tr>
		
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Crop from' ); ?>::<?php echo JText::_('Crop From'); ?>">
				<?php echo JText::_( 'Crop Thumbnail from' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.genericlist',  $cropfrom, 'config[profile_cropfrom_sml]', 'class="inputbox"', 'id', 'title',  $this->config->get('profile_cropfrom_sml') );;?>
			</td>
		</tr>
		
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Suffix' ); ?>::<?php echo JText::_('Suffix of Small thumbnail'); ?>">
				<?php echo JText::_( 'Suffix' ); ?> </span></td>
			<td valign="top"><input type="text"
				name="config[suffix_profile_image]"
				value="<?php echo $this->config->get('suffix_profile_image' );?>"
				size="10" /></td>
		</tr>
		
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Default Noimage' ); ?>::<?php echo JText::_('Set Default noimage to this path'); ?>">
				<?php echo JText::_( 'Deafult Noimage Path' ); ?> </span></td>
			<td valign="top"><input type="text" name="config[path_default_profile_noimage]"
				value="<?php echo $this->config->get('path_default_profile_noimage' );?>"
				size="45" /></td>
		</tr>
		

		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Logo Path' ); ?>::<?php echo JText::_('Save Image to this path'); ?>">
				<?php echo JText::_( 'Image Path' ); ?> </span></td>
			<td valign="top"><input type="text" name="config[path_profile_image]"
				value="<?php echo $this->config->get('path_profile_image' );?>"
				size="45" /></td>
		</tr>
		
		<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Show Contact Information in Profile' ); ?>::<?php echo JText::_('If Yes, contact information is visible in profile page'); ?>">
				<?php echo JText::_( 'Show Contact Information in Profile ' ); ?> </span></td>
			<td valign="top"><?php echo JHTML::_('select.booleanlist' , 'config[show_profile_contact_information]' , null ,  $this->config->get('show_profile_contact_information') , JText::_('Yes') , JText::_('No') ); ?>
			</td>
		</tr>

<tr>
			<td class="key"><span class="hasTip"
				title="<?php echo JText::_( 'Login link' ); ?>::<?php echo JText::_('Login link for 3rd party custom login. Leave blank if you want to use default Joomla Login'); ?>">
				<?php echo JText::_( 'Custom Login link (non SEF url)' ); ?> </span></td>
			<td valign="top"><input type="text"
				name="config[lb_custom_login]"
				value="<?php echo $this->config->get('lb_custom_login' );?>"
				size="45" /><br/><em>e.g. index.php?option=com_profilebingo&amp;task=user.login</em></td>
		</tr>

	</tbody>
</table>
</fieldset>
