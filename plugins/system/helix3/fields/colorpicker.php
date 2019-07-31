<?php
/**
 * Custom Joomla! form field to generate Bootstrap Colorpicker input with optional opacity slider
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('color');

class JFormFieldColorpicker extends JFormFieldColor {

  protected $type = 'Colorpicker';

  /**
   * Method to get the field input markup.
   *
   * @return  string  The field input markup.
   *
   * @since   11.3
   */
  protected function getInput() {
	
    $class = ' ' . $this->class;
	
	$default_hint = $this->element['default'] != '' ? $default_hint = $this->element['default'] : $default_hint = 'transparent';
 
	$value = strtolower($this->value);

    $doc = JFactory::getDocument();
	$plg_path = JURI::root(true) . '/plugins/system/helix3';
	$doc->addScript($plg_path . '/assets/js/bootstrap-colorpicker.js');
	$doc->addStyleSheet($plg_path . '/assets/css/bootstrap-colorpicker.css');
	
	JFactory::getDocument()->addScriptDeclaration('
	  jQuery(function () {
		 jQuery("#' . $this->id . '").colorpicker({
			customClass: \'colorpicker-flex\',
			sliders: {
				saturation: {
					maxLeft: 137,
					maxTop: 137
				},
				hue: {
					maxTop: 137
				},
				alpha: {
					maxTop: 137
				}
			 }	
		  });  
	  });
	');

    return '<div id="' . $this->id . '" class="colorpicker-component"><span class="input-group-addon"><span class="transparent"></span><i></i><input type="text" name="' . $this->name . '"' . ' class="form-control'.$class.'" value="'.$value.'" placeholder="'.$default_hint.'" /></span></div>';
  }
}