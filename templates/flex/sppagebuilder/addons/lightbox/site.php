<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('restricted access');

class SppagebuilderAddonLightbox extends SppagebuilderAddons{

	public function render() {
		
		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$width = (isset($this->addon->settings->width) && $this->addon->settings->width) ? $this->addon->settings->width : 200;
		$height = (isset($this->addon->settings->height) && $this->addon->settings->height) ? $this->addon->settings->height : 200;
		$spacing = (isset($this->addon->settings->spacing) && $this->addon->settings->spacing) ? $this->addon->settings->spacing : 0;

		
		$output  = '<div class="sppb-addon sppb-addon-lightbox ' . $class . '">';
		$output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
		$output .= '<div class="sppb-addon-content">';
		$output .= '<ul id="grid-'.$this->addon->id.'" class="sppb-lightbox clearfix">';
		
		foreach ($this->addon->settings->sp_lightbox_item as $key => $value) {
			
			$class != '' ? $thumb_class = ' class="'.$class.'"' : $thumb_class = '';
			$class == 'small' ? $small = ' small' : $small = '';
			$value->show_caption != 0 ? $caption = $value->title : $caption = '';

			
			if($value->thumb) {
				$output .= ($spacing !='') ? '<li class="shuffle-item'.$small.'" style="margin-bottom:' . $spacing . 'px;">' : '<li class="shuffle-item">';
				$output .= '';
		
				if($value->full) {
					$output .= '<div class="overlay"><a href="'. $value->full .'" data-imagelightbox="imagelightbox">';
					if (($class == 'simple') || ($class == 'small')) {
						$output .= '';
					} else {
						$output .= '<i class="ap-plus-1"></i>';
					}
					$output .= '';
				}
		
				$output .= '<img'.$thumb_class.' src="'. $value->thumb . '" width="' . $width . '" alt="' . $caption . '" />';
		
				if($value->full) {
					$output .= '</div></a>';
				}
		
				$output .= '</li>';
			}	
			
		}
		
		$output .= '</ul>';
		$output	.= '</div>';
		$output .= '</div>';

		return $output;
	}
	
	public function stylesheets() {
		$app = JFactory::getApplication();
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();	
		return array( $tmplPath.'/sppagebuilder/addons/lightbox/assets/css/imagelightbox.css');
	}
	

	public function scripts() {
		$app = JFactory::getApplication();
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();	
		
		return array(
			$tmplPath.'/sppagebuilder/addons/lightbox/assets/js/imagelightbox.min.js',
			$tmplPath.'/sppagebuilder/addons/lightbox/assets/js/imagelightbox.custom.js',
			$tmplPath.'/html/com_spsimpleportfolio/assets/js/jquery.shuffle.modernizr.min.js'
		);
	}

	public function js() {
		$gutter = (isset($this->addon->settings->spacing) && $this->addon->settings->spacing) ? 'gutterWidth: ' . $this->addon->settings->spacing . ',' : '';
		$js ='jQuery(function($){
			var $grid = jQuery("#grid-'.$this->addon->id.'"),
			$sizer = $grid.find(".shuffle_sizer");
			$("#grid-'.$this->addon->id.'").imagesLoaded(function(){
			  $grid.shuffle({itemSelector: ".shuffle-item",'.$gutter.'columnWidth: '.$this->addon->settings->width.',speed: 700,sequentialFadeDelay: 150,easing: \'cubic-bezier(0.635, 0.010, 0.355, 1.000)\',sizer: $sizer
			  });
			}); 
		});';
		$js = preg_replace(array('/([\s])\1+/', '/[\n\t]+/m'), '', $js); // Remove whitespace
		return $js;
	}

}
