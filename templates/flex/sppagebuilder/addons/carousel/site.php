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

class SppagebuilderAddonCarousel extends SppagebuilderAddons {

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? ' ' . $this->addon->settings->class : '';

		//Addons option
		$autoplay = (isset($this->addon->settings->autoplay) && $this->addon->settings->autoplay) ? ' data-sppb-ride="sppb-carousel"' : 0;
		$controllers = (isset($this->addon->settings->controllers) && $this->addon->settings->controllers) ? $this->addon->settings->controllers : 0;
		$arrows = (isset($this->addon->settings->arrows) && $this->addon->settings->arrows) ? $this->addon->settings->arrows : 0;
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : 0;
		$items_padding = (isset($this->addon->settings->items_padding) && $this->addon->settings->items_padding) ? $this->addon->settings->items_padding : '';
		
		$interval = (isset($this->addon->settings->interval) && $this->addon->settings->interval) ? ((int) $this->addon->settings->interval * 1000) : 5000;
		
		$sppbCarouselParam['items_padding'] = $items_padding;
		
		$carousel_autoplay = ($autoplay) ? ' data-sppb-ride="sppb-carousel"':'';

		$output  = '<div id="sppb-carousel-'. $this->addon->id .'" data-interval="'.$interval.'" class="sppb-carousel sppb-carousel-flex sppb-slide' . $class . '"'. $carousel_autoplay .'>';

		if($controllers) {
			$output .= '<ol class="sppb-carousel-indicators">';
				foreach ($this->addon->settings->sp_carousel_item as $key1 => $value) {
					$output .= '<li data-sppb-target="#sppb-carousel-'. $this->addon->id .'" '. (($key1 == 0) ? ' class="active"': '' ) .'  data-sppb-slide-to="'. $key1 .'"></li>' . "\n";
				}
			$output .= '</ol>';
		}

		$output .= '<div class="sppb-carousel-inner ' . $alignment . '">';

		foreach ($this->addon->settings->sp_carousel_item as $key => $value) {
			
			$heading_selector = (isset($value->heading_selector) && $value->heading_selector) ? $value->heading_selector : 'h2';
			$background_color = (isset($value->background_color) && $value->background_color) ? $value->background_color : '';
			$content_color = (isset($value->content_color) && $value->content_color) ? $value->content_color : '';
		
			if($value->bg) {
				$sppbCarouselParam['items_padding'] != '' ? $item_padding = 'padding:0;' : $item_padding = '';
			} else {
				$sppbCarouselParam['items_padding'] != '' ? $item_padding = 'padding:' .$sppbCarouselParam['items_padding']. ';' : $item_padding = 'padding:60px;';
			}
			
			if($background_color) {
				$background_color = 'background-color:' . $background_color . ';';
			}
			
			if($content_color) {
				$content_color = ' style="color:' . $content_color . ';"';
			}
			
			
			$title_fontsize = (isset($value->title_fontsize) && $value->title_fontsize) ? ' style="font-size: ' . $value->title_fontsize . 'px; line-height: ' . $value->title_fontsize . 'px;"' : '';


			$output   .= '<div style="' . $background_color . $item_padding .'" class="sppb-item'. (($value->bg) ? ' sppb-item-has-bg' : '') . (($key == 0) ? ' active' : '') .'">';
			
			// Image
			if($value->bg) {
				if(strpos($value->bg, 'http://') !== false || strpos($value->bg, 'https://') !== false){
					/* Lazyload for images with absolute URL */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $value->bg .'" alt="'. $value->title .'">';
					} else {
						$output  .= '<img src="' . $value->bg . '" alt="'. $value->title .'" title="'.$value->title.'">';	
					}
				} else {
					/* Lazyload for images for relative URL (local image) */
					if($has_lazyload) {
						$output .= '<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $value->bg .'" alt="'. $value->title .'">';
					} else {
						$output  .= '<img src="' . $value->bg . '" alt="'. $value->title .'" title="'.$alt_text.'">';	
					}
				}
			}
			
			$output  .= '<div class="sppb-carousel-item-inner">';
			$output  .= '<div class="sppb-carousel-caption">';
			$output  .= '<div'.$content_color.' class="sppb-carousel-pro-text">';

			if(($value->title) || ($value->content) ) {
				$output .= ($value->title) ? '<' . $heading_selector . $title_fontsize . '>' . $value->title . '</'.$heading_selector.'>' : '';
				
				$output  .= '<p>' . $value->content . '</p>';
				if($value->button_text) {
					$button_class = (isset($value->button_type) && $value->button_type) ? ' sppb-btn-' . $value->button_type : ' sppb-btn-default';
					$button_class .= (isset($value->button_size) && $value->button_size) ? ' sppb-btn-' . $value->button_size : '';
					$button_class .= (isset($value->button_shape) && $value->button_shape) ? ' sppb-btn-' . $value->button_shape: ' sppb-btn-rounded';
					$button_class .= (isset($value->button_appearance) && $value->button_appearance) ? ' sppb-btn-' . $value->button_appearance : '';
					$button_class .= (isset($value->button_block) && $value->button_block) ? ' ' . $value->button_block : '';
					$button_icon = (isset($value->button_icon) && $value->button_icon) ? $value->button_icon : '';
					$button_icon_position = (isset($value->button_icon_position) && $value->button_icon_position) ? $value->button_icon_position: 'left';
					$button_target = (isset($value->button_target) && $value->button_target) ? $value->button_target : '_self';

					if($button_icon_position == 'left') {
						$value->button_text = ($button_icon) ? '<i class="fa ' . $button_icon . '"></i> ' . $value->button_text : $value->button_text;
					} else {
						$value->button_text = ($button_icon) ? $value->button_text . ' <i class="fa ' . $button_icon . '"></i>' : $value->button_text;
					}

					$output  .= '<a href="' . $value->button_url . '" target="' . $button_target . '" id="btn-'. ($this->addon->id + $key) .'" class="sppb-btn'. $button_class .'">' . $value->button_text . '</a>';
				}
			}

			$output  .= '</div>';
			$output  .= '</div>';

			$output  .= '</div>';
			$output  .= '</div>';
		}

		$output	.= '</div>';

		if($arrows) {
			$output	.= '<a href="#sppb-carousel-'. $this->addon->id .'" class="sppb-carousel-arrow left sppb-carousel-control" data-slide="prev"><i class="fa fa-angle-left"></i></a>';
			$output	.= '<a href="#sppb-carousel-'. $this->addon->id .'" class="sppb-carousel-arrow right sppb-carousel-control" data-slide="next"><i class="fa fa-angle-right"></i></a>';
		}
	
		$output .= '</div>';

		return $output;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
	
		$css = '';
	
		// Buttons style
		foreach ($this->addon->settings->sp_carousel_item as $key => $value) {
			if($value->button_text) {
				$css_path = new JLayoutFile('addon.css.button', $layout_path);
				$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $this->addon->settings, 'id' => 'btn-' . ($this->addon->id + $key) ));
			}
		}
		$speed = (isset($this->addon->settings->speed) && $this->addon->settings->speed) ? $this->addon->settings->speed : 600;

		$css .= $addon_id.' .sppb-carousel-inner > .sppb-item{-webkit-transition-duration: '.$speed.'ms; transition-duration: '.$speed.'ms;}';
		return $css;
	}
}
