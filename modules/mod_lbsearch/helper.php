<?php

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class modLbSearchHelper {
	function getTreeForSelect($published = false, $filter = array()) {
		$wheres = array ();
		
		// If a not a new item, lets set the menu item id
		if ($filter->id) {
			$wheres [] = ' c.id != ' . ( int ) $filter->id;
		} else {
			$wheres [] = ' c.id != ' . ( int ) $filter->id;
		}
		
		if ($published) {
			$wheres [] = " c.published='1'";
		}
		
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
	
	function getCountryForSelect($published = false, $val = 'id', $text = 'title', $country=0) {
		
		$pubcond = "";
		
		$pubcond = "";
		if ($published) {
			$pubcond = " WHERE published='1'";
		}
		
		$orderby = ' ORDER BY  ordering,title';
		
		$db = JFactory::getDBO ();
		$query = "SELECT $val as value, $text as text FROM #__gbl_countries $pubcond $orderby";
		$db->setQuery ( $query );
		$countries2 = $db->loadAssocList ();
		
		$countries1 = array ();
		$countries1 [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'SELECT_COUNTRIES' ), 'value', 'text' );
		
		$countries = array_merge ( $countries1, $countries2 );
		return JHTML::_ ( 'select.genericlist', $countries, 'countries', 'class="inputbox" size="1"', 'value', 'text', $country );
	}

}
