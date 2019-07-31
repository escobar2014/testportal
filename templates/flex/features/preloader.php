<?php
/**
 * Flex @package Helix3 Framework
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('restricted access');

class Helix3FeaturePreloader {

	private $helix3;

	public function __construct($helix){
		$this->helix3 = $helix;
		$this->position = 'helixpreloader';
	}

	public function renderFeature() {

		$app = JFactory::getApplication();
		//Load Helix
		$helix3_path = JPATH_PLUGINS . '/system/helix3/core/helix3.php';
		if (file_exists($helix3_path)) {
			require_once($helix3_path);
			$getHelix3 = helix3::getInstance();
		} else {
			die('Please install and activate helix plugin');
		}

		$output = '';
		if ($getHelix3->getParam('preloader')) { 
           	//Pre-loader -->
            $output .= '<div class="sp-pre-loader">';
                if ($getHelix3->getParam('preloader_animation') == 'double-loop') {
                    // Bubble Loop loader 
                    $output .= '<div class="sp-loader-bubble-loop"></div>';
                } elseif ($getHelix3->getParam('preloader_animation') == 'wave-two') {
                    // Audio Wave 2 loader
                    $output .= '<div class="wave-two-wrap">';
                        $output .= '<ul class="wave-two">';
                            $output .= '<li></li>';
                            $output .= '<li></li>';
                            $output .= '<li></li>';
                            $output .= '<li></li>';
                            $output .= '<li></li>';
                            $output .= '<li></li>';
                        $output .= '</ul>'; //<!-- /.Audio Wave 2 loader -->
                    $output .= '</div>'; // <!-- /.wave-two-wrap -->

                } elseif ($getHelix3->getParam('preloader_animation') == 'audio-wave') {
                    // Audio Wave loader
                    $output .= '<div class="sp-loader-audio-wave"> </div>';
                } elseif ($getHelix3->getParam('preloader_animation') == 'circle-two') {
                    // Circle two Loader
                    $output .= '<div class="circle-two">'; 
                        $output .= '<span></span>'; 
                    $output .= '</div>'; // /.Circle two loader
                } elseif ($getHelix3->getParam('preloader_animation') == 'flip') {
                    // Flip Loader
                    $output .= '<div class="loader-flip">'; 
                        //$output .= '<i class="fa fa-balance-scale"></i>'; 
                    $output .= '</div>';
                } elseif ($getHelix3->getParam('preloader_animation') == 'clock') {
                    //Clock loader
                    $output .= '<div class="sp-loader-clock"></div>';
                } elseif ($getHelix3->getParam('preloader_animation') == 'logo') {

                    if ($getHelix3->getParam('logo_image')) {
                        $logo = JUri::root() . '/' . $getHelix3->getParam('logo_image');
                    } else {
                        $logo = JUri::root() . '/templates/' . $app->getTemplate() . '/images/presets/' . $getHelix3->Preset() . '/logo.png';
                    }

                    // Line loader with logo
                    $output .= '<div class="sp-loader-with-logo">';
                        $output .= '<div class="logo animated fadeIn">';
                            $output .= '<img src="' . $logo . '" alt="logo">';
                        $output .= '</div>';
                        $output .= '<div class="line" id="line-load"></div>';
                    $output .= '</div>'; // /.Line loader with logo

                } else {
                    // Circle loader
                    $output .= '<div class="sp-loader-circle"></div>'; // /.Circular loader
                }
            $output .= '</div>'; // /.Pre-loader
            
        } // if enable preloader

        echo $output;
	} //renderFeature
}