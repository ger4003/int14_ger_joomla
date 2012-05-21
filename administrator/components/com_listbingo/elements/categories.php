<?php

// Check to ensure this file is within the rest of the framework
defined ( 'JPATH_BASE' ) or die ();

/**
 * Renders a category element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementCategories extends JElement {
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'Categories';
	
	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO ();
		
		//get all super administrator
		

		$cat_list = self::getTreeForSelect ();
		$categories = array ();
		$categories [] = JHTML::_ ( 'select.option', '0', JText::_ ( '-All Category-' ), 'value', 'text' );
		
		foreach ( $cat_list as $cat ) {
			
			$xtreename = str_replace ( ".", "", $cat->treename );
			$xtreename = str_replace ( "&nbsp;", "-", $xtreename );
			$xtreename = str_replace ( "<sup>|_</sup>", "", $xtreename );
			$categories [] = JHTML::_ ( 'select.option', $cat->value, JText::_ ( $xtreename ), 'value', 'text' );
		}
		
		return JHTML::_ ( 'select.genericlist', $categories, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value, $control_name . $name );
	}
	
	function getTreeForSelect() {
		$wheres = array ();
		$wheres [] = " c.published='1'";
		if (count ( $wheres ) > 0) {
			$cond = " WHERE " . implode ( " AND ", $wheres );
		}
		
		$orderby = ' ORDER BY c.parent_id asc,c.ordering';
		
		$db = JFactory::getDBO ();
		$query = "SELECT c.*, c.id as value,c.title as name,c.parent_id as parent from #__gbl_categories as c
		$cond $orderby";
		$db->setQuery ( $query );
		$rows = $db->loadObjectList ();
		$children = array ();
		// first pass - collect children
		foreach ( $rows as $v ) {
			$pt = $v->parent_id;
			$list = @$children [$pt] ? $children [$pt] : array ();
			array_push ( $list, $v );
			/*if($filter->id && $v->parent_id==$filter->parent_id && $filter->parent_id>=0)
			 {

			 }
			 else
			 {
				$children[$pt] = $list;
				}*/
			$children [$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = JHTML::_ ( 'menu.treerecurse', 0, '', array (), $children, 9999, 0, 0 );
		$newlist = array ();
		foreach ( $list as $item ) {
			
			$newlist [] = $item;
		
		}
		
		return $newlist;
	
	}
}
