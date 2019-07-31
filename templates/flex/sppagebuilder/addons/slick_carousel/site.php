<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined('_JEXEC') or die;

AddonParser::addAddon('sp_slick_carousel','sp_slick_carousel_addon');
AddonParser::addAddon('sp_slick_carousel_item','sp_slick_carousel_item_addon');

$sppbSlickCarouselParam = array();

function sp_slick_carousel_addon($atts, $content){
	
	global $sppbSlickCarouselParam;

	$doc = JFactory::getDocument();
	$app = JFactory::getApplication();
	$doc->addStylesheet( JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/slick_carousel/assets/css/slick.css');
	$doc->addScript( JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/slick_carousel/assets/js/slick.min.js');


	extract(spAddonAtts(array(
		"infiniteloop" 		      => '',
		"lazyloading" 		      => '',
		"slidestoshow" 		      => '',
		"slidestoscroll" 		  => '',
		"autoplay" 		          => '',
		"autoplay_interval"       => '',
		"fade_effect"    	      => '',
		"speed"    			      => '',
		"arrows" 		          => '',
		"arrows_size" 			  => '',
		"arrows_color"		      => '',
		"arrows_background_color" => '',
		"arrows_class"			  => '',
		"counter" 		          => '',
		"counter_color"           => '',
		"dots"     				  => '',
		"autoheight"    	      => '',
		"spacing"			      => '',
		"rtl_support"			  => '',
		"breakpoint1"			  => '',
		"slidestoshow_break1"	  => '',
		"breakpoint2"			  => '',
		"slidestoshow_break2"	  => '',
		"breakpoint3"			  => '',
		"slidestoshow_break3"	  => '',
		"title"					  => '',
		"heading_selector" 		  => 'h3',
		"title_fontsize" 		  => '',
		"title_fontweight" 		  => '',
		"title_text_color" 		  => '',
		"title_margin_top" 		  => '',
		"title_margin_bottom" 	  => '',		
		"class"					  => '',
		), $atts));

	if($slidestoshow == '') {
		$slidestoshow = 1;
	}
	if($slidestoscroll == '') {
		$slidestoscroll = 1;
	}
		
	if ($dots == '1') {
		//with dots
		$arrows_margin_top = 'margin-top:-' . ( $arrows_size / 1.2 ) . 'px;';
	} else {
		//without dots
		$arrows_margin_top = 'margin-top:-' . ( $arrows_size / 2.2 ) . 'px;';
	}
	
	if($spacing=='') {
		$spacing = 0;
	}
	
	
	$sppbSlickCarouselParam['spacing'] = (int) $spacing;
	$sppbSlickCarouselParam['lazyloading'] = $lazyloading;
	
	$infiniteloop == 0 ? $infiniteloop = 'infinite:false,' : $infiniteloop = '';
	$lazyloading == 1 ? $lazyloading = 'lazyLoad:\'ondemand\',' : $lazyloading = '';
	if($autoplay_interval == '') {$autoplay_interval = '5000';} 
	
	$autoplay == 1 ? $autoplay = 'autoplay: true,autoplaySpeed: '.$autoplay_interval.',' : $autoplay = '';
	$fade_effect == 1 ? $fade_effect = 'fade:true,' : $fade_effect = '';
	$arrows == 0 ? $arrows = 'arrows:false,' : $arrows = '';
	$dots == 1 ? $dots = 'dots:true,' : $dots = '';
	$autoheight == 1 ? $autoheight = 'adaptiveHeight:true,' : $autoheight = '';

	// RTL Support
	if ($rtl_support == 1) { 
		$rtl_support = 'rtl:true,';
		$rtl = ' dir="rtl"';
	} else {
		$rtl_support = '';
		$rtl = '';
	}
	
	$speed == '' ? $speed = 'speed:500,' : $speed = 'speed:'.(int) $speed.',';

	$breakpoint1 == '' ? $breakpoint1 = '992' : $breakpoint1 = (int) $breakpoint1;
	$breakpoint2 == '' ? $breakpoint2 = '768' : $breakpoint2 = (int) $breakpoint2;
	$breakpoint3 == '' ? $breakpoint3 = '480' : $breakpoint3 = (int) $breakpoint3;

    //random ID number to avoid conflict if there is more then one Slick carousel on the same page
	$randomid = rand(1,1000);

	$arrows_size != '' ? $arrows_size_style = 'font-size:'. $arrows_size.'px;' : $arrows_size_style = 'font-size:44px;';
	$arrows_color != '' ? $arrows_color_style = 'color:'.$arrows_color.';' : $arrows_color_style = '';
	$arrows_background_color != '' ? $arrows_background_color_style = 'background-color:'.$arrows_background_color.';' : $arrows_background_color_style = '';
	$arrows_class != '' ? $arrows_class = ' '.$arrows_class : $arrows_class = '';
		if ($arrows_size || $arrows_color || $arrows_background_color) {
			$arrow_style = ' style="' . $arrows_size_style . $arrows_color_style . $arrows_background_color_style . '"'; 
		}

	$var_counter = '';
	$show_counter = '';	
	$counter_height = '';

	if ($counter == 1) { 
	$var_counter = '
		var total_slides;
		$slick_carousel.on("init reInit afterChange", function (event, slick, currentSlide) {
			var prev_slide_index, next_slide_index, current;
			var $prev_counter = $slick_carousel.find(".slick-prev .slick-counter");
			var $next_counter = $slick_carousel.find(".slick-next .slick-counter");
			total_slides = slick.slideCount;
			current = (currentSlide ? currentSlide : 0) + 1;
			prev_slide_index = (current - 1 < 1) ? total_slides : current - 1;
			next_slide_index = (current + 1 > total_slides) ? 1 : current + 1;
			$prev_counter.text(prev_slide_index + "/" + total_slides);
			$next_counter.text(next_slide_index + "/"+ total_slides);
		});
		';
		$counter = '1' ? $show_counter = '<h4 class="slick-counter"></h4>' : $show_counter = '';
		$counter_color != '' ? $counter_color = 'color:'.$counter_color.';' : $counter_color = '';
		$arrows_size != '' ? $counter_height = 'line-height:' . ( $arrows_size - 4 ) . 'px;' : $counter_height = 'line-height:40px;';
	} 

	
	// Add JS
	$js = 'jQuery(function($){
		var $slick_carousel = $(".slick-carousel-'.$randomid.'");
		jQuery(document).ready(function(){ 
		   '.$var_counter.'
    		$slick_carousel.slick({
			  '.$infiniteloop.'
			  '.$lazyloading.'
			  slidesToShow: ' . $slidestoshow . ',
			  slidesToScroll: ' . $slidestoscroll . ',
			  nextArrow: \'<span'.$arrow_style.' class="slick-next'.$arrows_class.'">'.$show_counter.'<i style="'.$arrows_size_style.'" class="pe pe-7s-angle-right"></i></span>\',
			  prevArrow: \'<span'.$arrow_style.' class="slick-prev'.$arrows_class.'">'.$show_counter.'<i style="'.$arrows_size_style.'" class="pe pe-7s-angle-left"></i></span>\',
			  '.$rtl_support.'
			  '.$autoplay.'
			  '.$fade_effect.'
			  '.$speed.'
			  '.$arrows.'
			  '.$dots.'
			  '.$autoheight.' 
			  cssEase: \'cubic-bezier(0.635, 0.010, 0.355, 1.000)\',
			  responsive: [
				{
				  breakpoint:'.$breakpoint1.',
				  settings: {
					slidesToShow:'.$slidestoshow_break1.',
					slidesToScroll:'.$slidestoshow_break1.'
				  }
				},
				{
				  breakpoint:'.$breakpoint2.',
				  settings: {
					slidesToShow:'.$slidestoshow_break2.',
					slidesToScroll:'.$slidestoshow_break2.'
				  }
				},
				{
				  breakpoint:'.$breakpoint3.',
				  settings: {
					slidesToShow:'.$slidestoshow_break3.',
					slidesToScroll:'.$slidestoshow_break3.'
				  }
				}
			  ]
			});
  		});
	});'; 
	$js = preg_replace(array('/([\s])\1+/', '/[\n\t]+/m'), '', $js); // Remove whitespace
	$doc->addScriptdeclaration($js);


	$slick_slide_spacing = $sppbSlickCarouselParam['spacing'];
	
	$sppbSlickCarouselParam['spacing'] != 0 ? $slick_slide_spacing = '-' . $sppbSlickCarouselParam['spacing'] / 2 . 'px' : $slick_slide_spacing = 'auto';

	// Add styles
	$style = ''
			. '.slick-carousel-'.$randomid.' .slick-slide{margin:0 ' . $sppbSlickCarouselParam['spacing'] / 2 .'px;}'
			. '.slick-carousel-'.$randomid.' .slick-list{margin:0 ' . $slick_slide_spacing . ';}'
			. '.slick-carousel-'.$randomid.' .slick-prev,.slick-carousel-'.$randomid.' .slick-next {' . $arrows_margin_top . $arrows_background_color_style . '}'
			. '.slick-carousel-'.$randomid.' .slick-prev i.pe, .slick-carousel-'.$randomid.' .slick-next i.pe {'.$arrows_size_style.'color:'.$arrows_color.'}'
			;		 
	$doc->addStyleDeclaration($style);
	
	
	if ($counter != '0') { 	
		$style_counter = '.slick-carousel-'.$randomid.' .slick-prev .slick-counter {left:-' . $arrows_size . 'px;height:'.$arrows_size.'px;width:' . $arrows_size . 'px;' . $counter_color . $counter_height . '}'
		. '.slick-carousel-'.$randomid.' .slick-next .slick-counter {right:-' . $arrows_size . 'px;height:'.$arrows_size.'px;width:' . $arrows_size . 'px;' . $counter_color . $counter_height . '}';
		 
		$doc->addStyleDeclaration($style_counter);
	}
	
	$output  = '<div class="sppb-addon ' . $class . '">';

	if($title) {

		$title_style = '';
		if($title_margin_top !='') $title_style .= 'margin-top:' . (int) $title_margin_top . 'px;';
		if($title_margin_bottom !='') $title_style .= 'margin-bottom:' . (int) $title_margin_bottom . 'px;';
		if($title_text_color) $title_style .= 'color:' . $title_text_color  . ';';
		if($title_fontsize) $title_style .= 'font-size:'.$title_fontsize.'px;line-height:'.$title_fontsize.'px;';
		if($title_fontweight) $title_style .= 'font-weight:'.$title_fontweight.';';

		$output .= '<'.$heading_selector.' class="sppb-addon-title" style="' . $title_style . '">' . $title . '</'.$heading_selector.'>';
	}

	$output .= '<div'.$rtl.' class="slick-carousel-'.$randomid.' clearfix">';
	$output .= AddonParser::spDoAddon($content);
	$output .= '</div>';
	
	$output .= '</div>';

	$sppbSlickCarouselParam = array();

	return $output;

}

function sp_slick_carousel_item_addon( $atts ){

	global $sppbSlickCarouselParam;

	extract(spAddonAtts(array(
		"title"=>'',
		"thumb"=>'',
		"thumb_url"=>'',
		"url_target"=>'',
		"description"=>'',
		), $atts));

	$output = '';
	
	if($title!='') {
		 $maintitle = '<h3>' . nl2br($title) . '</h3>';
	} else {
		$maintitle = '';
	}
	$url_target = (isset($url_target) && $url_target) ? ' target="' . $url_target . '"' : '';
	
	if($thumb) {
		$output .= '<div class="slick-img">';
		$output .= '';
		$output .= ($thumb_url !='') ? '<a href="'.$thumb_url.'"'.$url_target.'>' : '';
		$output .= '<img ';
		$output .= $sppbSlickCarouselParam['lazyloading'] == 1 ? 'data-lazy="'. JURI::root() . $thumb . '" alt="'. $title. '"' : 'src="'. $thumb . '" alt="'. $title. '"';
		$output .= '>';
		$output .= ($thumb_url !='') ? '</a>' : '';
		$output .= $description != '' ? '<div class="slick-desc">' . $description . '</div>' : '';
		$output .= '</div>';
	} else {
		$output .= '<div class="slick-img no-bckg-img">';	
		$output .= $description != '' ? '<div class="slick-desc">' .$maintitle . $description . '</div>' : '';
		$output .= '</div>';
	}

	return $output;

}