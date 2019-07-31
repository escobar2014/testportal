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

class SppagebuilderAddonTestimonialflex extends SppagebuilderAddons {

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : '';

		//Options
		$autoplay = (isset($this->addon->settings->autoplay) && $this->addon->settings->autoplay) ? ' data-sppb-ride="sppb-carousel"' : '';
		$arrows = (isset($this->addon->settings->arrows) && $this->addon->settings->arrows) ? $this->addon->settings->arrows : '';
		
		$autoplay_interval = (isset($this->addon->settings->autoplay_interval) && $this->addon->settings->autoplay_interval) ? ((int) $this->addon->settings->autoplay_interval) : 5000;
		
		$avatar_size = (isset($this->addon->settings->avatar_size) && $this->addon->settings->avatar_size) ? $this->addon->settings->avatar_size : '120';

		
		$carousel_autoplay = ($autoplay) ? 'data-sppb-ride="sppb-carousel"' : '';
		$carousel_autoplay_interval = ($autoplay) ? ' data-interval="'.$autoplay_interval.'"' : '';
	
		$output  = '<div class="sppb-carousel sppb-testimonial-flex sppb-slide ' . $class . ' sppb-text-center" ' . $carousel_autoplay . $carousel_autoplay_interval .'>';
		
		$output .= '<div class="sppb-carousel-inner">';

		foreach ($this->addon->settings->sp_testimonialflex_item as $key => $value) {
			$avatar = (isset($this->addon->settings->avatar) && $this->addon->settings->avatar) ? $this->addon->settings->avatar : '';
			$message = (isset($this->addon->settings->message) && $this->addon->settings->message) ? $this->addon->settings->message : '';
			$avatar_position = (isset($this->addon->settings->avatar_position) && $this->addon->settings->avatar_position) ? $this->addon->settings->avatar_position : 'left';
			$avatar_style = (isset($this->addon->settings->avatar_style) && $this->addon->settings->avatar_style) ? $this->addon->settings->avatar_style : '';
			$title = (isset($value->title) && $value->title) ? $value->title : '';
			$url = (isset($value->url) && $value->url) ? $value->url . '' : '';
			$link_target = (isset($value->link_target) && $value->link_target) ? $link_target = ' target="' . $value->link_target . '"' : '';
		
			$output   .= '<div class="sppb-item ' . (($key == 0) ? ' active' : '') .'">';
			
			$name = (isset($value->title) && $value->title) ? '<h4 class="pro-client-name">'. $value->title .'</h4>' : '';
			
			if($url) $name .= '<a' . $link_target . ' href="'.$url.'"><em class="pro-client-url">'. $url .'</em></a>';
			
			$output .= '<div class="sppb-media flex">';
			$output .= '<div class="pull-'.$value->avatar_position.'">';
			
			if (isset($value->avatar) && $value->avatar) { 
				// Image
				if(strpos($value->avatar, 'http://') !== false || strpos($value->avatar, 'https://') !== false){
					/* Lazyload for images with absolute URL */
					if($has_lazyload) {
						$output .= '<img class="lazyload sppb-img-responsive sppb-avatar '. $value->avatar_style .'" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. $value->avatar .'" height="' . $avatar_size . '" width="' . $avatar_size . '" alt="'. $value->title .'">';
					} else {
						$output .= '<img src="'.$value->avatar.'" height="' . $avatar_size . '" width="' . $avatar_size . '" class="sppb-img-responsive sppb-avatar '. $value->avatar_style .'" alt="'.$value->title.'">';	
					}
				} else {
					/* Lazyload for images for relative URL (local image) */
					if($has_lazyload) {
						$output .= '<img class="lazyload sppb-img-responsive sppb-avatar '. $value->avatar_style .'" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'. JUri::root() . $value->avatar .'" height="' . $avatar_size . '" width="' . $avatar_size . '" alt="'. $value->title .'">';
					} else {
						$output .= '<img src="'.$value->avatar.'" height="' . $avatar_size . '" width="' . $avatar_size . '" class="sppb-img-responsive sppb-avatar '. $value->avatar_style .'" alt="'.$value->title.'">';	
					}
				}
			}
			$output .= '</div>';
			
			$output .= '<div style="text-align:'.$value->avatar_position.'" class="sppb-media-body">';
			if($class == 'flex') $output .= '<i class="fa fa-quote-left"></i>';
			$output .= $value->message;
			if($class == 'flex') $output .= '<i class="fa fa-quote-right"></i>';
			if($title) $output .= '<div class="sppb-testimonial-client">' . $name . '</div>';
			$output .= '</div>';
			
			$output .= '</div>';
			$output  .= '</div>';
		}
		
		$output	.= '</div>';
		
		if($arrows) {
			$output	.= '<a class="left sppb-carousel-control" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>';
			$output	.= '<a class="right sppb-carousel-control" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>';
		}

		$output .= '</div>';

		return $output;

	}

	public function css() {
		$avatar = (isset($this->addon->settings->avatar) && $this->addon->settings->avatar) ? $this->addon->settings->avatar : '';
		$avatar_style = (isset($this->addon->settings->avatar_style) && $this->addon->settings->avatar_style) ? $this->addon->settings->avatar_style : '';
		$avatar_size = (isset($this->addon->settings->avatar_size) && $this->addon->settings->avatar_size) ? $this->addon->settings->avatar_size : '120';
		$avatar_border_radius = (isset($this->addon->settings->avatar_style) && $this->addon->settings->avatar_style) == 'sppb-img-circle' ? $avatar_border_radius = 'width:'. $avatar_size .'px;height:'. $avatar_size .'px;object-fit:cover;border-radius:'. $avatar_size .'px;' : $avatar_border_radius = '';
		
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$css = '';

		$css .= $addon_id.' .sppb-carousel-inner .sppb-media img.sppb-img-circle {width:'. $avatar_size .'px;height:'. $avatar_size .'px;object-fit:cover;border-radius:50%;}';
		
		return $css;
	}


	public static function getTemplate(){

		$output = '
			<#
				var autoplay = (data.autoplay) ? \' data-sppb-ride="sppb-carousel"\' : "";
				var interval = (data.autoplay_interval) ? \' data-interval="{{ data.autoplay_interval }}"\' :5000;
				
				var avatar_size = (data.avatar_size) ? data.avatar_size : "120";
			#>
			
			<div class="sppb-carousel sppb-testimonial-flex sppb-slide {{ data.class }} sppb-text-center" {{ autoplay }} {{ interval }}>
			
			<div class="sppb-carousel-inner">
			
				<# _.each(data.sp_testimonialflex_item, function(testimonial_item, key){
					var activeClass = (key == 0) ? " active" : ""; 
					#>
						<div class="sppb-item {{ activeClass }}">
							<div class="sppb-media flex">
								<div class="pull-{{ testimonial_item.avatar_position }}">
									<img class="sppb-img-responsive sppb-avatar {{ testimonial_item.avatar_style }}" src="{{ testimonial_item.avatar }}" height="{{ avatar_size }}" width="{{ avatar_size }}" alt="{{ testimonial_item.title }}" />
								</div>
								<div style="text-align:{{ testimonial_item.avatar_position }}" class="sppb-media-body">
									<# if(data.class == "flex") { #>
										<i class="fa fa-quote-left"></i>
									<# } #>
									{{ testimonial_item.message }}
									<# if(data.class == "flex") { #>
										<i class="fa fa-quote-right"></i>
									<# } #>
									<div class="sppb-testimonial-client">
										<h4 class="pro-client-name">{{ testimonial_item.title }}</h4>
										<# if(testimonial_item.url) { #>
											<a href="{{ testimonial_item.url }}"><em class="pro-client-url">{{ testimonial_item.url }}</em></a>
										<# } #>
									</div>
								</div>	
							</div>
						</div>
					<# }); #>
				</div>
				<# if(data.arrows) { #>
					<a class="left sppb-carousel-control" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>
					<a class="right sppb-carousel-control" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>
				<# } #>
			</div>
			';

		return $output;
	}

}
