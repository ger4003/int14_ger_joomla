<?php
/**
 * Joomla! 1.5 component LISTBINGO
 *
 * @version $Id: ads.php 2010-01-10 00:57:37 svn $
 * @author gobingoo.com
 * @package Joomla
 * @subpackage LISTBINGO
 * @license GNU/GPL
 *
 * A classified ad component from gobingoo.com.
 *
 * @code Bruce
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

gbimport ( "gobingoo.controller" );

class ListbingoControllerUpload extends GController {
	
	function test() {
		$images = array ();
		$basepath = JPATH_ROOT . DS . "images" . DS . "uploadtest" . DS;
		
		echo $images [] = $basepath . "original" . DS . "green.jpg";
		$images [] = $basepath . "original" . DS . "leaves.jpg";
		$images [] = $basepath . "original" . DS . "oryx.jpg";
		$images [] = $basepath . "original" . DS . "toco.jpg";
		$images [] = $basepath . "original" . DS . "whale.jpg";
		
		$configmodel = gbimport ( "listbingo.model.configuration" );
		$params = $configmodel->getParams ();
		
		gbimport ( "gobingoo.upload" );
		$uploader = new GUpload ();
		
		$uploader->setFolderPermission ( $params );
		//$params->get ( 'maxuploadsize', 204800 );
		$uploader->setMaxFileSize ( $params->get ( 'maxuploadsize', 5 ) ); // default size is 200 KB
		

		$returnvar = array ();
		
		$thumbparams = new stdClass ();
		$thumbparams->prefix = $params->get ( 'prefix' );
		$thumbparams->saveoriginal = $params->get ( 'saveoriginal' );
		$thumbparams->convert = $params->get ( 'convertto' );
		
		$thumbparams->uploadfolder = $basepath . "cropped";
		
		$thumbnails = array ();
		$ratio = $params->get ( 'saveproportion' );
		
		if ($params->get ( 'enable_thumbnail_mid' )) {
			$thumbnail = new stdClass ();
			$thumbnail->suffix = $params->get ( 'suffix_thumbnail_mid' );
			$thumbnail->width = $params->get ( 'width_thumbnail_mid' );
			$thumbnail->height = $params->get ( 'height_thumbnail_mid' );
			$thumbnail->y = $params->get ( 'y_thumbnail_mid' );
			$thumbnail->x = $params->get ( 'x_thumbnail_mid' );
			$thumbnail->ratio = $ratio;
			$thumbnail->resize = $params->get ( 'resize_thumbnail_mid' );
			$thumbnail->crop = $params->get ( 'crop_thumbnail_mid' );
			$thumbnails [] = $thumbnail;
		}
		
		$thumbparams->thumbnails = $thumbnails;
		
		$returns = $uploader->thumbnailArray ( $images, $thumbparams );
	
		var_dump($returns);
	
	}

}

?>