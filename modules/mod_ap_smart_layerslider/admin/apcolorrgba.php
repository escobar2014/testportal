<?php
/**
 * @package 	apcolorrgba.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;


jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldApcolorrgba extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'apcolorrgba';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	 
	protected function getInput() {
	
		$moduleName =  basename(dirname(__DIR__));	
		$doc= JFactory::getDocument();
	
		// add colorpicker for map color field
		$doc->addStyleSheet(JURI::root(true).'/modules/'.$moduleName.'/admin/colorpicker/css/bootstrap-colorpicker.css');
        JHTML::script('modules/'.$moduleName.'/admin/colorpicker/js/bootstrap-colorpicker.js');
		
		$scripts = '
		jQuery(function(){
			jQuery("#'.$this->id.'").colorpicker({format:"rgba"});
			jQuery("#'.$this->id.' .add-on i").click(function(c){
				c.preventDefault();
				jQuery("#'.$this->id.'").colorpicker("enable");
				jQuery(".helpcolor.'.$this->id.'").fadeOut(500);
			});	
			jQuery(".helpcolor.'.$this->id.'").popover({trigger: "hover"});
			jQuery("#'.$this->id.'-info").popover("destroy");
			
			jQuery("#'.$this->id.'").click(function(){
				jQuery("#'.$this->id.'-info").popover("destroy");
			});		
		});
		';
		JFactory::getDocument()->addScriptDeclaration($scripts);		
		
		$class = $this->element['class'];
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);
		$transparent = ($value == "") ? 'transparent' : $value;
		
        $background = 'style="background:'.$value.'"';
		$transparent_background = 'style="background:rgba(0,0,0,0);"';
		
		$fieldID = str_replace(array('jform[params]','[',']','_'), ' ', $this->name);
		$fieldID = ucfirst(strtolower(trim($fieldID)));

		if(($value) == "") {
			return '
			<div class="input-append color" data-color="rgba(0,0,0,0)" data-color-format="rgba" id="'.$this->id.'">
				<input type="text" name="'.$this->name.'" id="'.$this->id.'" class="'.$class.' input-medium" placeholder="'.$transparent.'" value="" data-color-format="rgba" disabled />
				<span class="add-on"><span class="transparent"></span><i class="disable" '.$transparent_background.'></i></span>	
			</div>
			<span class="helpcolor '.$this->id.'" data-toggle="popover" data-placement="right" data-content="<b>'.$fieldID.'</b><br>Use Colorpicker to select color with alpha transparency (RBGA format) for <b>'.ucfirst(strtolower(trim($fieldID))).'</b>"> <img style="margin:0 0 0 3px;" src="'.JURI::root(true).'/modules/'.$moduleName.'/admin/colorpicker/img/color-picker-16x16.png" /></span>
			';
		} else {
    		return '
			<div class="input-append color  '.$class.'" data-color="'.$value.'" data-color-format="rgba" id="'.$this->id.'">
			   <input type="text" name="'.$this->name.'" id="'.$this->id.'" class="'.$class.' input-medium" placeholder="transparent" value="'.$value.'" data-color-format="rgba" />
			   <span id="'.$this->id.'-info" class="add-on hasTooltip" title="<strong>'.$fieldID.'</strong><br>with alpha transparency (RGBA)"><span class="transparent"></span><i '.$background.'></i></span>
			</div>
			';
		}
	}

}
