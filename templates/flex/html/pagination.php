<?php
/**
 * Flex @package Helix3 Framework
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('restricted access');

function pagination_list_render($list) {
	// Initialize variables
	$html = '<nav role="pagination"><ul class="cd-pagination no-space animated-buttons custom-icons">';
	
	if ($list['start']['active']==1)   $html .= $list['start']['data'];
	if ($list['previous']['active']==1) $html .= $list['previous']['data'];

	foreach ($list['pages'] as $page) {
		$html .= $page['data'];
	}

	if ($list['next']['active']==1) $html .= $list['next']['data'];
	if ($list['end']['active']==1)  $html .= $list['end']['data'];

	$html .= '</ul></nav>';
	
	return $html;
}

function pagination_item_active(&$item) {
	
	$cls = "";
	$buttoncls = "";
	$buttoniconstart = "";
	$buttoniconend = "";
	
    if ($item->text == JText::_("Next")) { $item->text = '<i class="ap-right-2"></i>'; $cls = ' class="next"';}
    if ($item->text == JText::_("Prev")) { $item->text = '<i class="ap-left-2"></i>'; $cls = ' class="previous"';}
    
	if ($item->text == JText::_("First")) { $cls = ' class="first"';}
    if ($item->text == JText::_("Last")) { $cls = ' class="last"';}
	
	if ($item->text == JText::_("Start") || $item->text == JText::_("End")) { $buttoniconstart = '<i>'; $buttoniconend = '</i>';}
	
	if ($item->text == JText::_("Start")) { $buttoncls = ' class="button btn-previous"';}
    if ($item->text == JText::_("End")) { $buttoncls = ' class="button btn-next"';}
	
    return '<li' . $buttoncls . '><a' . $cls . ' href="' . $item->link . '">' . $buttoniconstart . $item->text . $buttoniconend . '</a></li>';
}

function pagination_item_inactive( &$item ) {
	$cls = (int)$item->text > 0 ? 'active': 'disabled';
	return '<li class="' . $cls . '"><a>' . $item->text . '</a></li>';
}
