<?php
/**
 * @package 	installscript.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2019 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

class mod_ap_smart_layersliderInstallerScript {
        /**
         * Method to install the extension
         * $parent is the class calling this method
         * @return void
         */
    function install($parent) {
      echo '
		 <div class="apinstall">
		 <h1><strong>AP Smart LayerSlider Module</strong><span class="pro">PRO</span><small>ver. 3.6</small></h1> 
		 <p><img class="img-rounded" style="float:left;margin:3px 15px 15px 0;" src="../modules/mod_ap_smart_layerslider/admin/images/ap_smart_layerslider.png" />AP Smart LayerSlider is a premium, fully responsive and touch-enabled Joomla module that allows you to create professional, multi-purpose sliders with smooth hardware accelerated transitions. This slider was built with user experience in mind, providing a clean and intuitive user interface in the admin area and a smooth navigation experience for the end-users.</p>
		 <p class="lic">From <a href="http://www.aplikko.com" target="_blank">Aplikko.com</a>.
		 <span class="check" style="float:right;margin-top:-3px;color:white;"><i class="icon-checkmark"></i><a class="hasTooltip" href="index.php?option=com_modules" title="Go to Modules">The installation was successful.</a></span></p>
		 </div>
		 <style type="text/css">
			.apinstall{display:block;margin:0 auto 20px;border:1px solid #ddd;vertical-align:top;padding:0 20px 7px;border-radius:5px;font-family:font-family:"Segoe UI","Myriad Pro",Arial,sans-serif;font-weight:normal;text-align:justify;color:#4d4d4d;line-height:24px;font-size:15px;background-color:#fff}
			.apinstall h1{font-family:Segoe,"Segoe UI","DejaVu Sans","Trebuchet MS",Verdana,Arial,sans-serif;font-size:25px;padding:15px 9px 15px 7px;border-bottom:1px solid #eee;margin:8px 10px 20px;vertical-align:top;line-height:180%;text-indent:7px;color:#595959;font-size:24px;font-weight:normal!important;border-radius:5px;background:transparent url(../modules/mod_ap_smart_layerslider/admin/images/logo_backend_gray.png) right 26px no-repeat;}
			.apinstall span.pro{font-size:8px;font-family:Arial,sans-serif;line-height:25px;padding:2px 4px 1px;color:#fff;vertical-align:middle;top:0px;margin-left:7px;position:relative;width:auto;border-radius:3px;background-color:#767A83;font-style:normal}
			.apinstall small{font-size:9px;font-family:Arial,sans-serif;line-height:25px;padding:1px 4px 1px;color:#fff;vertical-align:middle;top:0px;margin-left:7px;position:relative;width:auto;border-radius:3px;background-color:#679BB8;font-style:normal}
			.apinstall p{margin:10px 22px 10px;line-height:160%;font-size:114%;text-align:left}
			.apinstall a {color:#679BB8}
			.apinstall p.lic{width;100%;clear:both;border-top:1px solid #eee;margin:30px 24px 20px;padding:26px 3px 10px}
			.apinstall p.lic .check a {color:#777;text-decoration: none;}
			.apinstall p.lic .check a:hover {color:#4784A5}
			.apinstall p.lic .icon-checkmark {background:#85BF2D;border-radius:50%;padding:6px;margin-right:7px;color:#fff;text-shadow:1px 1px 1px rgba(0,0,0,.3);}
		 </style>
		';                
        }
 
        /**
         * Method to uninstall the extension
         * $parent is the class calling this method
         * @return void
         */
        function uninstall($parent) {  
                echo ' 
				<div class="apuninstall alert alert-block fade in">
				 <button type="button" class="close" data-dismiss="alert">&times;</button>
				 <p><strong>AP Smart LayerSlider Module</strong><hr/></p>
				  <p class="lic"><span class="check" style="margin-top:-3px;"><i class="icon-checkmark"></i>The module has been <strong>uninstalled</strong>.</span></p>
				 </div>
				 <style type="text/css">
					.apuninstall{width:520px;margin:0 0 20px 5px;border:1px solid #ddd;vertical-align:top;padding:15px 20px;border-radius:5px;font-family:font-family:"Segoe UI","Myriad Pro",Arial,sans-serif;font-weight:normal;text-align:justify;color:#4d4d4d;line-height:24px;font-size:15px;background-color:#fff}
					.apuninstall .close {margin:3px 15px 0 0;}
					.apuninstall p{margin:0 auto;line-height:160%;font-size:114%;text-align:left}
					.apuninstall hr {border:none;border-bottom:1px solid #eee;width:auto;margin:12px auto 5px;display:block;}
					.apuninstall p.lic{display:block;margin:0 auto;padding:15px 3px 5px 1px}
					.apuninstall p.lic .icon-checkmark {background:#85BF2D;border-radius:50%;padding:6px;margin-right:7px;color:#fff;text-shadow:1px 1px 1px rgba(0,0,0,.3);}
					@media (max-width: 767px){.apuninstall{width:100%!important;}}
				 </style>
				';                
        }
 
        /**
         * Method to update the extension
         * $parent is the class calling this method
         * @return void
         */
        function update($parent) {
			echo '
			 <div class="apinstall">
			 <h1><strong>AP Smart LayerSlider Module</strong><span class="pro">PRO</span><small>ver. 3.6</small></h1> 
		 <p><img class="img-rounded" style="float:left;margin:3px 15px 15px 0;" src="../modules/mod_ap_smart_layerslider/admin/images/ap_smart_layerslider.png" />AP Smart LayerSlider is a premium, fully responsive and touch-enabled Joomla module that allows you to create professional, multi-purpose sliders with smooth hardware accelerated transitions. This slider was built with user experience in mind, providing a clean and intuitive user interface in the admin area and a smooth navigation experience for the end-users.</p>
			 <p class="lic">From <a href="http://www.aplikko.com" target="_blank">Aplikko.com</a>.
			 <span class="check" style="float:right;margin-top:-3px;color:white;"><i class="icon-checkmark"></i><a class="hasTooltip" href="index.php?option=com_modules" title="Go to Modules">The Update was successful.</a></span></p>
			 </div>
			<style type="text/css">
			.apinstall{display:block;margin:0 auto 20px;border:1px solid #ddd;vertical-align:top;padding:0 20px 7px;border-radius:5px;font-family:font-family:"Segoe UI","Myriad Pro",Arial,sans-serif;font-weight:normal;text-align:justify;color:#4d4d4d;line-height:24px;font-size:15px;background-color:#fff}
			.apinstall h1{font-family:Segoe,"Segoe UI","DejaVu Sans","Trebuchet MS",Verdana,Arial,sans-serif;font-size:25px;padding:15px 9px 15px 7px;border-bottom:1px solid #eee;margin:8px 10px 20px;vertical-align:top;line-height:180%;text-indent:7px;color:#595959;font-size:24px;font-weight:normal!important;border-radius:5px;background:transparent url(../modules/mod_ap_smart_layerslider/admin/images/logo_backend_gray.png) right 26px no-repeat;}
			.apinstall span.pro{font-size:8px;font-family:Arial,sans-serif;line-height:25px;padding:2px 4px 1px;color:#fff;vertical-align:middle;top:0px;margin-left:7px;position:relative;width:auto;border-radius:3px;background-color:#767A83;font-style:normal}
			.apinstall small{font-size:9px;font-family:Arial,sans-serif;line-height:25px;padding:1px 4px 1px;color:#fff;vertical-align:middle;top:0px;margin-left:7px;position:relative;width:auto;border-radius:3px;background-color:#679BB8;font-style:normal}
			.apinstall p{margin:10px 22px 10px;line-height:160%;font-size:114%;text-align:left}
			.apinstall a {color:#679BB8}
			.apinstall p.lic{width;100%;clear:both;border-top:1px solid #eee;margin:30px 24px 20px;padding:26px 3px 10px}
			.apinstall p.lic .check a {color:#777;text-decoration: none;}
			.apinstall p.lic .check a:hover {color:#4784A5}
			.apinstall p.lic .icon-checkmark {background:#85BF2D;border-radius:50%;padding:6px;margin-right:7px;color:#fff;text-shadow:1px 1px 1px rgba(0,0,0,.3);}
		 </style>
			';    
        }
 
        /**
         * Method to run before an install/update/uninstall method
         * $parent is the class calling this method
         * $type is the type of change (install, update or discover_install)
         * @return void
         */
        function preflight($type, $parent) {
            //echo '<p>Anything here happens before the installation/update/uninstallation of the module</p>';
        }
        /**
         * Method to run after an install/update/uninstall method
         * $parent is the class calling this method
         * $type is the type of change (install, update or discover_install)
         * @return void
         */
        function postflight($type, $parent) {
            //echo '<p>Anything here happens after the installation/update/uninstallation of the module</p>';
        }
}