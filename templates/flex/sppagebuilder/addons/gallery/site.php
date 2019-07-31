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

class SppagebuilderAddonGallery extends SppagebuilderAddons{

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$width = (isset($this->addon->settings->width) && $this->addon->settings->width) ? $this->addon->settings->width : 200;
		//$height = (isset($this->addon->settings->height) && $this->addon->settings->height) ? $this->addon->settings->height : 200;
		$thumbs_gap = (isset($this->addon->settings->thumbs_gap) && $this->addon->settings->thumbs_gap) ? $this->addon->settings->thumbs_gap : '';

		if ($thumbs_gap < 1) {
			$thumbs_margin = '';
		} else {
			$thumbs_gap != '' ? $thumbs_margin = ' style="margin:-' . $thumbs_gap . 'px;"' : $thumbs_margin = '';
		}
		$output  = '<div class="sppb-addon sppb-addon-gallery ' . $class . '">';
		$output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
		$output .= '<div class="sppb-addon-content">';
		$output .= '<ul' . $thumbs_margin . ' class="sppb-gallery clearfix">';
		
		if ( $thumbs_gap < '1' ) {
			$thumbs_padding = '';
		} else {
			$thumbs_gap != '' ? $thumbs_padding = ' style="padding:' .$thumbs_gap. 'px;"' : $thumbs_padding = '';
		}

		if(isset($this->addon->settings->sp_gallery_item) && count((array) $this->addon->settings->sp_gallery_item)){
			foreach ($this->addon->settings->sp_gallery_item as $key => $value) {
				if($value->thumb) {
					$output .= '<li'.$thumbs_padding.'>';
					$output .= ($value->full) ? '<a href="' . $value->full . '" class="sppb-gallery-btn">' : '';
					// Thumb Image
					if(strpos($value->thumb, 'http://') !== false || strpos($value->thumb, 'https://') !== false){
					// Lazyload for images with absolute URL
						if($has_lazyload) {
							$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="'. $value->thumb .'" alt="' . $value->title . '" style="width:'.$width.'px;" data-expand="-10">';
						} else {
							$output .= '<img class="sppb-img-responsive" src="' . $value->thumb . '" alt="' . $value->title . '" style="width:'.$width.'px;">';	
						}
					} else {
						// Lazyload for images for relative URL (local image)
						if($has_lazyload) {
							$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="'. JUri::root() . $value->thumb .'" alt="' . $value->title . '" style="width:'.$width.'px;" data-expand="-10">';
						} else {
							$output .= '<img class="sppb-img-responsive" src="' . $value->thumb . '" alt="' . $value->title . '" style="width:'.$width.'px;">';	
						}
					}
					$output .= ($value->full) ? '</a>' : '';
					$output .= '</li>';
				}
			}
		}

		$output .= '</ul>';
		$output	.= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function stylesheets() {
		return array(JURI::base(true) . '/components/com_sppagebuilder/assets/css/magnific-popup.css');
	}

	public function scripts() {
		return array(JURI::base(true) . '/components/com_sppagebuilder/assets/js/jquery.magnific-popup.min.js');
	}

	public function js() {

		$js ='jQuery(function($){
			$(document).magnificPopup({
				delegate: ".sppb-gallery-btn",
				type: "image",
				mainClass: "mfp-no-margins mfp-with-zoom",
				gallery:{
					enabled:true
				},
				image: {
					verticalFit: true
				},
				zoom: {
					enabled: true,
					duration: 300
				}
			});
		})';
		$js = preg_replace('/[\n\t]+/m', '', $js); // Remove whitespace
		return $js;
	}

}
