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
class SppagebuilderAddonBootstrap_modal extends SppagebuilderAddons{

	public function render() {
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);
		
		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class . ' ' : '';
		
		//Options
		$modal_selector = (isset($this->addon->settings->modal_selector) && $this->addon->settings->modal_selector) ? $this->addon->settings->modal_selector : '';
		$button_text = (isset($this->addon->settings->button_text) && $this->addon->settings->button_text) ? $this->addon->settings->button_text : '';
		$button_size = (isset($this->addon->settings->button_size) && $this->addon->settings->button_size) ? ' sppb-btn-' . $this->addon->settings->button_size : '';
		$button_type = (isset($this->addon->settings->button_type) && $this->addon->settings->button_type) ? ' sppb-btn-' . $this->addon->settings->button_type : ' sppb-btn-default';
		
		//Pixeden Icons
		$button_peicon = (isset($this->addon->settings->button_peicon) && $this->addon->settings->button_peicon) ? $this->addon->settings->button_peicon : '';
		$button_icon = (isset($this->addon->settings->button_icon) && $this->addon->settings->button_icon) ? $this->addon->settings->button_icon : '';
		$button_block = (isset($this->addon->settings->button_block) && $this->addon->settings->button_block) ? $this->addon->settings->button_block : '';
		$selector_image = (isset($this->addon->settings->selector_image) && $this->addon->settings->selector_image) ? $this->addon->settings->selector_image : '';
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : 'sppb-text-center';
		
		$button_icon_position = (isset($this->addon->settings->button_icon_position) && $this->addon->settings->button_icon_position) ? $this->addon->settings->button_icon_position: 'left';
		
		$modal_unique_id = 'sppb-modal-' . $this->addon->id;
		$modal_content_type = (isset($this->addon->settings->modal_content_type) && $this->addon->settings->modal_content_type) ? $this->addon->settings->modal_content_type : 'text';
		$modal_window_title = (isset($this->addon->settings->modal_window_title) && $this->addon->settings->modal_window_title) ? $this->addon->settings->modal_window_title : '';
		$modal_window_size = (isset($this->addon->settings->modal_window_size) && $this->addon->settings->modal_window_size) ? $this->addon->settings->modal_window_size : '';
		$modal_content_text = (isset($this->addon->settings->modal_content_text) && $this->addon->settings->modal_content_text) ? $this->addon->settings->modal_content_text : '';
		$modal_content_image = (isset($this->addon->settings->modal_content_image) && $this->addon->settings->modal_content_image) ? $this->addon->settings->modal_content_image : '';
		$modal_content_video_url = (isset($this->addon->settings->modal_content_video_url) && $this->addon->settings->modal_content_video_url) ? $this->addon->settings->modal_content_video_url : '';

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

	$modal_window_title = (isset($modal_window_title) && $modal_window_title) ? $modal_window_title : '';
	
	if($modal_window_title != '') {
		$alt_text = $modal_window_title;
	} else {
		$alt_text = 'modal image';
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

	$output .= '<div class="' . $class . $alignment . ' mobile-centered">';

	if($modal_selector=='image') {
		if($selector_image) {
			$output .= '<a class="sppb-modal-selector modal-selector-image" href="#" data-toggle="sppb-modal">';
			// Image
			if(strpos($selector_image, 'http://') !== false || strpos($selector_image, 'https://') !== false){
				/* Lazyload for images with absolute URL */
				if($has_lazyload) {
					$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $selector_image .'" alt="'. $alt_text .'">';
				} else {
					$output .= '<img src="' . $selector_image . '" alt="'. $alt_text .'" title="'.$alt_text.'">';	
				}
			} else {
				/* Lazyload for images for relative URL (local image) */
				if($has_lazyload) {
					$output .= '<img class="lazyload sppb-img-responsive' . $class . '" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $selector_image .'" alt="'. $alt_text .'">';
				} else {
					$output .= '<img src="' . $selector_image . '" alt="'. $alt_text .'" title="'.$alt_text.'">';	
				}
			}
		$output .= '</a>';
		}
		
	} else {
		$output .= '<button'. $css_align .' class="sppb-btn sppb-btn-'. $button_type .' sppb-btn-'. $button_size . ' ' . $button_block .' sppb-modal-selector" data-toggle="sppb-modal">'. $button_text .'</button>';
	}

	$output .= '</div>';

	$output .= '<div class="sppb-modal sppb-fade '. $class .'" tabindex="-1" role="dialog" aria-hidden="true">';
	$output .= '<div class="sppb-modal-dialog ' . $modal_window_size . '">';
	$output .= '<div class="sppb-modal-content">';

	if($modal_window_title != '') {
		$output .= '<div class="sppb-modal-header">';
	}
	$output .= '<button type="button" class="sppb-close" data-dismiss="sppb-modal"><span aria-hidden="true">&times;</span></button>';
	if($modal_window_title != '') {
		$output .= '<h4 class="sppb-modal-title">' . $modal_window_title . '</h4>';
		$output .= '</div>';
	}

	$output .= '<div class="sppb-modal-body">';

	if($modal_content_text) $output .= '<div class="sppb-modal-text">'. $modal_content_text .'</div>';
	if($modal_content_image) {
		$output .= '<div class="sppb-modal-image">';
		// Image
		if(strpos($modal_content_image, 'http://') !== false || strpos($modal_content_image, 'https://') !== false){
			/* Lazyload for images with absolute URL */
			if($has_lazyload) {
				$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $modal_content_image .'" alt="'. $alt_text .'" data-expand="-5">';
			} else {
				$output .= '<img class="sppb-img-responsive" src="'. $modal_content_image .'" alt="'.$alt_text.'">';	
			}
		} else {
			/* Lazyload for images for relative URL (local image) */
			if($has_lazyload) {
				$output .= '<img class="lazyload sppb-img-responsive' . $class . '" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $modal_content_image .'" alt="'. $alt_text .'" data-expand="-5">';
			} else {
				$output .= '<img class="sppb-img-responsive" src="'. $modal_content_image .'" alt="'.$alt_text.'">';	
			}
		}
		$output .= '</div>';
	}

	//Video
	if($modal_content_video_url) {

		$video = parse_url($modal_content_video_url);
		
		switch($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'],'/');
				$src = '//www.youtube.com/embed/' . $id;
			break;
			
			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = '//www.youtube.com/embed/' . $id;
			break;
			
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'],'/');
				$src = "//player.vimeo.com/video/{$id}";
		}

		$output .= '<div class="sppb-modal-video sppb-embed-responsive sppb-embed-responsive-16by9">';
		$output .= '<iframe class="sppb-embed-responsive-item" src="' . $src . '" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		$output .= '</div>';
	}

	$output .= '</div>';
	
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;
	}

	public function scripts() {
		$app = JFactory::getApplication();	
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();
		return array($tmplPath.'/sppagebuilder/addons/bootstrap_modal/assets/js/bootstrap-modal.js');
	}

	public function stylesheets() {
		$app = JFactory::getApplication();	
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();
		return array($tmplPath.'/sppagebuilder/addons/bootstrap_modal/assets/css/bootstrap-modal.css');
	}

}
