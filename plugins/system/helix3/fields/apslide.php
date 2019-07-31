<?php 

/**
 * @package 	apslide.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldApslide extends JFormField {
	protected $type = 'Apslide';

        protected function getInput() {
			
		$doc = JFactory::getDocument();
		$plg_path = JURI::root(true) . '/plugins/system/helix3';
		$doc->addScript($plg_path . '/assets/js/simple-slider.min.js');
		$doc->addStyleSheet($plg_path . '/assets/css/simple-slider.css');
		
		$class = $this->element['class'];
		$value = intval(htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'));
		$fieldID = str_replace(array('jform[params]','[',']'), '', $this->name);
		
		$scripts = '	
		jQuery(document).ready(function() {
			
			 // Slide options
			 jQuery("#'.$fieldID.'").each(function(){ 
				 jQuery("#'.$fieldID.'").bind("slider:ready slider:changed", function (event, data) { 
					 jQuery(".output_'.$fieldID.'").html(data.value.toFixed(0));		 
				 });
			  });
		
		});
		';
		JFactory::getDocument()->addScriptDeclaration($scripts);	
			
			$data_slider_range  = ((string) $this->element['data-slider-range'] != NULL) ? ' data-slider-range="'.$this->element['data-slider-range'].'" data-slider-highlight="true"' : '';

			$data_slider_range_steps  = ((string) $this->element['data-slider-range'] != NULL) ? ' data-slider-step="'.$this->element['data-slider-step'].'"' : '';
			
			$append = JText::_($this->element['append']);

            $input = '
			<div class="slide_wrap '.$class.'">
			<span class="slider input-group-addon"><input type="text" name="'.$this->name.'" id="'.$fieldID.'"'
			. ' data-slider="true" value="'.$value.'"'.$data_slider_range.$data_slider_range_steps.
			' /></span>
			<div class="info"><span class="output_'.$fieldID.'">'.$value.'</span> '.$append.'</div>
			</div>
			';
            return $input;
	
	}

}
