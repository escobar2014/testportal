<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonModal extends SppagebuilderAddons{

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
		$button_block = (isset($this->addon->settings->button_block) && $this->addon->settings->button_block) ? ' ' . $this->addon->settings->button_block : '';

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
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : 'sppb-text-center';
		$modal_unique_id = 'sppb-modal-' . $this->addon->id;
		$modal_content_type = (isset($this->addon->settings->modal_content_type) && $this->addon->settings->modal_content_type) ? $this->addon->settings->modal_content_type : 'text';
		$modal_content_text = (isset($this->addon->settings->modal_content_text) && $this->addon->settings->modal_content_text) ? $this->addon->settings->modal_content_text : '';
		$modal_content_image = (isset($this->addon->settings->modal_content_image) && $this->addon->settings->modal_content_image) ? $this->addon->settings->modal_content_image : '';
		$modal_content_video_url = (isset($this->addon->settings->modal_content_video_url) && $this->addon->settings->modal_content_video_url) ? $this->addon->settings->modal_content_video_url : '';
		$modal_popup_width = (isset($this->addon->settings->modal_popup_width) && $this->addon->settings->modal_popup_width) ? $this->addon->settings->modal_popup_width : '';
		$modal_popup_height = (isset($this->addon->settings->modal_popup_height) && $this->addon->settings->modal_popup_height) ? $this->addon->settings->modal_popup_height : '';
		$selector_text = (isset($this->addon->settings->selector_text) && $this->addon->settings->selector_text) ? $this->addon->settings->selector_text : '';

		if ( $modal_content_type == 'text' ) {
			$mfg_type = 'inline';
		} else if ( $modal_content_type == 'video' ) {
			$mfg_type = 'iframe';
		} else if ( $modal_content_type == 'image' ) {
			$mfg_type = 'image';
		}
		
		// Alignment
		$css_align = '';
		if ($button_block != 'sppb-btn-block') {
			if($alignment == 'sppb-text-left'){
				$css_align .= ' style="float:left;"';
			} elseif( $alignment == 'sppb-text-right' ){
				$css_align .= ' style="float:right;"';
			} elseif( $alignment == 'sppb-text-center' ){
				$css_align .= ' style="float:none;margin-left:auto;margin-right:auto;display:table;"';
			}
		} else {
			$css_align .= ' style="display:block;"';
		}

		$output = '';

		if($modal_content_type == 'text') {
			$url = '#' . $modal_unique_id;
			$output .= '<div id="' . $modal_unique_id . '" class="mfp-hide white-popup-block modal-text">';
				$output .= '<div class="modal-inner-block">';
					$output .= $modal_content_text;
				$output .= '</div>';
			$output .= '</div>';
			$attribs = 'data-popup_type="inline" data-mainclass="mfp-no-margins mfp-with-zoom"';
		} else if( $modal_content_type == 'video') {
			$url = $modal_content_video_url;
			$attribs = 'data-popup_type="iframe" data-mainclass="mfp-no-margins mfp-with-zoom"';
		} else {
			$url = '#' . $modal_unique_id;
			$output .= '<div id="' . $modal_unique_id . '" class="mfp-hide popup-image-block">';
				$output .= '<div class="modal-inner-block">';
				
				// Image
				if(strpos($modal_content_image, 'http://') !== false || strpos($modal_content_image, 'https://') !== false){
					/* Lazyload for images with absolute URL */
					if($has_lazyload) {
						$output .= '<img class="lazyload mfp-img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $modal_content_image .'">';
					} else {
						$output .= '<img class="mfp-img" src="'.$modal_content_image.'" >';	
					}
				} else {
					/* Lazyload for images for relative URL (local image) */
					if($has_lazyload) {
						$output .= '<img class="lazyload mfp-img" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $modal_content_image .'">';
					} else {
						$output .= '<img class="mfp-img" src="'.$modal_content_image.'" >';	
					}
				}
				
				$output .= '</div>';
			$output .= '</div>';
			$attribs = 'data-popup_type="inline" data-mainclass="mfp-no-margins mfp-with-zoom"';
		}

		$output .= '<div class="' . $class . ' ' . $alignment . ' mobile-centered">';

		if($modal_selector=='image') {
			if ($selector_image) {
				$output .= '<a class="sppb-modal-selector sppb-magnific-popup" '. $attribs .' href="'. $url . '" id="'. $modal_unique_id .'-selector">';
				// Image
				if(strpos($selector_image, 'http://') !== false || strpos($selector_image, 'https://') !== false){
					/* Lazyload for images with absolute URL */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $selector_image .'" alt="'.$selector_text.'">';
					} else {
						$output .= '<img src="' . $selector_image . '" alt="'.$selector_text.'">';
					}
				} else {
					/* Lazyload for images for relative URL (local image) */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $selector_image .'" alt="'.$selector_text.'">';
					} else {
						$output .= '<img src="' . $selector_image . '" alt="'.$selector_text.'">';	
					}
				}
				$output .= ($selector_text) ? '<span class="text">' . $selector_text . '</span>' : '';
				$output .= '</a>';
			}
		} else if ($modal_selector=='icon') {
			if($selector_icon_name || $peicon_name) {
				$output .= '<a class="sppb-modal-selector sppb-magnific-popup" href="'. $url . '" '. $attribs .' id="'. $modal_unique_id .'-selector">';
				$output  .= '<span>';
				if ($peicon_name != '') {
					$output .= '<i class="pe ' . $peicon_name . '"></i>';
				}else{
					$output .= '<i class="fa ' . $selector_icon_name . '"></i>';
				}
				$output .= '</span>';
				$output .= ($selector_text) ? '<span class="text">' . $selector_text . '</span>' : '';
				$output .= '</a>';
			}
		} else {
			$output .= '<a'. $css_align .' class="sppb-btn ' . $button_class . ' sppb-magnific-popup sppb-modal-selector" '. $attribs .' href="'. $url . '" id="'. $modal_unique_id .'-selector">'. $button_text .'</a>';
		}

		$output .= '</div>';

		return $output;
	}

	public function scripts() {
		return array(JURI::base(true) . '/components/com_sppagebuilder/assets/js/jquery.magnific-popup.min.js');
	}

	public function stylesheets() {
		return array(JURI::base(true) . '/components/com_sppagebuilder/assets/css/magnific-popup.css');
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;

		$modal_content_type = (isset($this->addon->settings->modal_content_type) && $this->addon->settings->modal_content_type) ? $this->addon->settings->modal_content_type : 'text';

		$modal_size  = (isset($this->addon->settings->modal_popup_width) && $this->addon->settings->modal_popup_width) ? 'max-width: ' .$this->addon->settings->modal_popup_width . 'px;' : '';
		$modal_size .= (isset($this->addon->settings->modal_popup_height) && $this->addon->settings->modal_popup_height) ? ' height: ' . $this->addon->settings->modal_popup_height . 'px;' : '';

		$selector_style	= '';
		$selector_style_sm	= '';
		$selector_style_xs	= '';

		$modal_selector = (isset($this->addon->settings->modal_selector) && $this->addon->settings->modal_selector) ? $this->addon->settings->modal_selector : '';
		//Pixeden Icon
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		//Fontawesome Icon
		$selector_icon_name = (isset($this->addon->settings->selector_icon_name) && $this->addon->settings->selector_icon_name) ? $this->addon->settings->selector_icon_name : '';
		$selector_image = (isset($this->addon->settings->selector_image) && $this->addon->settings->selector_image) ? $this->addon->settings->selector_image : '';
		$selector_style	.= (isset($this->addon->settings->selector_margin_top) && $this->addon->settings->selector_margin_top) ? 'margin-top:' . (int) $this->addon->settings->selector_margin_top .'px;' : '';
		$selector_style	.= (isset($this->addon->settings->selector_margin_bottom) && $this->addon->settings->selector_margin_bottom) ? 'margin-bottom:' . (int) $this->addon->settings->selector_margin_bottom .'px;' : '';

		$css = '';

		if( $modal_selector == 'icon' || $modal_selector == 'image' ) {
			if($selector_icon_name || $peicon_name || $selector_image) {
				$selector_text_style	= (isset($this->addon->settings->selector_text_size) && $this->addon->settings->selector_text_size) ? 'font-size:' . $this->addon->settings->selector_text_size .'px;' : '';
				$selector_text_style	.= (isset($this->addon->settings->selector_text_weight) && $this->addon->settings->selector_text_weight) ? 'font-weight:' . $this->addon->settings->selector_text_weight .';' : '';
				$selector_text_style	.= (isset($this->addon->settings->selector_text_margin) && $this->addon->settings->selector_text_margin) ? 'margin:' . $this->addon->settings->selector_text_margin .';' : '';
				$selector_text_style	.= (isset($this->addon->settings->selector_text_color) && $this->addon->settings->selector_text_color) ? 'color:' . $this->addon->settings->selector_text_color .';' : '';

				if($selector_text_style) {
					$css .= $addon_id . ' .sppb-modal-selector span.text {';
					$css .= $selector_text_style;
					$css .= '}';
				}
			}
		}

		if($modal_selector == 'icon') {
			if($selector_icon_name || $peicon_name) {
				$selector_style	.= 'display:inline-block;line-height:1;';

				$selector_style	.= (isset($this->addon->settings->selector_icon_padding) && $this->addon->settings->selector_icon_padding) ? 'padding:' . (int) $this->addon->settings->selector_icon_padding .'px;' : '';
				$selector_style_sm	.= (isset($this->addon->settings->selector_icon_padding_sm) && $this->addon->settings->selector_icon_padding_sm) ? 'padding:' . (int) $this->addon->settings->selector_icon_padding_sm .'px;' : '';
				$selector_style_xs	.= (isset($this->addon->settings->selector_icon_padding_xs) && $this->addon->settings->selector_icon_padding_xs) ? 'padding:' . (int) $this->addon->settings->selector_icon_padding_xs .'px;' : '';

				$selector_style	.= (isset($this->addon->settings->selector_icon_color) && $this->addon->settings->selector_icon_color) ? 'color:' . $this->addon->settings->selector_icon_color .';' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_background) && $this->addon->settings->selector_icon_background) ? 'background-color:' . $this->addon->settings->selector_icon_background .';' : '';
				$selector_style	.= (isset($this->addon->settings->selector_icon_border_color) && $this->addon->settings->selector_icon_border_color) ? 'border-style:solid;border-color:' . $this->addon->settings->selector_icon_border_color .';' : '';

				$selector_style	.= (isset($this->addon->settings->selector_icon_border_width) && $this->addon->settings->selector_icon_border_width) ? 'border-width:' . (int) $this->addon->settings->selector_icon_border_width .'px;' : '';
				$selector_style_sm	.= (isset($this->addon->settings->selector_icon_border_width_sm) && $this->addon->settings->selector_icon_border_width_sm) ? 'border-width:' . (int) $this->addon->settings->selector_icon_border_width_sm .'px;' : '';
				$selector_style_xs	.= (isset($this->addon->settings->selector_icon_border_width_xs) && $this->addon->settings->selector_icon_border_width_xs) ? 'border-width:' . (int) $this->addon->settings->selector_icon_border_width_xs .'px;' : '';

				$selector_style	.= (isset($this->addon->settings->selector_icon_border_radius) && $this->addon->settings->selector_icon_border_radius) ? 'border-radius:' . (int) $this->addon->settings->selector_icon_border_radius .'px;' : '';
				$selector_style_sm	.= (isset($this->addon->settings->selector_icon_border_radius_sm) && $this->addon->settings->selector_icon_border_radius_sm) ? 'border-radius:' . (int) $this->addon->settings->selector_icon_border_radius_sm .'px;' : '';
				$selector_style_xs	.= (isset($this->addon->settings->selector_icon_border_radius_xs) && $this->addon->settings->selector_icon_border_radius_xs) ? 'border-radius:' . (int) $this->addon->settings->selector_icon_border_radius_xs .'px;' : '';

				$selector_icon_style_sm = '';
				$selector_icon_style_xs = '';
				$selector_icon_style = (isset($this->addon->settings->selector_icon_size) && $this->addon->settings->selector_icon_size) ? 'font-size:' . (int) $this->addon->settings->selector_icon_size . 'px;width:' . (int) $this->addon->settings->selector_icon_size . 'px;height:' . (int) $this->addon->settings->selector_icon_size . 'px;line-height:' . (int) $this->addon->settings->selector_icon_size . 'px;' : '';
				$selector_icon_style_sm	= (isset($this->addon->settings->selector_icon_size_sm) && $this->addon->settings->selector_icon_size_sm) ? 'font-size:' . (int) $this->addon->settings->selector_icon_size_sm . 'px;width:' . (int) $this->addon->settings->selector_icon_size_sm . 'px;height:' . (int) $this->addon->settings->selector_icon_size_sm . 'px;line-height:' . (int) $this->addon->settings->selector_icon_size_sm . 'px;' : '';
				$selector_icon_style_xs	= (isset($this->addon->settings->selector_icon_size_xs) && $this->addon->settings->selector_icon_size_xs) ? 'font-size:' . (int) $this->addon->settings->selector_icon_size_xs . 'px;width:' . (int) $this->addon->settings->selector_icon_size_xs . 'px;height:' . (int) $this->addon->settings->selector_icon_size_xs . 'px;line-height:' . (int) $this->addon->settings->selector_icon_size_xs . 'px;' : '';

				if($selector_style) {
					$css .= $addon_id . ' .sppb-modal-selector span {';
					$css .= $selector_style;
					$css .= '}';
				}

				if($selector_style_sm) {
					$css .= '@media (min-width: 768px) and (max-width: 991px) {';
						$css .= $addon_id . ' .sppb-modal-selector span {';
							$css .= $selector_style_sm;
						$css .= '}';
					$css .= '}';
				}

				if($selector_style_xs) {
					$css .= '@media (max-width: 767px) {';
						$css .= $addon_id . ' .sppb-modal-selector span {';
							$css .= $selector_style_xs;
						$css .= '}';
					$css .= '}';
				}

				if($selector_icon_style) {
					$css .= $addon_id . ' .sppb-modal-selector span > i {';
					$css .= $selector_icon_style;
					$css .= '}';
				}

				if($selector_icon_style_sm) {
					$css .= '@media (min-width: 768px) and (max-width: 991px) {';
						$css .= $addon_id . ' .sppb-modal-selector span > i {';
							$css .= $selector_icon_style_sm;
						$css .= '}';
					$css .= '}';
				}

				if($selector_icon_style_xs) {
					$css .= '@media (max-width: 767px) {';
						$css .= $addon_id . ' .sppb-modal-selector span > i {';
							$css .= $selector_icon_style_xs;
						$css .= '}';
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

		if( $modal_content_type != 'video' && $modal_size) {
			if ($modal_content_type == 'image') {
				$css .= '#sppb-modal-' . $this->addon->id . '.popup-image-block img{';
				$css .= $modal_size;
				$css .= '}';
			} else {
				$css .= '#sppb-modal-' . $this->addon->id . '.white-popup-block {';
				$css .= $modal_size;
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
