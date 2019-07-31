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

class SppagebuilderAddonAnimated_number extends SppagebuilderAddons{

	public function render() {

		$settings = $this->addon->settings;
		$number = (isset($settings->number) && $settings->number) ? $settings->number : 0;
		$number_addtext = (isset($settings->number_addtext) && $settings->number_addtext) ? $settings->number_addtext : '';
		$duration = (isset($settings->duration) && $settings->duration) ? $settings->duration : 0;
		
		$counter_title = (isset($settings->counter_title) && $settings->counter_title) ? $settings->counter_title : '';
		$alignment = (isset($settings->alignment) && $settings->alignment) ? $settings->alignment : '';
		
		$font_size = (isset($settings->font_size) && $settings->font_size) ? $settings->font_size : '';
		$color = (isset($settings->color) && $settings->color) ? $settings->color : '';
		$title_font_size = (isset($settings->title_font_size) && $settings->title_font_size) ? $settings->title_font_size : '';
		$counter_color = (isset($settings->counter_color) && $settings->counter_color) ? $settings->counter_color : '';
		$background = (isset($settings->background) && $settings->background) ? $settings->background : '';
		$border_color = (isset($settings->border_color) && $settings->border_color) ? $settings->border_color : '';
		$border_width = (isset($settings->border_width) && $settings->border_width) ? $settings->border_width : '';
		$border_radius = (isset($settings->border_radius) && $settings->border_radius) ? $settings->border_radius : '';
		
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';

		// Styling
		$style = '';
		$color = '';
		$number_style = '';
		$text_style 	= '';
	
		if($background) $class .= $class . ' sppb-hasbg';
	
		if($background) $style .= 'background-color:' . $background  . ';';
		if($border_color) $style .= 'border-style:solid;border-color:' . $border_color  . ';';
		
		$border_width != '' ? $padding = 'padding:20px;' : $padding = '';
		$class == 'shadow' ? $shadow = 'box-shadow:0 0 15px rgba(0,0,0,0.2);text-shadow: 1px 2px 2px rgba(0,0,0,0.2);padding:20px;' : $shadow = '';
		$class == 'padding' ? $paddingbox = 'padding:20px;' : $paddingbox = '';
		
		if($border_width) $style .= 'border-width:' . (int) $border_width  . 'px;';
		if($border_radius) $style .= 'border-radius:' . (int) $border_radius  . 'px;';
		
		$number_addtext != '' ? $number_addtext = '<span class="number_addtext">'. $number_addtext .'</span>' : $number_addtext = '';

		$output  = '<div class="sppb-addon sppb-addon-animated-number '. $alignment . ' ' . $class .'">';
		
		$output .= '<div class="sppb-addon-content" style="' . $paddingbox . $padding . $style . $shadow . '">';
		$output .= '<span class="sppb-animated-number" data-digit="'. $number .'" data-duration="' . $duration . '">0</span>'. $number_addtext .'';
		if($counter_title) $output .= '<div class="sppb-animated-number-title">' . $counter_title . '</div>';
		$output .= '</div>';
	
		$output .= '</div>';
		
		return $output;
	}


	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$settings = $this->addon->settings;
		//Number Style
		$number_style = '';
		$number_style .= (isset($settings->color) && $settings->color) ? 'color:' . $settings->color  . ';' : '';
		$number_style .= (isset($settings->font_size) && $settings->font_size) ? 'font-size:' . (int) $settings->font_size . 'px;' : '';
		$number_style .= (isset($settings->line_height) && $settings->line_height) ? 'line-height:' . (int) $settings->line_height . 'px;' : '';
		$number_style .= (isset($settings->number_font_weight) && $settings->number_font_weight) ? 'font-weight:' . (int) $settings->number_font_weight . ';' : '';
		//Number Tablet Style
		$number_style_sm  = '';
		$number_style_sm .= (isset($settings->font_size_sm) && $settings->font_size_sm) ? 'font-size:' . (int) $settings->font_size_sm . 'px;' : '';
		$number_style_sm .= (isset($settings->line_height_sm) && $settings->line_height_sm) ? 'line-height:' . (int) $settings->line_height_sm . 'px;' : '';
		//Number Mobile Style
		$number_style_xs  = '';
		$number_style_xs .= (isset($settings->font_size_xs) && $settings->font_size_xs) ? 'font-size:' . (int) $settings->font_size_xs . 'px;' : '';
		$number_style_xs .= (isset($settings->line_height) && $settings->line_height) ? 'line-height:' . (int) $settings->line_height . 'px;' : '';
		$number_addtext = (isset($settings->number_addtext) && $settings->number_addtext) ? $settings->number_addtext : '';	
		
		//Text Style
		$text_style = '';
		$text_style_sm = '';
		$text_style_xs = '';

		$text_style .= (isset($settings->title_font_size) && $settings->title_font_size) ? 'font-size:' . (int) $settings->title_font_size . 'px;': '';
		$text_style .= (isset($settings->title_line_height) && $settings->title_line_height) ? 'line-height:' . (int) $settings->title_line_height . 'px;': '';
		// $text_style .= (isset($settings->title_color) && $settings->title_color) ? 'color:' . $settings->title_color . ';': '';
		$text_style .= (isset($settings->counter_color) && $settings->counter_color) ? 'color:' . $settings->counter_color . ';': '';
		$text_style .= (isset($settings->title_margin) && $settings->title_margin) ? 'margin:' . $settings->title_margin . ';': '';
		//Title Font Style
		$title_fontstyle = (isset($settings->title_fontstyle) && $settings->title_fontstyle) ? $settings->title_fontstyle : '';
		if(isset($title_fontstyle->underline) && $title_fontstyle->underline){
			$text_style .= 'text-decoration:underline;';
		}
		if(isset($title_fontstyle->italic) && $title_fontstyle->italic){
			$text_style .= 'font-style:italic;';
		}
		if(isset($title_fontstyle->uppercase) && $title_fontstyle->uppercase){
			$text_style .= 'text-transform:uppercase;';
		}
		if(isset($title_fontstyle->weight) && $title_fontstyle->weight){
			$text_style .= 'font-weight:'.$title_fontstyle->weight.';';
		}
		$text_style_sm = '';
		$text_style_xs = '';

		//Title tablet style
		$text_style_sm .= (isset($settings->title_font_size_sm) && $settings->title_font_size_sm) ? 'font-size:' . (int) $settings->title_font_size_sm . 'px;': '';
		$text_style_sm .= (isset($settings->title_line_height_sm) && $settings->title_line_height_sm) ? 'line-height:' . (int) $settings->title_line_height_sm . 'px;': '';
		$text_style_sm .= (isset($settings->title_margin_sm) && $settings->title_margin_sm) ? 'margin:' . $settings->title_margin_sm . ';' : '';
		//Title mobile style
		$text_style_xs .= (isset($settings->title_font_size_xs) && $settings->title_font_size_xs) ? 'font-size:' . (int) $settings->title_font_size_xs . 'px;': '';
		$text_style_xs .= (isset($settings->title_line_height_xs) && $settings->title_line_height_xs) ? 'line-height:' . (int) $settings->title_line_height_xs . 'px;': '';
		$text_style_xs .= (isset($settings->title_margin_xs) && $settings->title_margin_xs) ? 'margin:' . $settings->title_margin_xs . ';' : '';

		$css = '';

		if($number_style) {
			$css .= $addon_id . ' .sppb-animated-number{';
			$css .= $number_style;
			$css .= '}';
		}

		if($text_style) {
			$css .= $addon_id . ' .sppb-animated-number-title{';
			$css .= $text_style;
			$css .= '}';
		}
	
		if($number_addtext != '') {
			$css .= $addon_id . ' .number_addtext{';
			$css .= $number_style;
			$css .= '}';
		}
	

		$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			if($number_style_sm) {
				$css .= $addon_id . ' .sppb-animated-number {';
					$css .= $number_style_sm;
				$css .= '}';
			}

			if($text_style_sm) {
				$css .= $addon_id . ' .sppb-animated-number-title {';
					$css .= $text_style_sm;
				$css .= '}';
			}
		$css .= '}';

		$css .= '@media (max-width: 767px) {';
			if($number_style_xs) {
				$css .= $addon_id . ' .sppb-animated-number {';
					$css .= $number_style_xs;
				$css .= '}';
			}

			if($text_style_xs) {
				$css .= $addon_id . ' .sppb-animated-number-title {';
					$css .= $text_style_xs;
				$css .= '}';
			}
		$css .= '}';

		return $css;
	}
public static function getTemplate(){
		$output = '
		<#
			var addonId = "sppb-addon-"+data.id;
			var number_position = (!_.isEmpty(data.number_position) && data.number_position) ? "animated-number-position-"+data.number_position : "";
		#>
		<style type="text/css">
			#{{ addonId }} .sppb-animated-number{
				color: {{ data.color }};
				font-weight: {{ data.number_font_weight }};
				font-family: {{ data.number_font_family }};
				<# if(_.isObject(data.font_size)){ #>
					font-size: {{ data.font_size.md }}px;
				<# } else { #>
					font-size: {{ data.font_size }}px;
				<# }
				if(_.isObject(data.line_height)){ #>
					line-height: {{ data.line_height.md }}px;
				<# } else { #>
					line-height: {{ data.line_height }}px;
				<# } #>
			}
			#{{ addonId }} .number_addtext{
				color: {{ data.color }};
				font-weight: {{ data.number_font_weight }};
				font-family: {{ data.number_addtext_font_family }};
				<# if(_.isObject(data.font_size)){ #>
					font-size: {{ data.font_size.md }}px;
				<# } else { #>
					font-size: {{ data.font_size }}px;
				<# }
				if(_.isObject(data.line_height)){ #>
					line-height: {{ data.line_height.md }}px;
				<# } else { #>
					line-height: {{ data.line_height }}px;
				<# } #>
			}
			#{{ addonId }} .sppb-animated-number-title{
				color: {{ data.counter_color }};
				<# if(_.isObject(data.title_font_size)){ #>
					font-size: {{ data.title_font_size.md }}px;
				<# } else { #>
					font-size: {{ data.title_font_size }}px;
				<# }
				if(_.isObject(data.title_line_height)){ #>
					line-height: {{ data.title_line_height.md }}px;
				<# } else { #>
					line-height: {{ data.title_line_height }}px;
				<# }
				if(_.isObject(data.title_margin)){ #>
					margin: {{ data.title_margin.md }};
				<# }
				if(_.isObject(data.title_fontstyle)){ #>
					<# if(data.title_fontstyle.underline){ #>
						text-decoration:underline;
					<# }
					if(data.title_fontstyle.italic){
					#>
						font-style:italic;
					<# }
					if(data.title_fontstyle.uppercase){
					#>
						text-transform:uppercase;
					<# }
					if(data.title_fontstyle.weight){
					#>
						font-weight:{{data.title_fontstyle.weight}};
					<# }
				} #>
			}
			@media (min-width: 768px) and (max-width: 991px) {
				#{{ addonId }} .sppb-animated-number{
					<# if(_.isObject(data.font_size)){ #>
						font-size: {{ data.font_size.sm }}px;
					<# }
					if(_.isObject(data.line_height)){ #>
						line-height: {{ data.line_height.sm }}px;
					<# } #>
				}
				#{{ addonId }} .sppb-animated-number-title{
					<# if(_.isObject(data.title_font_size)){ #>
						font-size: {{ data.title_font_size.sm }}px;
					<# }
					if(_.isObject(data.title_line_height)){ #>
						line-height: {{ data.title_line_height.sm }}px;
					<# }
					if(_.isObject(data.title_margin)){ #>
						margin: {{ data.title_margin.sm }};
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#{{ addonId }} .sppb-animated-number{
					<# if(_.isObject(data.font_size)){ #>
						font-size: {{ data.font_size.xs }}px;
					<# }
					if(_.isObject(data.line_height)){ #>
						line-height: {{ data.line_height.xs }}px;
					<# } #>
				}
				#{{ addonId }} .sppb-animated-number-title{
					<# if(_.isObject(data.title_font_size)){ #>
						font-size: {{ data.title_font_size.xs }}px;
					<# }
					if(_.isObject(data.title_line_height)){ #>
						line-height: {{ data.title_line_height.xs }}px;
					<# }
					if(_.isObject(data.title_margin)){ #>
						margin: {{ data.title_margin.xs }};
					<# } #>
				}
			}
		</style>
		
		<div class="sppb-addon sppb-addon-animated-number {{ data.alignment }} {{ data.class }}">
			<div class="sppb-addon-content">
				<span class="sppb-animated-number sp-inline-editable-element" data-id={{data.id}} data-fieldName="number" contenteditable="true" data-digit="{{ data.number }}" data-duration="{{ data.duration }}">0</span>
				<# if(data.number_addtext){ #>
					<span class="number_addtext">{{ data.number_addtext }}</span>
				<# } #>
				<# if(data.counter_title){ #>
					<div class="sppb-animated-number-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="counter_title" contenteditable="true">{{ data.counter_title }}</div>
				<# } #>
			</div>
		</div>';

		return $output;
	}
}
