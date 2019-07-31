<?php
/**
 * @author		Aplikko
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldApspacer extends JFormField {

	public $type = 'Apspacer';
	
	//Empty Label
    protected function getLabel(){return;}
	
	protected function getInput(){

		$html = array();
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		
		$divider = $this->element['divider'] == 'true' ? '<span class="divider"></span>' : '';
		$icon = ($this->element['icon'] != NULL) ? '<i class="'. JText::_($this->element['icon']) .'"></i>' : '';
		$style = ($this->element['style'] != NULL) ? ' style="'. JText::_($this->element['style']) .'"' : '';
		
		$hr = $this->element['hr'] == 'true' ? '<hr'.$style.' />' : '';
		
		$margin = $this->element['hr'] == 'true' ? '' : ' no-hr';
		$prepend = ($this->element['prepend'] != NULL) ? '<h3'.$style.' class="prepend'.$margin.'">'. $icon . JText::_($this->element['prepend']). $divider .'</h3>' : '';
		$append = ($this->element['append'] != NULL) ? '<h3'.$style.' class="append'.$margin.'">'. $icon . JText::_($this->element['append']). $divider .'</h3>' : '';

		// Start field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';
        $html[] = '<div class="row-fluid"><div class="clearfix span12">'. $prepend . $hr . $append .'</div></div>';
		$html[] = '</fieldset>';
		
		return implode($html);
	}
	
	public function renderField($options = array()) {
		$datashowon = ' data-showon=\'' . json_encode(JFormHelper::parseShowOnConditions($this->showon, $this->formControl, $this->group)) . '\'';
		return '<div'.$datashowon.'>'. $this->getInput() .'</div>';
 	}

}
