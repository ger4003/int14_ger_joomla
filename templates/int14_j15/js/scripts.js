/* scripts for template */


$(function($) {
	// **************
	// * scrollable *
	// **************
	var scrollable_config = {};
	var navigator_config = {
			navi: 'ul.navi-items',
			naviItem: 'li.navi-item'
		};
	
	// frontpage content items
	$("#fp_content_inner .scrollable").scrollable(
		scrollable_config
	).navigator(
		navigator_config
	);
	
	// frontpage modules
	$("#fp_modules .scrollable").scrollable({
		keyboard: false,
		circular: true,
		next: '.next-tab',
		prev: '.prev-tab',
	}).navigator(
		navigator_config
	);
	
	// ***************
	// * flyout navi *
	// ***************
	$("ul.sf-menu").superfish();
	
});
jQuery.noConflict();
