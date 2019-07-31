<?php
/**
 * AP Smart LayerSlider Module
 * @author		Aplikko
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2019 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();
//Params
$moduleclass_sfx = $params->get('moduleclass_sfx');
$moduleName = basename(dirname(__FILE__));
$baseUri = JURI::root(true) . '/modules/mod_ap_smart_layerslider/';
$ext_id = "mod_".$module->id;
$moduleId = $module->id;
//Themes
$theme = $params->get('theme', '1');
if (empty($theme)) $theme = '1';


//Slides
$image_width = $params->get('image_width');
$image_height = $params->get('image_height');
$forceSize = $params->get('forceSize', 'none');
$visibleSize = $params->get('visibleSize', 'auto');
$slideDistance = $params->get('slideDistance', 10);
$responsive = $params->get('responsive', 1);
$imageScaleMode = $params->get('imageScaleMode', 'cover');
$autoHeight = $params->get('autoHeight', 1);
$autoScaleLayers = $params->get('autoScaleLayers', 1);
$waitForLayers = $params->get('waitForLayers', 0);
$orientation = $params->get('orientation', 'horizontal');
$loop = $params->get('loop', 1);
$shuffle = $params->get('shuffle', 0);
$fullScreen = $params->get('fullScreen', 0);
$fullscreen_button_color = $params->get('fullscreen_button_color');
// Fade
$fadeEffect = $params->get('fadeEffect');
$fadeOutPreviousSlide = $params->get('fadeOutPreviousSlide');
$fadeDuration = $params->get('fadeDuration', 500);
// Autoplay
$autoplay = $params->get("autoplay", 0);
$autoplayDelay = $params->get('autoplayDelay', 5000);
$autoplayOnHover = $params->get('autoplayOnHover', 'pause');

// Thumbnails
$show_thumbnails = $params->get('show_thumbnails', 0);
$thumbnailWidth = $params->get('thumbnailWidth', 120);
$thumbnailHeight = $params->get('thumbnailHeight', 80);
$thumbnailtxt_align = $params->get('thumbnailtxt_align', 'center');
$thumbnailsPosition = $params->get('thumbnailsPosition', 'bottom');
$thumbnailPointer = $params->get('thumbnailPointer', 0);
$thumbnailPointer_color = $params->get('thumbnailPointer_color');
$thumbnailArrows = $params->get('thumbnailArrows', 0);
$show_thumbnail_description = $params->get('show_thumbnail_description', 1);
$selected_thumbnail_backg_color = $params->get('selected_thumbnail_backg_color');
$selected_thumbnail_txt_color = $params->get('selected_thumbnail_txt_color');

// Arrows
$show_arrows = $params->get('show_arrows', 1);
$arrows_size = $params->get('arrows_size', 50); 
$arrows_backg_color = $params->get('arrows_backg_color');
$arrows_color = $params->get('arrows_color');

// Buttons
$show_buttons = $params->get('show_buttons', 1);
$buttons_color = $params->get('buttons_color');

// Captions
$display_caption = $params->get('display_caption', 0);
$captiontxt_align = $params->get('captiontxt_align', 'center');
$description_max_chars = $params->get('description_max_chars', 70);

// Video Options
$load_videojs = $params->get('load_videojs', 1);
$reachVideoAction = $params->get('reachVideoAction', 'none');
$leaveVideoAction = $params->get('leaveVideoAction', 'pauseVideo');
$playVideoAction = $params->get('playVideoAction', 'stopAutoplay');
$pauseVideoAction = $params->get('pauseVideoAction', 'none');
$endVideoAction = $params->get('endVideoAction', 'none');

// Way to load javascript
$load_js = $params->get('load_js', 'customtag');

//Get list from Helper
$lists = modApSmartLayersliderHelper::getList($params);

if (isset($lists) && count($lists) > 0) :		
//include css
$doc->addStyleSheet($baseUri.'assets/css/slider-pro.css');

//include js
if ($load_js == 'customtag') { 
  $doc->addCustomTag('<script src="'.$baseUri.'assets/js/jquery.sliderPro.packed.js" type="text/javascript"></script>');
} else {
  $doc->addScript($baseUri.'assets/js/jquery.sliderPro.packed.js');
}
	
endif;

require JModuleHelper::getLayoutPath('mod_ap_smart_layerslider', $params->get('layout', 'default'));
