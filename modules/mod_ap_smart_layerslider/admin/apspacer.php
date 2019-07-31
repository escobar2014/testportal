<?php
/**
 * @package 	apspacer.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldAPSpacer extends JFormField {

	public $type = 'Apspacer';
	
	//Empty Label
    protected function getLabel(){return;}

	protected function getInput() {

		$html   = array();
        $class  = (string) $this->element['class'];
        $label  = '';
		$descirption = $this->element['description'];
		
	    $text = (!empty($this->element['label'])) ? (string) $this->element['label'] : '';
		$name = str_replace(array('jform[params]', '[', ']'),'',$this->name);
		$getidname = str_replace(array('jform[params]', ' ', '[', ']'),'',str_replace(' ' , '_', strtolower($this->name)));

		if($text != ''){
            $label .= '<div class="row-fluid"><div id="'.$getidname.'" class="'.(($text != '') ? 'apspacer_divider' : 'spacer').' span12"><span>'. JText::_($text).'</span>'.(($text != '') ? '<i class="fa fa-chevron-down"></i>' : '').'</div></div>';
        }
		
		if($class == 'hideshowspacer'){
            return '
			<div class="control-group '.$class.'">
			<div id="jform_params_'.$name.'-lbl" for="jform_params_'.$name.'""><div id="'.$getidname.'" class="'.(($text != '') ? 'apspacer_divider' : 'spacer').' span12"><span>'. JText::_($text).'</span>'.(($text != '') ? '<i class="fa fa-chevron-down"></i>' : '').'</div></div>
			'.(($descirption != '') ? '<div class="description">'. JText::_($descirption).'</div>' : '').'	
			</div>
			';
        }
		
        $html[] = $label;
        return implode('', $html);
	}

	public function renderField($options = array()) {
		return $this->getInput(); 
 	}
}
