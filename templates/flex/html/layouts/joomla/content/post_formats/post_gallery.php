<?php
/**
 * Flex @package Helix3 Framework
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined('JPATH_BASE') or die;

$doc = JFactory::getDocument();
$app = JFactory::getApplication();
JHtml::_('jquery.framework');
//$doc->addScript( JURI::base(true) . '/templates/'.$app->getTemplate().'/js/imagesloaded.min.js');

$items_per_row = ($displayData['params']->get('items_per_row') != '') ? $items_per_row = $displayData['params']->get('items_per_row') : $items_per_row = '1';
$margin_between_items = ($displayData['params']->get('$margin_between_items') != '' || $displayData['params']->get('items_per_row') != '') ? $margin_between_items = 'slideMargin:' . $displayData['params']->get('margin_between_items') .',' : $margin_between_items = 'slideMargin:0,';
$transition_effect = $displayData['params']->get('transition_effect');
$transition_duration = ($displayData['params']->get('transition_duration') != 0) ? $transition_duration = $displayData['params']->get('transition_duration') : $transition_duration = '700';
$show_arrows = $displayData['params']->get('show_arrows', 1);
$arrow_size = ($displayData['params']->get('arrow_size') != '') ? $arrow_size = $displayData['params']->get('arrow_size') : $arrow_size = '44';
$arrow_icons = $displayData['params']->get('arrow_icons');
$arrows_bg = ($displayData['params']->get('arrows_bg') != '') ? $arrows_bg = 'background:' . $displayData['params']->get('arrows_bg') . ';' : $arrows_bg = 'background:rgba(10,10,10,.3);';
$arrows_color = ($displayData['params']->get('arrows_color') != '') ? $arrows_color = '.lSAction > a {color:' . $displayData['params']->get('arrows_color') . '}' : $arrows_color = '';
$arrows_color_style = ($displayData['params']->get('arrows_color') != '') ? $arrows_color_style = 'color:' . $displayData['params']->get('arrows_color') . ';' : $arrows_color_style = '';
$show_pager = $displayData['params']->get('show_pager');
$autoplay = $displayData['params']->get('autoplay', 1);
$auto_transition = ($displayData['params']->get('autoplay') != 0) ? $auto_transition = $displayData['params']->get('auto_transition') . '000' : $auto_transition = '5000';
$ltr_rtl = $displayData['params']->get('ltr_rtl');
$items_per_row > 2 ? $responsive_items_per_row = ( $displayData['params']->get('items_per_row') - 1 ) : $responsive_items_per_row = '1';
	
$left_arrow = '';
$right_arrow = '';
if ($arrow_icons == 'pixeden-circle') {
		$left_arrow = 'pe pe-7s-angle-left-circle';
	$right_arrow = 'pe pe-7s-angle-right-circle';
} elseif ($arrow_icons == 'fontawesome-angle') {
	$left_arrow = 'fa fa-angle-left';
	$right_arrow = 'fa fa-angle-right';
} elseif ($arrow_icons == 'fontawesome-chevron') {
	$left_arrow = 'fa fa-chevron-left';
	$right_arrow = 'fa fa-chevron-right';
} else {
	$left_arrow = 'pe pe-7s-angle-left';
	$right_arrow = 'pe pe-7s-angle-right';
}
?>
<?php 
if($displayData['params']->get('gallery')) {
	$images = json_decode( $displayData['params']->get('gallery') );
	$randomid = rand(1,1000);
	// Add CSS styling
	if ($show_arrows == 1) {
	$arrows_style = '.lSPrev,.lSNext{'
		. 'height:'.$arrow_size.'px;'
		. 'width:'.$arrow_size.'px;'
		. '}'
		. '.lSPrev>i,.lSNext>i{'
		. 'font-size:'.$arrow_size.'px;}'; 
		$doc->addStyledeclaration($arrows_style);
	}
	$html = '';
	$html .= '<div class="entry-gallery">';
		if( count( $images->gallery_images ) ) {     
			$html .= '<ul id="post-slider-'. $randomid .'" class="list-unstyled post-slides">';
			foreach ( $images->gallery_images as $key => $image ) {  
			    $active = ($key===0) ? ' active' : '';
				$html .= '<li class="item'. $active .'">';
				$html .= '<img src="'. $image .'" alt="'. JFactory::getApplication()->get('sitename') .'">';
				$html .= '</li>';
			} 
			$html .= '</ul>';  
		}
	$html .= '</div>';
	echo $html;
} ?>
<script type="text/javascript">
 jQuery(function($){
	var $post_slider = $("#post-slider-<?php echo $randomid; ?>");
	$post_slider.imagesLoaded(function(){
	$($post_slider).lightSlider({
		<?php if ($show_arrows == 0) { ?>controls: false,<?php } ?>
prevHtml:'<i style="width:<?php echo $arrow_size; ?>px;height:<?php echo $arrow_size; ?>px;margin-top:-<?php echo round($arrow_size / 2); ?>px;font-size:<?php echo $arrow_size; ?>px;<?php echo $arrows_color_style . $arrows_bg; ?>" class="<?php echo $left_arrow; ?>"></i>',
		nextHtml:'<i style="width:<?php echo $arrow_size; ?>px;height:<?php echo $arrow_size; ?>px;margin-top:-<?php echo round($arrow_size / 2); ?>px;font-size:<?php echo $arrow_size; ?>px;<?php echo $arrows_color_style . $arrows_bg; ?>" class="<?php echo $right_arrow; ?>"></i>',
		item:<?php echo $items_per_row; ?>,
		slideMove:<?php echo $items_per_row; ?>,
		loop:true,
		<?php echo $margin_between_items; ?>
		
		pauseOnHover:true,
		<?php if ($autoplay == 1) { ?>auto:true,
		pause:<?php echo $auto_transition; ?>,
		<?php } ?>
speed:<?php echo $transition_duration; ?>,
		<?php if ($show_pager == 0) { ?>pager:false,<?php } ?>
<?php if ($transition_effect == 'fade') { ?>mode:'fade',<?php } ?>
<?php if ($ltr_rtl == 2) { ?>rtl:true,<?php } ?>
		
		cssEasing: 'cubic-bezier(0.75, 0, 0.35, 1)',
		adaptiveHeight:true,
		keyPress:true,
		responsive:[{breakpoint:768,settings:{item:<?php echo $responsive_items_per_row; ?>}},{breakpoint:480,settings:{item:1}}]
		});
	});
});
</script>