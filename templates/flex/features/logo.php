<?php
/**
 * Flex @package Helix3 Framework
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2019 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die('restricted access');
 
class Helix3FeatureLogo {

	private $helix3;
	public $position;

	public function __construct( $helix3 ){
		$this->helix3 = $helix3;
		$this->position = $this->helix3->getParam('logo_position', 'logo');
		$this->load_pos = $this->helix3->getParam('logo_load_pos');
	}

	public function renderFeature() {

		$doc = JFactory::getDocument();
		$extention = '';
		//Retina Image
		if( $this->helix3->getParam('logo_type') == 'image' ) {
			jimport('joomla.image.image');

			if( $this->helix3->getParam('logo_image') ) {
				$path = JPATH_ROOT . '/' . $this->helix3->getParam('logo_image');
			} else {
				$path = JPATH_ROOT . '/templates/' . $this->helix3->getTemplate() . '/images/presets/' . $this->helix3->Preset() . '/logo.png';
			}
			
			// Scroll Logo (for Sticky header)
			if ($this->helix3->getParam('sticky_logo')) {
				$scroll_path = JPATH_ROOT . '/' . $this->helix3->getParam('sticky_logo');
			} else {
				$scroll_path = '';
			}

			// Detecting SVG 
			$extention = explode('.', $this->helix3->getParam('logo_image'));
 			$extention = end($extention);
			
			// Fix for SVG image for logo
			if ($extention != 'svg') {
			
				// Other images (jpg, png, gif)
				if(file_exists($path)) {
					$image = new JImage( $path );
					$width 	= $image->getWidth();
					$height = $image->getHeight();
				} else {
					$width 	= '';
					$height = '';
				}	
				
			}
			
			/* From Flex 3.2: fix for Logo container. Logo graphic shouldn't go outside "Logo" container. */
			$header_container = ( $height < $this->helix3->getParam('header_height') ) ? ' style="max-width:' . $width . 'px;max-height:' . $height . 'px;"' : '';
			
		}
		
		// Detecting SVG  for Sticky Logo
		$svg_sticky = explode('.', $this->helix3->getParam('sticky_logo'));
		$svg_sticky = end($svg_sticky);

		$html  = '';
		$custom_logo_class = '';
		$sitename = JFactory::getApplication()->get('sitename');

		if( $this->helix3->getParam('mobile_logo') ) {
			$custom_logo_class = ' hidden-xs';
		}

		$sticky_logo_class = ($this->helix3->getParam('sticky_logo')) ? ' has-sticky-logo' : '';

		if( $this->helix3->getParam('logo_type') == 'image' ) {
			if( $this->helix3->getParam('logo_image') ) {
				$html .= '<a class="logo" href="' . JURI::base(true) . '/">';
				
				if ($extention != 'svg') {
				  $html .= '<img'. $header_container .' class="sp-default-logo'. $custom_logo_class . $sticky_logo_class .'" src="' . $this->helix3->getParam('logo_image') . '" alt="'. $sitename .'">';
				} else {
					$html .= '<img class="'. $sticky_logo_class .'" style="width:100%;height:100%;" src="' . $this->helix3->getParam('logo_image') . '" alt="'. $sitename .'">';
				}
				
			// Detecting SVG for retina
			$extention_2x = explode('.', $this->helix3->getParam('logo_image_2x'));
 			$extention_2x = end($extention_2x);
		
				if ($extention_2x != 'svg') {
					if( $this->helix3->getParam('logo_image_2x') ) {
						$html .= '<img'. $header_container .' class="sp-retina-logo'. $custom_logo_class . $sticky_logo_class .'" src="' . $this->helix3->getParam('logo_image_2x') . '" alt="'. $sitename .'">';
					}
				} else {
					$html .= '<img class="'. $sticky_logo_class .'" style="max-width:100%;height:' . $height . 'px;" src="' . $this->helix3->getParam('logo_image_2x') . '" alt="'. $sitename .'">';
				}
				
				/* Sticky Logo */
				if ($this->helix3->getParam('sticky_header') == 1) {
					if ($svg_sticky != 'svg') {
						if ($this->helix3->getParam('sticky_logo')) {
							$html .= '<img class="sp-sticky-logo'. $custom_logo_class .'" src="' . $this->helix3->getParam('sticky_logo') . '" alt="'. $sitename .'">';
						}
					} else {
						/* If Sticky Logo is SVG graphic*/
						$html .= '<img class="sp-sticky-logo'. $custom_logo_class .'" src="' . $this->helix3->getParam('sticky_logo') . '" alt="'. $sitename .'">';
					}
				}
				
				if( $this->helix3->getParam('mobile_logo') ) {
					$html .= '<img class="sp-default-logo visible-xs-block'. $sticky_logo_class .'" src="' . $this->helix3->getParam('mobile_logo') . '" alt="'. $sitename .'">';
				}

				$html .= '</a>';
			} else {
				$html .= '<a class="logo" href="' . JURI::base(true) . '/">';
				$html .= '<img class="sp-default-logo'. $custom_logo_class . $sticky_logo_class .'" src="' . $this->helix3->getTemplateUri() . '/images/presets/' . $this->helix3->Preset() . '/logo.png" alt="'. $sitename .'">';
				$html .= '<img style="max-width:' . $width . 'px;max-height:' . $height . 'px;" class="sp-retina-logo'. $custom_logo_class . $sticky_logo_class .'" src="' . $this->helix3->getTemplateUri() . '/images/presets/' . $this->helix3->Preset() . '/logo@2x.png" alt="'. $sitename .'">';
				
				/* Sticky Logo */
				if ($this->helix3->getParam('sticky_header') == 1) {
					if ($svg_sticky != 'svg') {
						if ($this->helix3->getParam('sticky_logo')) {
							$html .= '<img class="sp-sticky-logo'. $custom_logo_class .'" src="' . $this->helix3->getParam('sticky_logo') . '" alt="'. $sitename .'">';
						}
					} else {
						/* If Sticky Logo is SVG graphic*/
						$html .= '<img class="sp-sticky-logo'. $custom_logo_class .'" style="max-width:100%;" src="' . $this->helix3->getParam('sticky_logo') . '" alt="'. $sitename .'">';
					}
				}

				/* Mobile Logo */
				if( $this->helix3->getParam('mobile_logo') ) {
					$html .= '<img class="sp-default-logo visible-xs-block'. $sticky_logo_class .'" src="' . $this->helix3->getParam('mobile_logo') . '" alt="'. $sitename .'">';
				}

				$html .= '</a>';
			}
			
			
			
		} else {
			if( $this->helix3->getParam('logo_text') ) {
				$html .= '<h1 class="logo"> <a href="' . JURI::base(true) . '/">' . $this->helix3->getParam('logo_text') . '</a></h1>';
			} else {
				$html .= '<h1 class="logo"> <a href="' . JURI::base(true) . '/">' . $sitename . '</a></h1>';
			}

			if( $this->helix3->getParam('logo_slogan') ) {
				$html .= '<p class="logo-slogan">' . $this->helix3->getParam('logo_slogan') . '</p>';
			}
		}
		
		return $html;
	}
}
