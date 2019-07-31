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

class SppagebuilderAddonPricing extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$price = (isset($this->addon->settings->price) && $this->addon->settings->price) ? $this->addon->settings->price : '';
		$currency = (isset($this->addon->settings->currency) && $this->addon->settings->currency) ? $this->addon->settings->currency : '';
		$duration = (isset($this->addon->settings->duration) && $this->addon->settings->duration) ? $this->addon->settings->duration : '';
		$pricing_content = (isset($this->addon->settings->pricing_content) && $this->addon->settings->pricing_content) ? $this->addon->settings->pricing_content : '';
		$button_text = (isset($this->addon->settings->button_text) && $this->addon->settings->button_text) ? $this->addon->settings->button_text : '';
		$button_url = (isset($this->addon->settings->button_url) && $this->addon->settings->button_url) ? $this->addon->settings->button_url : '';
		$button_classes = (isset($this->addon->settings->button_size) && $this->addon->settings->button_size) ? ' sppb-btn-' . $this->addon->settings->button_size : '';
		$button_classes .= (isset($this->addon->settings->button_type) && $this->addon->settings->button_type) ? ' sppb-btn-' . $this->addon->settings->button_type : '';
		$button_classes .= (isset($this->addon->settings->button_shape) && $this->addon->settings->button_shape) ? ' sppb-btn-' . $this->addon->settings->button_shape: ' sppb-btn-rounded';
		$button_classes .= (isset($this->addon->settings->button_appearance) && $this->addon->settings->button_appearance) ? ' sppb-btn-' . $this->addon->settings->button_appearance : '';
		$button_classes .= (isset($this->addon->settings->button_block) && $this->addon->settings->button_block) ? ' ' . $this->addon->settings->button_block : '';
		$button_block = (isset($this->addon->settings->button_block ) && $this->addon->settings->button_block ) ? $this->addon->settings->button_block  : '';
		// Pixeden icons
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		$button_icon = (isset($this->addon->settings->button_icon) && $this->addon->settings->button_icon) ? $this->addon->settings->button_icon : '';
		$button_icon_position = (isset($this->addon->settings->button_icon_position) && $this->addon->settings->button_icon_position) ? $this->addon->settings->button_icon_position: 'left';
		$button_position = (isset($this->addon->settings->button_position) && $this->addon->settings->button_position) ? $this->addon->settings->button_position : '';
		$button_attribs = (isset($this->addon->settings->button_target) && $this->addon->settings->button_target) ? ' target="' . $this->addon->settings->button_target . '"' : '';
		$button_attribs .= (isset($this->addon->settings->button_url) && $this->addon->settings->button_url) ? ' href="' . $this->addon->settings->button_url . '"' : '';
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : '';
		$border_radius = (isset($this->addon->settings->border_radius) && $this->addon->settings->border_radius) ? $this->addon->settings->border_radius : '';
		$background = (isset($this->addon->settings->background) && $this->addon->settings->background) ? $this->addon->settings->background : '';
		$color = (isset($this->addon->settings->color) && $this->addon->settings->color) ? $this->addon->settings->color : '';
		$header_color = (isset($this->addon->settings->header_color) && $this->addon->settings->header_color) ? $this->addon->settings->header_color : '';
		$features_color = (isset($this->addon->settings->features_color) && $this->addon->settings->features_color) ? $this->addon->settings->features_color : '';
		
		$featured = (isset($this->addon->settings->featured) && $this->addon->settings->featured) ? $this->addon->settings->featured : '';
		
		$style = '';
		$table_border_radius = '';
		$header_border_radius = '';
		$duration_span = '';
		$header_style = '';
		$content_color = '';
		$currency_span = '';
		
		if($background) $style .= 'background-color:' . $background . ';border-color: ' . $background . ';';
		if($header_color) $header_style .= 'color:' . $header_color . ';';
		if($color) $content_color .= ' style="color:' . $color . ';"';
		if($border_radius != '') {
			$table_border_radius = 'border-radius:'.$border_radius.'px;';
			$header_border_radius = 'border-radius:'.$border_radius.'px '.$border_radius.'px 0 0;';
			if($class == 'flex') {
				$button_block != '' ? $button_block_radius = ' style="border-radius:0 0 '.$border_radius.'px '.$border_radius.'px;"' : '';
			}
		}
		
		if($class == 'flex') {
			$button_block != '' ? $button_block_padding = ' style="padding:0;"' : $button_block_padding = '';
		}
		
		if($button_icon_position == 'left') {
			if ($peicon_name != '') {
				$button_text = ($peicon_name) ? '<i class="pe ' . $peicon_name . '"></i> ' . $button_text : $button_text;
			}else{
				$button_text = ($button_icon) ? '<i class="fa ' . $button_icon . '"></i> ' . $button_text : $button_text;
			}
		} else {
			if ($peicon_name != '') {
				$button_text = ($peicon_name) ? $button_text . ' <i style="margin-left:7px;margin-right:-1px;" class="pe ' . $peicon_name . '"></i>' : $button_text;
			}else{
				$button_text = ($button_icon) ? $button_text . ' <i style="margin-left:5px;margin-right:-1px;" class="fa ' . $button_icon . '"></i>' : $button_text;
			}
		}

		$button_output = ($button_text) ? '<a' . $button_attribs . ' id="btn-'. $this->addon->id .'" class="sppb-btn' . $button_classes . '">' . $button_text . '</a>' : '';

		//Output
		$output  = '<div class="sppb-addon sppb-addon-pricing-table ' . $alignment . ' ' . $class . '">';
		
		$output .= '<div style="' . $style . $table_border_radius . '" class="sppb-pricing-box '. $featured .'">';
		$output .= '<div style="' . $header_border_radius . $header_style . '" class="sppb-pricing-header">';
	
		if($title) $output .= '<h1 class="sppb-pricing-title responsive">' . $title . '</h1>';
		
		if($currency) $currency_span = '<span class="sppb-pricing-currency">' . $currency . '</span>';
		if($duration) $duration_span = '<span class="sppb-pricing-duration">' . $duration . '</span>';
		if($price) $output .= '<h2 class="sppb-pricing-price">' . $currency_span . $price . $duration_span . '</h2>';
	
		$output .= '</div>';

		if($pricing_content) {
			$output .= '<div class="sppb-pricing-features">';
			$output .= '<ul'.$content_color.'>';

			$features = explode("\n", $pricing_content);

			foreach ($features as $feature) {
				$output .= '<li>' . $feature . '</li>';
			}

			$output .= '</ul>';
			$output .= '</div>';
		}

		$output .= '<div class="sppb-pricing-footer">';
		$output .= $button_output;
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$css = '';
		$style = (isset($this->addon->settings->global_background_color) && $this->addon->settings->global_background_color) ? 'border: 0; background-color: '. $this->addon->settings->global_background_color .';' : '';

		if($style) {
			$css .= $addon_id . ' .sppb-pricing-box {';
			$css .= $style;
			$css .= '}';
		}

		// Button css
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $this->addon->settings, 'id' => 'btn-' . $this->addon->id));

		return $css;
	}
}
