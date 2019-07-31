<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined('_JEXEC') or die;

class SppagebuilderAddonProgress_bar extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$class .= (isset($this->addon->settings->shape) && $this->addon->settings->shape) ? 'sppb-progress-' . $this->addon->settings->shape : '';
		$type = (isset($this->addon->settings->type) && $this->addon->settings->type) ? $this->addon->settings->type : 'sppb-progress-bar-default';
		$progress = (isset($this->addon->settings->progress) && $this->addon->settings->progress) ? $this->addon->settings->progress : '';
		$text = (isset($this->addon->settings->text) && $this->addon->settings->text) ? $this->addon->settings->text : '';
		$stripped = (isset($this->addon->settings->stripped) && $this->addon->settings->stripped) ? $this->addon->settings->stripped : '';
		$active = (isset($this->addon->settings->active) && $this->addon->settings->active) ? $this->addon->settings->active : '';
		
			
		//Output
		$output = '';
		
		if($type == 'flex') {
			$output .= '<div class="flex-text-wrapper"><div class="flex-text">'.  $text .'</div>';
			$output .= '<div class="flex-progress-text">'. (int) $progress .'%</div>';
			$output .= '</div>';
		} 
		
		$output .= '<div class="sppb-progress ' . $class . '">';
		$output .= '<div class="sppb-progress-bar ' . $type . ' ' . $stripped . ' ' . $active . '" role="progressbar" aria-valuenow="' . (int) $progress . '" aria-valuemin="0" aria-valuemax="100" data-width="' . (int) $progress . '%">';
		
		if($type != 'flex') {
			$output .= ($text) ? $text : '';
		}
		
		$output .= '</div>';
		$output .= '</div>';

		return $output;
		
		
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$custom_height = (isset($this->addon->settings->custom_height) && $this->addon->settings->custom_height) ? $this->addon->settings->custom_height : 0;
		$type = (isset($this->addon->settings->type) && $this->addon->settings->type) ? $this->addon->settings->type : 'sppb-progress-bar-default';
		$bar_background = (isset($this->addon->settings->bar_background) && $this->addon->settings->bar_background) ? $this->addon->settings->bar_background : '';
		$custom_color = (isset($this->addon->settings->custom_color) && $this->addon->settings->custom_color) ? $this->addon->settings->custom_color : '';
		$text_color = (isset($this->addon->settings->text_color) && $this->addon->settings->text_color) ? $this->addon->settings->text_color : '';
		
		$animation_duration = (isset($this->addon->settings->animation_duration) && $this->addon->settings->animation_duration) ? $this->addon->settings->animation_duration : 0;
		$animation_delay = (isset($this->addon->settings->animation_delay) && $this->addon->settings->animation_delay) ? $this->addon->settings->animation_delay : 0;
		
		$animation_duration != '' ? $animation_duration : $animation_duration = '2';
		$animation_delay != '' ? $animation_delay = ' '.$animation_delay . 's' : $animation_delay = '';

		$css = '';
		if($custom_height) {
			$css .= $addon_id . ' .sppb-progress {height: '. $custom_height .'px;}';
			$css .= $addon_id . ' .sppb-progress-bar {line-height: '. $custom_height .'px;}';
		}
		
		if($animation_duration && $animation_delay) {
			$css .= $addon_id . ' .sppb-progress-bar{'
				. '-webkit-transition:' . $animation_duration . 's ease'.$animation_delay.';'
				. 'transition:' . $animation_duration . 's ease'.$animation_delay.';'
				. '}';
		}

		if($type == 'flex') {
			$css .= $addon_id . ' .flex-text-wrapper {color: '. $text_color .';}';		
		} else {
			$css .= $addon_id . ' .sppb-progress-bar {color: '. $text_color .';}';
		}
		
		if($bar_background) {
			$css .= $addon_id . ' .sppb-progress {background-color: '. $bar_background .';}';
		}

		if($custom_color) {
			$css .= $addon_id . ' .sppb-progress-bar {background-color: '. $custom_color .';}';
		}

		return $css;

	}

}
