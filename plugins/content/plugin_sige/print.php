<?php
/*
// "Simple Image Gallery" Extended Plugin Joomla 1.5 - Version 1.5-14
// License: http://www.gnu.org/copyleft/gpl.html
// Author: Viktor Vogel
// Projectsite: http://joomla-extensions.kubik-rubik.de/sige-simple-image-gallery-extended
// Based on: Simple Image Gallery - www.joomlaworks.gr
//
// @license GNU/GPL
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

	if($_GET['img'] == "") {
		exit("Keine Parameter!");
	}

	if ($_GET['img'] != '') {
		//$_GET['img'] = str_replace( '..', '', urldecode( $_GET['img'] ) );
		$_image_ = rawurldecode($_GET['img']);
		$_name_ = rawurldecode($_GET['name']);
		if (isset($_GET['caption']))
		{
			$_caption_ = rawurldecode($_GET['caption']);
		}
		else
		{
			$_caption_ = '';
		}
		
		$timestamp = time();
		// $uhrzeit = date("H:i",$timestamp);
		$datum = date("d.m.Y - H:i",$timestamp);
	?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta http-equiv="expires" content="0" />
			<meta http-equiv="cache-control" content="no-cache" />
			<title><?php echo $_name_ ?> - Simple Image Gallery Extended</title>
		</head>
		<body onload="window.print();">
		<div style="text-align:center">
		<p>
		<?php echo '<strong>'.$_name_.'</strong>';
			if ($_caption_ != '') {
				echo '<br />'.$_caption_;  
			}
			?>
		</p>
		<p><img src="<?php echo $_image_ ?>" alt="<?php echo $_name_ ?>" title="<?php echo $_name_ ?>" /></p>
		<p style="font-size: small"><?php echo $datum ?><br />Powered by Simple Image Gallery Extended - Kubik-Rubik.de</p>
		</div>
		</body></html>
	<?php 
	} 
?>