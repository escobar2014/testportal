<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('restricted access');

class SppagebuilderAddonPrettyphoto_modal extends SppagebuilderAddons{

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);
		
		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$modal_selector = (isset($this->addon->settings->modal_selector) && $this->addon->settings->modal_selector) ? $this->addon->settings->modal_selector : '';
		$button_text = (isset($this->addon->settings->button_text) && $this->addon->settings->button_text) ? $this->addon->settings->button_text : '';
		$button_class = (isset($this->addon->settings->button_type) && $this->addon->settings->button_type) ? ' sppb-btn-' . $this->addon->settings->button_type : ' sppb-btn-default';
		$button_class .= (isset($this->addon->settings->button_size) && $this->addon->settings->button_size) ? ' sppb-btn-' . $this->addon->settings->button_size : '';
		$button_class .= (isset($this->addon->settings->button_shape) && $this->addon->settings->button_shape) ? ' sppb-btn-' . $this->addon->settings->button_shape: ' sppb-btn-rounded';
		$button_class .= (isset($this->addon->settings->button_appearance) && $this->addon->settings->button_appearance) ? ' sppb-btn-' . $this->addon->settings->button_appearance : '';
		$button_class .= (isset($this->addon->settings->button_block) && $this->addon->settings->button_block) ? ' ' . $this->addon->settings->button_block : '';
		//Pixeden Icons
		$button_peicon = (isset($this->addon->settings->button_peicon) && $this->addon->settings->button_peicon) ? $this->addon->settings->button_peicon : '';
		$button_icon = (isset($this->addon->settings->button_icon) && $this->addon->settings->button_icon) ? $this->addon->settings->button_icon : '';
		$button_icon_position = (isset($this->addon->settings->button_icon_position) && $this->addon->settings->button_icon_position) ? $this->addon->settings->button_icon_position: 'left';

		if($button_icon_position == 'left') {
			if ($button_peicon != '') {
				$button_text = ($button_peicon) ? '<i class="pe ' . $button_peicon . '"></i> ' . $button_text : $button_text;
			}else{
				$button_text = ($button_icon) ? '<i class="fa ' . $button_icon . '"></i> ' . $button_text : $button_text;
			}
		} else {
			if ($button_peicon != '') {
				$button_text = ($button_peicon) ? $button_text . ' <i style="margin-left:7px;margin-right:-1px;" class="pe ' . $button_peicon . '"></i>' : $button_text;
			}else{
				$button_text = ($button_icon) ? $button_text . ' <i style="margin-left:5px;margin-right:-1px;" class="fa ' . $button_icon . '"></i>' : $button_text;
			}
		}
		

		$selector_image = (isset($this->addon->settings->selector_image) && $this->addon->settings->selector_image) ? $this->addon->settings->selector_image : '';
		//Pixeden Icon
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		$selector_icon_name = (isset($this->addon->settings->selector_icon_name) && $this->addon->settings->selector_icon_name) ? $this->addon->settings->selector_icon_name : '';
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : '';
		$modal_unique_id = 'prettyphoto-modal-' . $this->addon->id;
		$modal_content_type = (isset($this->addon->settings->modal_content_type) && $this->addon->settings->modal_content_type) ? $this->addon->settings->modal_content_type : 'text';
		$modal_content_text = (isset($this->addon->settings->modal_content_text) && $this->addon->settings->modal_content_text) ? $this->addon->settings->modal_content_text : '';
		$modal_content_image = (isset($this->addon->settings->modal_content_image) && $this->addon->settings->modal_content_image) ? $this->addon->settings->modal_content_image : '';
		$modal_content_video_url = (isset($this->addon->settings->modal_content_video_url) && $this->addon->settings->modal_content_video_url) ? $this->addon->settings->modal_content_video_url : '';
		$modal_popup_width = (isset($this->addon->settings->modal_popup_width) && $this->addon->settings->modal_popup_width) ? $this->addon->settings->modal_popup_width : '';
		$modal_popup_height = (isset($this->addon->settings->modal_popup_height) && $this->addon->settings->modal_popup_height) ? $this->addon->settings->modal_popup_height : '';

		

		$output = '';

		if($modal_content_type == 'text') {
			$url = '#' . $modal_unique_id. '-content';
			$output .= '<div id="' . $modal_unique_id . '-content" style="display: none;">';
			$output .= '<div class="sppb-addon-modal-content clearfix">';
			$output .= $modal_content_text;
			$output .= '</div>';
			$output .= '</div>';
		} else if( $modal_content_type == 'video') {
			$url = $modal_content_video_url;
		} else {
			$url = $modal_content_image;
		}

		$output .= '<div class="' . $class . ' ' . $alignment . '">';

		if($modal_selector=='image') {
			
			if ($selector_image) {
				
				$output .= '<a class="sppb-modal-selector modal-selector-image" href="'. $url . '" id="'. $modal_unique_id .'">';
				// Image
				if(strpos($selector_image, 'http://') !== false || strpos($selector_image, 'https://') !== false){
					/* Lazyload for images with absolute URL */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $selector_image .'" alt="'.$modal_selector.'">';
					} else {
						$output .= '<img src="' . $selector_image . '" alt="'.$modal_selector.'">';	
					}
				} else {
					/* Lazyload for images for relative URL (local image) */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $selector_image .'" alt="'.$modal_selector.'">';
					} else {
						$output .= '<img src="' . $selector_image . '" alt="'.$modal_selector.'">';	
					}
				}
				$output .= '</a>';
			}
		
		
		} else if ($modal_selector=='icon') {
			
			if($selector_icon_name || $peicon_name) {
				$output  .= '<a class="sppb-modal-selector" href="'. $url . '" id="'. $modal_unique_id .'">';
				$output  .= '<span>';
				if ($peicon_name != '') {
					$output  .= '<i class="pe ' . $peicon_name . '"></i>';
				}else{
					$output  .= '<i class="fa ' . $selector_icon_name . '"></i>';
				}
				$output  .= '</span>';
				$output  .= '</a>';
			}
		} else {
			$output .= '<a class="sppb-btn ' . $button_class . ' sppb-modal-selector" href="'. $url . '" id="'. $modal_unique_id .'">'. $button_text .'</a>';	
		}

		$output .= '</div>';

		return $output;

	}

	public function scripts() {
		$app = JFactory::getApplication();
		return array(JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/prettyphoto_modal/js/jquery.prettyPhoto.js');
	}

	public function stylesheets() {
		$app = JFactory::getApplication();
		return array(JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/prettyphoto_modal/css/prettyPhoto.css');
	}
	
	public function js() {
		$modal_popup_width = (isset($this->addon->settings->modal_popup_width) && $this->addon->settings->modal_popup_width) ? $this->addon->settings->modal_popup_width : '';
		$modal_popup_height = (isset($this->addon->settings->modal_popup_height) && $this->addon->settings->modal_popup_height) ? $this->addon->settings->modal_popup_height : '';
		$js ='jQuery(function($){$(document).ready(function(){$("#prettyphoto-modal-'.$this->addon->id.'").prettyPhoto({social_tools:false,theme:"sppb_prettyphoto",horizontal_padding:20,allow_resize:true,default_width:' . $modal_popup_width . ',default_height:' . $modal_popup_height . '})})});';

		return $js;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$css = '';
		$modal_selector = (isset($this->addon->settings->modal_selector) && $this->addon->settings->modal_selector) ? $this->addon->settings->modal_selector : '';
		//Pixeden Icon
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		//Fontawesome Icon
		$selector_icon_name = (isset($this->addon->settings->selector_icon_name) && $this->addon->settings->selector_icon_name) ? $this->addon->settings->selector_icon_name : '';
		$selector_style	= (isset($this->addon->settings->selector_margin_top) && $this->addon->settings->selector_margin_top) ? 'margin-top:' . (int) $this->addon->settings->selector_margin_top .'px;' : '';
		$selector_style	.= (isset($this->addon->settings->selector_margin_bottom) && $this->addon->settings->selector_margin_bottom) ? 'margin-bottom:' . (int) $this->addon->settings->selector_margin_bottom .'px;' : '';

		if($modal_selector == 'icon') {
		
			if($selector_icon_name || $peicon_name) {
			
				$selector_style	.= 'display:inline-block;line-height:1;';
				$selector_style	.= (isset($this->addon->settings->selector_icon_padding) && $this->addon->settings->selector_icon_padding) ? 'padding:' . (int) $this->addon->settings->selector_icon_padding .'px;' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_color) && $this->addon->settings->selector_icon_color) ? 'color:' . $this->addon->settings->selector_icon_color .';' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_background) && $this->addon->settings->selector_icon_background) ? 'background-color:' . $this->addon->settings->selector_icon_background .';' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_border_color) && $this->addon->settings->selector_icon_border_color) ? 'border-style:solid;border-color:' . $this->addon->settings->selector_icon_border_color .';' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_border_width) && $this->addon->settings->selector_icon_border_width) ? 'border-width:' . (int) $this->addon->settings->selector_icon_border_width .'px;' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_border_radius) && $this->addon->settings->selector_icon_border_radius) ? 'border-radius:' . (int) $this->addon->settings->selector_icon_border_radius .'px;' : '';

				$selector_icon_style = (isset($this->addon->settings->selector_icon_size) && $this->addon->settings->selector_icon_size) ? 'font-size:' . (int) $this->addon->settings->selector_icon_size . 'px;width:' . (int) $this->addon->settings->selector_icon_size . 'px;height:' . (int) $this->addon->settings->selector_icon_size . 'px;line-height:' . (int) $this->addon->settings->selector_icon_size . 'px;' : '';

				if($selector_style) {
					$css .= $addon_id . ' .sppb-modal-selector span {';
					$css .= $selector_style;
					$css .= '}';
				}

				if($selector_icon_style) {
					$css .= $addon_id . ' .sppb-modal-selector span > i {';
					$css .= $selector_icon_style;
					$css .= '}';
				}

			}
		} else {
			if($selector_style) {
				$css .= $addon_id . ' .sppb-modal-selector {';
				$css .= $selector_style;
				$css .= '}';
			}
		}

		// Button css
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $this->addon->settings, 'id' => 'sppb-modal-' . $this->addon->id . '-selector'));

		return $css;
	}

}
