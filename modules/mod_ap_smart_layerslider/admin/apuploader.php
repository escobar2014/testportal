<?php
/**
 * @package 	apuploader.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldApuploader extends JFormField {
    protected $type = 'Apuploader';
    protected function getInput() {
		$params = $this->form->getValue('params');
		//remove request param label
		$doc = JFactory::getDocument();
		
		$doc->addScriptDeclaration("
		jQuery(window).load(function(){
			jQuery('#jform_params_apuploader-lbl').parent().remove();
		});");

		$command = JRequest::getString('command', '');
		$apuploader = strtolower(JRequest::getString('apuploader'));
		$path = JRequest::getString('path', '');
		//process
        if ($apuploader && $command) {
			
			//load file to excute command
			require_once(dirname((dirname(__FILE__))).'/admin/apuploader/'.$apuploader.'.php');
            $obLevel = ob_get_level();
			if($obLevel){
				while ($obLevel > 0 ) {
					ob_end_clean();
					$obLevel --;
				}
			}else{
				ob_clean();
			}
            $obj = new $apuploader();
			
			$data = $obj->$command($params);
			echo json_encode($data);
			
            exit;
        }
    }    
    
}