<?php
/**
 * @package 	aptext.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldAptext extends JFormField {
	protected $type = 'Aptext';

        protected function getInput() {

            $output = NULL;
            // Initialize some field attributes.
            $size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
            $maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
            $class      = $this->element['class'];
            $readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
            $disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
            
            $prepend    = ($this->element['prepend'] != NULL) ? '<span class="add-on">'. JText::_($this->element['prepend']). '</span>' : '';

            $append   = ($this->element['append'] != NULL) ? '<span class="add-on" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="'.JText::_($this->element['data-content']).'" title="'.JText::_($this->element['title']).'">'.JText::_($this->element['append']).'</span>' : '';

            if($prepend) $extra_class = 'input-prepend';
            elseif($append) $extra_class = ' input-append';
            else $extra_class = '';

            $wrapstart  = '<div class="field-wrap clearfix '.$class. $extra_class .'">';
            $wrapend    = '</div>';

            $input = '<input type="text" name="'.$this->name.'" id="'.$this->id.'"'
			. ' value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'"'
			.$size.$disabled.$readonly.$maxLength.'/>';

            $output = $wrapstart . $prepend . $input . $append . $wrapend;
            return $output;
	
	}

}
