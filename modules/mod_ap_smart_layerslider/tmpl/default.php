<?php
/**
 * AP Smart LayerSlider for Joomla 3.x
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2019 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;


if ($params->get('load_videojs') == '1') { 
// video.js css
$doc->addStyleSheet($baseUri.'assets/js/video_js/video-js.min.css');
// video.js
$doc->addScript($baseUri.'assets/js/video_js/video.js');
}

//Path to module's style php
$module_style = 'themes/style'.$theme.'/style'.$theme;

require_once JModuleHelper::getLayoutPath($moduleName, $module_style);
?>
<style type="text/css">
<?php if ($arrows_size != "") { ?>
  #ap-smart-layerslider-<?php echo $ext_id; ?> .sp-arrow{font-size:<?php echo $arrows_size; ?>px;width:<?php echo $arrows_size * 1.4; ?>px;}
<?php } ?>	
.sp-horizontal .sp-arrow {margin-top:-<?php echo $arrows_size / 1.5; ?>px;}
.sp-vertical .sp-arrow {margin-left:-<?php echo $arrows_size / 1.5; ?>px;}
<?php //echo ($arrows_size != "") ? "#ap-smart-layerslider-".$ext_id." .sp-arrow{font-size:".$arrows_size."px;}\n" : "" ?>
<?php echo ($arrows_backg_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-arrow{background:".$arrows_backg_color.";width:".$arrows_backg_color."}\n" : "" ?>
<?php echo ($arrows_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-arrow{color:".$arrows_color.";}\n" : "" ?>
<?php echo ($show_thumbnails == 1 && $thumbnailPointer_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-has-pointer .sp-selected-thumbnail:before {border-color:".$thumbnailPointer_color.";}#ap-smart-layerslider-".$ext_id." .sp-has-pointer .sp-selected-thumbnail:after {color:".$thumbnailPointer_color.";}" : "" ?> 
<?php echo ($show_thumbnails == 1 && $selected_thumbnail_txt_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-selected-thumbnail .sp-thumbnail-text {color:".$selected_thumbnail_txt_color.";}#ap-smart-layerslider-".$ext_id." .sp-thumbnails .sp-selected-thumbnail .sp-thumbnail {color:".$selected_thumbnail_txt_color.";}" : "" ?> 
<?php echo ($show_thumbnails == 1 && $selected_thumbnail_backg_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-selected-thumbnail .sp-thumbnail-text,#ap-smart-layerslider-".$ext_id." .sp-selected-thumbnail .sp-thumbnail {background-color:".$selected_thumbnail_backg_color.";}" : "" ?> 
<?php echo ($show_thumbnails == 1) ? "#ap-smart-layerslider-".$ext_id." .sp-thumbnail {text-align:".$thumbnailtxt_align.";}\n" : "" ?>
<?php echo ($display_caption == 1) ? "#ap-smart-layerslider-".$ext_id." .sp-caption-container {text-align:".$captiontxt_align.";}\n" : "" ?>
<?php echo ($buttons_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-button{border-color:".$buttons_color.";}\n" : "" ?>
<?php echo ($buttons_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-selected-button{background-color:".$buttons_color.";}" : "" ?> 
<?php echo ($fullscreen_button_color != "") ? "#ap-smart-layerslider-".$ext_id." .sp-full-screen-button:before{color:".$fullscreen_button_color.";}\n" : "" ?>
@media (max-width: 979px) {
<?php if ($arrows_size != "") { ?>#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-arrow{font-size:<?php echo $arrows_size - 5; ?>px;width:<?php echo $arrows_size + 5; ?>px;}<?php } ?>	
}
</style>
<?php 
/* URL to the Flash SWF (Video.js) */ 
if ($params->get('load_videojs') == '1') { ?>
<script>videojs.options.flash.swf = "../assets/js/video_js/video-js.swf";</script>
<?php } ?>

