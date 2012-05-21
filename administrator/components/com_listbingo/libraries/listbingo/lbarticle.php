<?php
/*
 * @author:Bruce
 * Loads article for select
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();


class JElementLbArticle extends JElement {
	/**
	 * Element name
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'lbarticle';
	
	function fetchElement($name, $value, &$node, $control_name) {
		global $mainframe;
		
		$db = & JFactory::getDBO ();
		$doc = & JFactory::getDocument ();
		$template = $mainframe->getTemplate ();
		$fieldName = $control_name . '[' . $name . ']';
		$article = & JTable::getInstance ( 'content' );
		if ($value) {
			$article->load ( $value );
		} else {
			$article->title = JText::_ ( 'Select an Article' );
		}
		
		$js = "
		
		window.addEvent( 'domready', function()
		{
			$$('.lbarticle_remove').addEvent('click', function(){				
				
				var lbarticle_array = (this.id).split('-');
				var lbarticle_element = lbarticle_array[1];				
				
				$(lbarticle_element + '_name').setProperty('value', '" . JText::_ ( 'Select an article' ) . "');
				$(lbarticle_element + '_id').setProperty('value', '0');
			});
		});
		
		function jSelectArticle(id, title, object) {
			
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
			
		}";
		$doc->addScriptDeclaration ( $js );
		
		//$link = 'index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;object='.$name;
		$link = JRoute::_('index.php?option=com_listbingo&task=articles.showArticles&tmpl=component&object='.$name,false);
		
		JHTML::_ ( 'behavior.modal', 'a.modal' );
		$html = "\n" . '<div style="float: left;"><input style="background: #ffffff;" type="text" id="' . $name . '_name" value="' . htmlspecialchars ( $article->title, ENT_QUOTES, 'UTF-8' ) . '" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="' . JText::_ ( 'Select an Article' ) . '"  href="' . $link . '" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">' . JText::_ ( 'Select' ) . '</a></div></div>' . "\n";
		$html .= "\n" . '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . ( int ) $value . '" />';
		$html .= "<div class=\"button2-left\"><div class=\"blank\"><a id='lbremove-".$name."' class=\"lbarticle_remove\" title=\"" . JText::_ ( 'Remove article' ) . "\"  style=\"cursor:pointer\"\">" . JText::_ ( 'Remove article' ) . "</a></div></div>\n";
		
		return $html;
	}
	
}
?>