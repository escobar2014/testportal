<?php
/**
* @package Helix3 Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2018 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
defined('_JEXEC') or die('Restricted Access');

//helper & model
$menu_class   = JPATH_ROOT . '/plugins/system/helix3/core/classes/helix3.php';
$this->helix3 = helix3::getInstance();

if (file_exists($menu_class)) {
    require_once($menu_class);
}

$data = $displayData;

$left_right_modules = '';
$custom_class = (isset($data->settings->custom_class) && $data->settings->custom_class) ? ' ' . $data->settings->custom_class : '';

if (JFilterOutput::stringURLSafe($data->settings->name) == 'left' || JFilterOutput::stringURLSafe($data->settings->name) == 'right') {
	// When SPPB is inside Article with "left" modules
	$left_right_modules = ' sppb-in-article';
} 

$output ='';

    $output .= '<div id="sp-' . JFilterOutput::stringURLSafe($data->settings->name) . '" class="' . $data->className . $left_right_modules . '">';

        $output .= '<div class="sp-column' . $custom_class . '">';

        $features = (Helix3::hasFeature($data->settings->name))? helix3::getInstance()->loadFeature[$data->settings->name] : array();

            foreach ($features as $key => $feature){
                if (isset($feature['feature']) && $feature['load_pos'] == 'before' ) {
                    $output .= $feature['feature'];
                }
            }

            if (JFilterOutput::stringURLSafe($data->settings->name) == 'left' || JFilterOutput::stringURLSafe($data->settings->name) == 'right') {
			// Only "left" or "right" module positions
			$output .= '<div class="sp-lr">';
			} 
			
            $output .= '<jdoc:include type="modules" name="' . $data->settings->name . '" style="sp_xhtml" />';
			
			if (JFilterOutput::stringURLSafe($data->settings->name) == 'left' || JFilterOutput::stringURLSafe($data->settings->name) == 'right') {
			// Only "left" or "right" module positions
			$output .= '</div>';
			} 

            foreach ($features as $key => $feature){
                if (isset($feature['feature']) && $feature['load_pos'] != 'before' ) {
                    $output .= $feature['feature'];
                }
            }

        $output .= '</div>'; //.sp-column

    $output .= '</div>'; //.sp-


echo $output;
