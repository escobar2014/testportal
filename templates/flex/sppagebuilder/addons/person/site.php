<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('Restricted access');

class SppagebuilderAddonPerson extends SppagebuilderAddons{

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		
		$type = (isset($this->addon->settings->type) && $this->addon->settings->type) ? $this->addon->settings->type : '';

		//Options
		$image 				= (isset($this->addon->settings->image) && $this->addon->settings->image) ? $this->addon->settings->image : '';
		$name 				= (isset($this->addon->settings->name) && $this->addon->settings->name) ? $this->addon->settings->name : '';
		$designation 	= (isset($this->addon->settings->designation) && $this->addon->settings->designation) ? $this->addon->settings->designation : '';
		$email 				= (isset($this->addon->settings->email) && $this->addon->settings->email) ? $this->addon->settings->email : '';
		$introtext 		= (isset($this->addon->settings->introtext) && $this->addon->settings->introtext) ? $this->addon->settings->introtext : '';
		$facebook 		= (isset($this->addon->settings->facebook) && $this->addon->settings->facebook) ? $this->addon->settings->facebook : '';
		$twitter 			= (isset($this->addon->settings->twitter) && $this->addon->settings->twitter) ? $this->addon->settings->twitter : '';
		$google_plus 	= (isset($this->addon->settings->google_plus) && $this->addon->settings->google_plus) ? $this->addon->settings->google_plus : '';
		$youtube 			= (isset($this->addon->settings->youtube) && $this->addon->settings->youtube) ? $this->addon->settings->youtube : '';
		$linkedin 		= (isset($this->addon->settings->linkedin) && $this->addon->settings->linkedin) ? $this->addon->settings->linkedin : '';
		$pinterest 		= (isset($this->addon->settings->pinterest) && $this->addon->settings->pinterest) ? $this->addon->settings->pinterest : '';
		$flickr 			= (isset($this->addon->settings->flickr) && $this->addon->settings->flickr) ? $this->addon->settings->flickr : '';
		$dribbble 		= (isset($this->addon->settings->dribbble) && $this->addon->settings->dribbble) ? $this->addon->settings->dribbble : '';
		$behance 			= (isset($this->addon->settings->behance) && $this->addon->settings->behance) ? $this->addon->settings->behance : '';
		$instagram 		= (isset($this->addon->settings->instagram) && $this->addon->settings->instagram) ? $this->addon->settings->instagram : '';
		$social_position = (isset($this->addon->settings->social_position) && $this->addon->settings->social_position) ? $this->addon->settings->social_position : '';
		$alignment 		= (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : '';
		$background 		= (isset($this->addon->settings->background) && $this->addon->settings->background) ? $this->addon->settings->background : '';
		
		$output = '';
		$social_icons = '';
		$person_background = '';
	
		if($facebook || $twitter || $google_plus || $youtube || $linkedin || $pinterest || $flickr || $dribbble || $behance || $instagram) {
			$social_icons  	.= '<div class="sppb-person-social-icons">';
			$social_icons 	.= '<ul class="sppb-person-social">';
	
			if($facebook) 		$social_icons .= '<li><a target="_blank" href="' . $facebook . '"><i class="fa fa-facebook"></i></a></li>';
			if($twitter) 		$social_icons .= '<li><a target="_blank" href="' . $twitter . '"><i class="fa fa-twitter"></i></a></li>';
			if($google_plus) 	$social_icons .= '<li><a target="_blank" href="' . $google_plus . '"><i class="fa fa-google-plus"></i></a></li>';
			if($youtube) 		$social_icons .= '<li><a target="_blank" href="' . $youtube . '"><i class="fa fa-youtube"></i></a></li>';
			if($linkedin) 		$social_icons .= '<li><a target="_blank" href="' . $linkedin . '"><i class="fa fa-linkedin"></i></a></li>';
			if($pinterest) 		$social_icons .= '<li><a target="_blank" href="' . $pinterest . '"><i class="fa fa-pinterest"></i></a></li>';
			if($flickr) 		    $social_icons .= '<li><a target="_blank" href="' . $flickr . '"><i class="fa fa-flickr"></i></a></li>';
			if($dribbble) 		$social_icons .= '<li><a target="_blank" href="' . $dribbble . '"><i class="fa fa-dribbble"></i></a></li>';
			if($behance) 		$social_icons .= '<li><a target="_blank" href="' . $behance . '"><i class="fa fa-behance"></i></a></li>';
			if($instagram) 		$social_icons .= '<li><a target="_blank" href="' . $instagram . '"><i class="fa fa-instagram"></i></a></li>';
	
			$social_icons 	.= '</ul>';
			$social_icons 	.= '</div>';
		}
		
		if($background) {
			$person_background = ' style="background-color:' . $background . ';padding:0 0 12px;"';
		} 

		if($type != 'default') {
			// Front content
			$output  .= '<div class="flex flip-container" ontouchstart="this.classList.toggle("hover");"><div class="flipper"><div'.$person_background.' class="front ' . $class . '">';
			$output  .= '<div class="sppb-addon sppb-addon-person ' . $alignment . '">';
			$output  .= '<div class="sppb-addon-content">';
			if($image) {
				$output  .= '<div class="sppb-person-image">';
				
				// Image
				$output  .= '<img class="sppb-img-responsive" src="' . $image . '" alt="' . $name . '">';
				$output  .= '</div>';
			}
	
			if($name || $designation) {
				$output  .= '<div class="sppb-person-information">';
				if($name) $output  .= '<span class="sppb-person-name">' . $name . '</span>';
				if($designation) $output  .= '<span class="sppb-person-designation">' . $designation . '</span>';
				$output  .= '</div>';
			}
			$output  .= '</div>';
			$output  .= '</div>';
			$output  .= '</div>';
			
		
			// Back content
			$output  .= '<div'.$person_background.' class="back ' . $class . '">';
			
			$output  .= '<div class="sppb-addon sppb-addon-person ' . $alignment . '">';
			$output  .= '<div class="sppb-addon-content">';
			
	
			
			if($name || $designation) {
				if($type != 'flip') {	
					$output  .= '<div class="sppb-person-block">';
				} else {
					$output  .= '<div class="sppb-person-block flip">';
				}
				if($type != 'flip') {
					if($image) {	
						$output  .= '<div class="thumb">';	
						// Image
						if(strpos($image, 'http://') !== false || strpos($image, 'https://') !== false){
							/* Lazyload for images with absolute URL */
							if($has_lazyload) {
								$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $image .'" alt="'.$name.'">';
							} else {
								$output .= '<img class="sppb-img-responsive" src="' . $image . '" alt="' . $name . '">';	
							}
						} else {
							/* Lazyload for images for relative URL (local image) */
							if($has_lazyload) {
								$output .= '<img class="lazyload sppb-img-responsive" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $image .'" alt="'.$name.'">';
							} else {
								$output .= '<img class="sppb-img-responsive" src="' . $image . '" alt="' . $name . '">';	
							}
						}
						$output  .= '</div>';
					}
				}
				if($type != 'flip') {	
					$output  .= '<div class="sppb-person-information">';
				} else {
					$output  .= '<div class="sppb-person-information flip">';
				}
				if($name) $output  .= '<span class="sppb-person-name">' . $name . '</span>';
				if($designation) $output  .= '<span class="sppb-person-designation">' . $designation . '</span>';
				$output  .= '</div>';
				$output  .= '</div>';
			}
			
			if($social_position=='before') $output .= $social_icons;
	
			if($introtext) { 
				$output  .= '<div class="sppb-person-introtext">' . $introtext . '</div>';
				if($type != 'flip') {
					$output  .= '<hr />';
				}
			}
	
			if($social_position=='after') $output .= $social_icons;
			
			$output  .= '</div>';
			$output  .= '</div>';
			
			$output  .= '</div>';
			
			$output  .= '</div></div>';
	
			return $output;
		
		} else {
			
			$person_background = '';
				$output  .= '<div class="sppb-addon sppb-addon-person ' . $alignment . ' ' . $class . '">';
				$output  .= '<div'.$person_background.' class="sppb-addon-content">';
				if($image) {
					$output  .= '<div class="sppb-person-image">';
					// Image
					$output  .= '<img class="sppb-img-responsive" src="' . $image . '" alt="' . $name . '">';
					$output  .= '</div>';
				}
		
				if($name || $designation) {
					$output  .= '<div class="sppb-person-information">';
					if($name) $output  .= '<span class="sppb-person-name">' . $name . '</span>';
					if($designation) $output  .= '<span class="sppb-person-designation">' . $designation . '</span>';
					$output  .= '</div>';
				}
		
				if($social_position=='before') $output .= $social_icons;
		
				if($introtext) $output  .= '<div class="sppb-person-introtext">' . $introtext . '</div>';
		
				if($social_position=='after') $output .= $social_icons;
		
				$output  .= '</div>';
				$output  .= '</div>';
		
				return $output;
		
		}
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;

		$border_radius = (isset($this->addon->settings->image_border_radius) && $this->addon->settings->image_border_radius) ? 'border-radius:' . $this->addon->settings->image_border_radius . 'px' : '';
		$css = '';
		if($border_radius) {
			$css .= $addon_id . ' .sppb-person-image img {';
			$css .= $border_radius;
			$css .= "\n" . '}' . "\n"	;
		}

		$name_color = (isset($this->addon->settings->name_color) && $this->addon->settings->name_color) ? 'color:' . $this->addon->settings->name_color : '';
		if($name_color) {
			$css .= $addon_id . ' .sppb-person-name {';
			$css .= $name_color;
			$css .= "\n" . '}' . "\n"	;
		}

		$designation_color = (isset($this->addon->settings->designation_color) && $this->addon->settings->designation_color) ? 'color:' . $this->addon->settings->designation_color : '';
		if($designation_color) {
			$css .= $addon_id . ' .sppb-person-designation {';
			$css .= $designation_color;
			$css .= "\n" . '}' . "\n"	;
		}

		return $css;
	}

	public static function getTemplate(){

		$output ='
			<div class="sppb-addon sppb-addon-person {{ data.alignment }} {{ data.class}}">
				<div class="sppb-addon-content">
				<# if(!_.isEmpty(data.image)) { #>
					<div class="sppb-person-image">
						<# if(data.image.indexOf("https://") == -1 && data.image.indexOf("http://") == -1){ #>
							<img class="sppb-img-responsive" src=\'{{ pagebuilder_base + data.image }}\' alt="{{ data.name }}">
						<# } else { #>
							<img class="sppb-img-responsive" src=\'{{ data.image }}\' alt="{{ data.name }}">
						<# } #>
					</div>
				<# } #>

				<# if(data.name || data.designation || data.email ){ #>
					<div class="sppb-person-information">
						<# if(!_.isEmpty(data.name)) { #>
							<span class="sppb-person-name sp-inline-editable-element" data-id={{data.id}} data-fieldName="name" contenteditable="true">{{ data.name}}</span>
						<# } #>
						<# if(!_.isEmpty(data.designation)) { #>
							<span class="sppb-person-designation sp-inline-editable-element" data-id={{data.id}} data-fieldName="designation" contenteditable="true">{{ data.designation}}</span>
						<# } #>
						<# if(!_.isEmpty(data.email)) { #>
							<span class="sppb-person-email">{{ data.email}}</span>
						<# } #>
					</div>
				<# } #>

				<# if(data.social_position == "after") { #>
					<# if(!_.isEmpty(data.introtext)) { #>
						<div class="sppb-person-introtext sp-inline-editable-element" data-id={{data.id}} data-fieldName="introtext" contenteditable="true">{{ data.introtext }}</div>
					<# } #>
				<# } #>

				<# if ( data.facebook || data.twitter || data.google_plus || data.youtube || data.linkedin || data.pinterest || data.flickr || data.dribbble || data.behance || data.instagram ) { #>
					<div class="sppb-person-social-icons">
					<ul class="sppb-person-social">
						<# if (!_.isEmpty(data.facebook)) { #>
							<li><a target="_blank" href=\'{{ data.facebook }}\'><i class="fa fa-facebook"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.twitter)) { #>
							<li><a target="_blank" href=\'{{ data.twitter }}\'><i class="fa fa-twitter"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.google_plus)) { #>
							<li><a target="_blank" href=\'{{ data.google_plus }}\'><i class="fa fa-google-plus"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.youtube)) { #>
							<li><a target="_blank" href=\'{{ data.youtube }}\'><i class="fa fa-youtube"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.linkedin)) { #>
							<li><a target="_blank" href=\'{{ data.linkedin }}\'><i class="fa fa-linkedin"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.pinterest)) { #>
							<li><a target="_blank" href=\'{{ data.pinterest }}\'><i class="fa fa-pinterest"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.flickr)) { #>
							<li><a target="_blank" href=\'{{ data.flickr }}\'><i class="fa fa-flickr"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.dribbble)) { #>
							<li><a target="_blank" href=\'{{ data.dribbble }}\'><i class="fa fa-dribbble"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.behance)) { #>
							<li><a target="_blank" href=\'{{ data.behance }}\'><i class="fa fa-behance"></i></a></li>
						<# } #>
						<# if (!_.isEmpty(data.instagram)) { #>
							<li><a target="_blank" href=\'{{ data.instagram }}\'><i class="fa fa-instagram"></i></a></li>
						<# } #>
					</ul>
					</div>
				<# } #>

				<# if(data.social_position == "before") { #>
					<# if(!_.isEmpty(data.introtext)) { #>
						<div class="sppb-person-introtext">{{{ data.introtext }}}</div>
					<# } #>
				<# } #>

				</div>
			</div>

			<style type="text/css">
				<# if(data.image_border_radius) { #>
					#sppb-addon-{{ data.id }} .sppb-person-image img {
						border-radius: {{data.image_border_radius}}px;
					}
				<# } #>
				<# if(data.name_color) { #>
					#sppb-addon-{{ data.id }} .sppb-person-name {
						color: {{data.name_color}};
					}
				<# } #>
				<# if(data.designation_color) { #>
					#sppb-addon-{{ data.id }} .sppb-person-designation {
						color: {{data.designation_color}};
					}
				<# } #>
			</style>

			';

			return $output;
	}

}
