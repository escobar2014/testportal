<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined('_JEXEC') or die;

AddonParser::addAddon('sp_icons','sp_icons_addon');
AddonParser::addAddon('sp_icons_item','sp_icons_item_addon');

$sppbIconsParam = array();

function sp_icons_addon($atts, $content){
	
	global $sppbIconsParam;

	extract(spAddonAtts(array(
		"title"					=> '',
		"title_tooltip"			=> '',
		"heading_selector" 		=> 'h3',
		"title_fontsize" 		=> '',
		"title_fontweight" 		=> '',
		"title_text_color" 		=> '',
		"title_margin_top" 		=> '',
		"title_margin_bottom" 	=> '',	
		"alignment"				=> '',
		"margin_gap" => '',
		"class" => '',
		), $atts));

	$output = '';
	$class != '' ? $icon_class = ' ' . $class . '"' : $icon_class = '';
	$icons_alignment = ' ' . $alignment . '';
	$sppbIconsParam['title_tooltip'] = $title_tooltip;
	$sppbIconsParam['margin_gap'] = $margin_gap;
	
	$output  = '<div class="sppb-addon sppb-addon-icons ' . $icons_alignment . $class . '">';

	if($title) {

		$title_style = '';
		if($title_margin_top !='') $title_style .= 'margin-top:' . (int) $title_margin_top . 'px;';
		if($title_margin_bottom !='') $title_style .= 'margin-bottom:' . (int) $title_margin_bottom . 'px;';
		if($title_text_color) $title_style .= 'color:' . $title_text_color  . ';';
		if($title_fontsize) $title_style .= 'font-size:'.$title_fontsize.'px;line-height:'.$title_fontsize.'px;';
		if($title_fontweight) $title_style .= 'font-weight:'.$title_fontweight.';';

		$output .= '<'.$heading_selector.' class="sppb-addon-title" style="' . $title_style . '">' . $title . '</'.$heading_selector.'>';
	}
	$output .= '<div class="flex-icons' . $icons_alignment . $icon_class . '">';
	$output .= AddonParser::spDoAddon($content);
	$output .= '</div>';
	$output .= '</div>';
	
	return $output;

}


function sp_icons_item_addon( $atts ){
	
	global $sppbIconsParam;

	extract(spAddonAtts(array(
		"title"=>'',
		"peicon_name" => '',
		"icon_name" => '',
		"size" => '',
		"font_weight" => '',
		"color" => '',
		"border_color" => '',
		"border_width" => '',
		"border_radius" => '',
		"background" => '',
		"icon_margin" => '',
		"padding" => '',
		"url"=>'',
		"url_target"=>'',
		), $atts));
		
	$style = '';
	$font_size = '';
	$extra_style = '';
	$icon_class = '';
	$margin_gap = '';
	$title_tooltip = '';
	$url != '' ? $icon_url = $url : $icon_url = '#';
	

	if($icon_name || $peicon_name) {

		if($icon_margin) $style .= 'margin:' . (int) $icon_margin . 'px;';
		
		if($padding) { 
			$style .= 'padding:' . (int) $padding  . 'px;';
		}
	
		$extra_style .= 'width:' . (int) $size . 'px;';
		$extra_style .= 'height:' . (int) $size . 'px;';
		$extra_style .= 'line-height:' . (int) $size . 'px;';

		$sppbIconsParam['margin_gap'] != '' ? $margin_gap = 'margin:0 '.$sppbIconsParam['margin_gap'].'px '.$sppbIconsParam['margin_gap'].'px 0;' : '';
		$sppbIconsParam['title_tooltip'] == '1' ? $title_tooltip = ' title="'.$title.'" data-toggle="tooltip"' : '';
		
		$url_target != '' ? $icon_url_target = ' target="' . $url_target . '"' : $icon_url_target = '';
		
		if($color) $style .= 'color:'. $color  . ';';
		if($font_weight) $font_weight = 'font-weight:' . $font_weight  . ';';
		if($background) $style .= 'background-color:' . $background  . ';';
		if($border_color) $style .= 'border-style:solid;border-color:' . $border_color  . ';';
		if($border_width) $style .= 'border-width:' . (int) $border_width  . 'px;';
		if($border_radius) $style .= 'border-radius:' . (int) $border_radius  . 'px;';

		if($size) $font_size .= 'font-size:' . (int) $size . 'px;' . $font_weight . $extra_style .'';

		$output = '<div'.$title_tooltip.' style="' . $margin_gap . '" class="flex-icon-wrap">';
		$output .= '<a' . $icon_url_target . ' href="' . $icon_url . '">';
		$output .= '<span style="' . $style . '"' . $icon_class . '>';
		if ($peicon_name) {
			$output .= '<i class="pe ' . $peicon_name . '" style="' . $font_size . '"></i>';
		}else{
			$output .= '<i class="fa ' . $icon_name . '" style="' . $font_size . '"></i>';
		}
		$output .= '</span>';
		$output .= '</a>';
		
		$output .= '</div>';

		return $output;
	}

	return;		

}
