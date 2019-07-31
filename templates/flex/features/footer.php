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

class Helix3FeatureFooter {

	private $helix3;

	public function __construct($helix3){
		$this->helix3 = $helix3;
		$this->position = $this->helix3->getParam('copyright_position');
		$this->load_pos = $this->helix3->getParam('copyright_load_pos');
	}

	public function renderFeature() {

		if($this->helix3->getParam('enabled_copyright')) {
			$output = '';
			//Copyright
			if( $this->helix3->getParam('copyright') ) {
				$output .= '<span class="sp-copyright">' . str_ireplace('{year}',date('Y'), $this->helix3->getParam('copyright')) . '</span>';
			}
			
			return $output;
		}
		
	}    
}