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

class SppagebuilderAddonPie_progress extends SppagebuilderAddons{

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h4';

		//Options
		$percentage = (isset($this->addon->settings->percentage) && $this->addon->settings->percentage) ? $this->addon->settings->percentage : '';
		$duration = (isset($this->addon->settings->duration) && $this->addon->settings->duration) ? $this->addon->settings->duration : '';
		$border_color = (isset($this->addon->settings->border_color) && $this->addon->settings->border_color) ? $this->addon->settings->border_color : '#eeeeee';
		

		$border_active_color = (isset($this->addon->settings->border_active_color) && $this->addon->settings->border_active_color) ? $this->addon->settings->border_active_color : '';
		
		$border_width = (isset($this->addon->settings->border_width) && $this->addon->settings->border_width) ? $this->addon->settings->border_width : '';
		$size = (isset($this->addon->settings->size) && $this->addon->settings->size) ? $this->addon->settings->size : '';
		// Pixeden icons
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		$icon_name = (isset($this->addon->settings->icon_name) && $this->addon->settings->icon_name) ? $this->addon->settings->icon_name : '';
		$icon_size = (isset($this->addon->settings->icon_size) && $this->addon->settings->icon_size) ? $this->addon->settings->icon_size : '32';
		$icon_color = (isset($this->addon->settings->icon_color) && $this->addon->settings->icon_color) ? $this->addon->settings->icon_color : '';
		$text = (isset($this->addon->settings->text) && $this->addon->settings->text) ? $this->addon->settings->text : '';
		
		
	$style = 'height:'. (int) $size .'px; width:'. (int) $size .'px;';

	$duration  != '' ? $duration = $duration.'000' : $duration = '2000';
	$icon_size  != '' ? $icon_size = 'font-size:'.$icon_size.'px;line-height:' . ( $icon_size + 1 ) .'px;width:'.$icon_size.'px;height:'.$icon_size.'px;' : $icon_size = 'font-size:32px;width:32px;line-height:31px;'; 

	if($border_active_color == '') {
		$progress_color = '#f14833';
	} else {
		$progress_color = '';
	}

	$output  = '<div class="sppb-addon sppb-addon-pie-progress '. $class .'">';
	$output .= '<div class="sppb-addon-content sppb-text-center">';
	$output .= '<div class="sppb-pie-chart" data-size="'. (int) $size .'" data-percent="'.$percentage.'" data-width="'.$border_width.'" data-barcolor="'.$border_active_color.$progress_color.'" data-animate="'.$duration.'" data-trackcolor="'.$border_color.'" style="'. $style .'">';

	if($icon_name || $peicon_name) {
		$output .= '<div class="sppb-chart-icon"><span>';
		if ($peicon_name) {
			$output .= '<i class="pe ' . $peicon_name . '" style="'. $icon_size .'color:'. $icon_color .';"></i>';
		}else{
			$output .= '<i class="fa ' . $icon_name . '" style="'. $icon_size .'color:'. $icon_color .';"></i>';
		}
		$output .= '</span></div>';
	} else {
		$output .= '<div class="sppb-chart-percent"><span style="color:'. $icon_color .';"></span></div>';
	}

	$output .= '</div>';
	$output .= ($title) ? '<div class="clearfix"></div><'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
	$output .= '<div class="sppb-addon-text">';
	$output .= $text;
	$output .= '</div>';

	$output .= '</div>';
	$output .= '</div>';

	return $output;
	}

	public function scripts() {
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$js[] = JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/pie_progress/js/jquery.easypiechart.min.js';
		return $js;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$css = '';
		$style = (isset($this->addon->settings->size) && $this->addon->settings->size) ? 'height: '. (int) $this->addon->settings->size .'px; width: '. (int) $this->addon->settings->size .'px;' : '';

		if($style) {
			$css .= $addon_id . ' .sppb-pie-chart {';
			$css .= $style;
			$css .= '}';
		}

		return $css;
	}

}
