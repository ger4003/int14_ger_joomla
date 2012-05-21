<?php
function modChrome_sidebar( $module, &$params, &$attribs ) 
{
	/* TITLE */
	$title = "";
	if($module->showtitle) {
		$clean_title = replaceMarker($module->title);
		$title = "<h4>".$clean_title."</h4>";
	}
	
	/* CONTENT */
	$content = 	"<div class='mod_content_wrap'>" . 
					$module->content . 
				"</div>";
	
	/* OUTPUT */
	$output = 	"<div id='mod_".$module->id."' class='mod_wrap ".$params->get( 'moduleclass_sfx' )."'>" . 
					$title . 
					$content . 
			  	"</div>";
	
	echo $output;
}

function modChrome_frontpage( $module, &$params, &$attribs ) 
{
	/* TITLE */
	$title = "";
	if($module->showtitle) {
		$title = "<h2>".$module->title."</h2>";
	}
	
	
	/* CONTENT */
	$content = 	"<div class='mod_content_wrap'>" . 
					$module->content . 
				"</div>";
	
	/* OUTPUT */
	$output = 	"<div id='mod_".$module->id."' class='mod_wrap ".$params->get( 'moduleclass_sfx' )."'>" . 
					$title . 
					$content . 
			  	"</div>";
	
	echo $output;
}

function modChrome_frontpage_half( $module, &$params, &$attribs ) 
{
	/* TITLE */
	$title = "";
	if($module->showtitle) {
		$title = "<h2>".$module->title."</h2>";
	}
	
	/* CONTENT */
	$content = 	"<div class='mod_content_wrap'>" . 
					$module->content . 
				"</div>";
	
	/* OUTPUT */
	$output = 	"<div id='mod_".$module->id."' class='mod_wrap ".$params->get( 'moduleclass_sfx' )."'>" . 
					$title . 
					$content . 
			  	"</div>";
	
	echo $output;
}

function modChrome_flyout( $module, &$params, &$attribs ) 
{
	/* CONTENT */
	$content = 	$module->content;
	
	/* OUTPUT */
	$output = 	"<div class='menu_wrap".$params->get( 'moduleclass_sfx' )."'>" . 
					$content . 
			  	"</div>" .
			  	"<div class='clear'><!-- --></div>";
	
	echo $output;
}


/**
 * Replace markes in article view, category view and section view
 * Markers consists of contenttype and database field.  
 * For example %section_title%, %category_description%, %content_introtext%
 * 
 * @param $subject in which the marker will be replaced
 */
function replaceMarker($subject) {
	preg_match_all('/%([\w]+)%/', $subject, $matches);
	
	foreach($matches[1] as $marker) {
		$markerSplit = explode('_', $marker);
		$dbItem = getCurrentDBItem($markerSplit[0]);	
		$subject = preg_replace('/%'.$marker.'%/', $dbItem[$markerSplit[1]], $subject);
	}
	return $subject;
}

/**
 * Recieve the current item from database
 * 
 * @param String content|category|section
 */
function getCurrentDBItem($type) {
	$db =& JFactory::getDBO();
	$view = JRequest::getVar('view');
	$id = JRequest::getVar('id');

	switch($view) {
		case 'article':
			$values = explode(':', $id);
			$id = $values[0];
			
			$query_content = "SELECT * FROM #__content WHERE id = $id";
			$db->setQuery($query_content);
			$article = $db->loadAssoc();
			
			switch($type) {
				case 'content':
					return $result;
				case 'category':
					$query = "SELECT * FROM #__categories WHERE id = ".$article['catid'];
					break;
				case 'section':
					$query = "SELECT * FROM #__sections WHERE id = ".$article['sectionid'];
					break;
			}
			
			$db->setQuery($query);
			$result = $db->loadAssoc();
			return $result;
			
		case 'category':
			$query_category = "SELECT * FROM #__categories WHERE id = $id";
			$db->setQuery($query_category);
			$category = $db->loadAssoc();
			
			switch($type) {
				case 'category':
					return $category;
				case 'section':
					$query = "SELECT * FROM #__sections WHERE id = ".$category['section'];
					$db->setQuery($query);
					$result = $db->loadAssoc();
					return $result;
			}
			break;

		case 'section':
			$query_section = "SELECT * FROM #__sections WHERE id = $id";
			$db->setQuery($query_section);
			$section = $db->loadAssoc();
			
			return $section;
	}
	
	return $result;
}

?>