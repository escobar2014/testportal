<?php
/**
 * @package 	apmod.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;
 
jimport('joomla.form.formfield');

	$doc = JFactory::getDocument();
	$moduleName = basename(dirname(__DIR__));
	$doc->addStylesheet(JURI::root(true).'/modules/'.$moduleName.'/admin/css/admin_style.css');
	$doc->addStylesheet('//netdna.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');
	JHTML::script('modules/'.$moduleName.'/admin/js/apoptions.js');
	
	// Clone form-inline-header to another position
	$jsclone = 'jQuery(document).ready(function(){'
        . 'var cloneContent = jQuery(".form-inline.form-inline-header").clone().addClass("visible");'
        . 'jQuery(".form-inline.form-inline-header,#myTabTabs li:has([href$=\"description\"])").remove();'
        . 'jQuery("#general").prepend(cloneContent);'
        . '});'; 
	$doc->addScriptDeclaration($jsclone);

// The class name must always be the same as the filename (in camel case)
class JFormFieldApmod extends JFormField {
        //The field class must know its own type through the variable $type.
        protected $type = 'Apmod';
		
        public function getInput() {
			
		$this->moduleName = basename(dirname(__DIR__));
		?>
		<script type="text/javascript">

			jQuery(document).ready(function(){
				// hides .apinstall (.apintro only showing on installation)
				jQuery("div.apinstall").remove();
				jQuery("p").filter(jQuery(".readmore")).parent("div").remove();
				
				jQuery("#general .form-inline.form-inline-header").hide().fadeIn(400);
				jQuery(".intro").hide().fadeIn(700);
				jQuery('#myTabTabs li').on('show', function () {
				  jQuery("#myTabContent").hide().fadeIn(250);
				});
				
				jQuery('#myTabTabs li a[href="#general"]').prepend("<i class='fa fa-codepen'></i>");
				jQuery('#myTabTabs li a[href$="assignment"]').prepend("<i class='fa fa-list-ul'></i>");
				jQuery('#myTabTabs li a[href$="permissions"]').prepend("<i class='fa fa-user'></i>");
				jQuery('#myTabTabs li a[href$="source"]').prepend("<i class='fa fa-folder-open'></i>");	
				jQuery('#myTabTabs li a[href$="slider_settings"]').prepend("<i class='icomoon-image'></i>");
				jQuery('#myTabTabs li a[href$="advanced"]').prepend("<i class='fa fa-code'></i>");	
				jQuery('#myTabTabs').append("<div class='copyright'><a href='http://www.aplikko.com' target='_blank'><img src='<?php echo JURI::root(true).'/modules/'.$this->moduleName.'/admin/images/logo_backend_gray.png'; ?>' /><br/>Developed by Aplikko</div>");
				jQuery('body').append('<a href=\"#top\" id=\"scroll-top\"><i class=\"fa fa-chevron-up\"></i></a>');
			});	
			
		    jQuery(document).ready(function(){
				var cloneContent = jQuery('#general .row-fluid .span9 h3, #general .row-fluid .span9 .info-labels').clone();
				jQuery('#general > .row-fluid > .span9 > h2, #general .row-fluid .span9 .info-labels, #general .row-fluid .span9 hr').remove();
				jQuery('#general .form-inline-header .control-group .control-label').removeClass('span3');
				jQuery('#general .form-inline-header .control-group .controls').removeClass('span9');
				jQuery("#general .form-inline.form-inline-header .control-group .controls").append(cloneContent);
				
				// Spacer
				jQuery("div.control-group:has([class='spacer']) .control-label").removeClass("aplabel").css({"width":"100%","padding":"0","background":"transparent"});
				// control-label + controls
				jQuery("div.control-group .control-label").addClass("aplabel");
				jQuery("div.control-group .controls").addClass("apcontrols");
				jQuery("label[for='jform_title']").parent().css({"background":"transparent"});

				jQuery('div.control-group:has([id="path_folder_images"]) .controls').css({"width":"100%","margin":"0 auto","padding":"0"});
				
			/*------------- Scroll to Top ------------------*/
			jQuery(window).scroll(function(){if(!jQuery('body').hasClass('whatever')){if(jQuery(this).scrollTop()>600){jQuery('a#scroll-top').addClass('open')}else{jQuery('a#scroll-top').removeClass('open')}}else{jQuery('a#scroll-top').removeClass('open')}});jQuery('a#scroll-top').on('click',function(){if(!jQuery('body').hasClass('whatever')){jQuery('html, body').animate({scrollTop:0},600);return false}})
			
			// activate popover (responsive)
				var options = {
					placement: function (context, source) {
						var position = jQuery(source).position();
						if (position.left < 400) {return "left";}
						if (position.left <= 650) {return "top";}
						if (position.left > 650) {return "right";}	
					},html: 'true'
				};
				jQuery(".add-on").popover(options);
				jQuery(".info-labels .label, label[for='jform_title']").tooltip();
				
				// hides <field type="apmod" />
				jQuery("div.control-group:has([class='hidden'])").remove();
			});	
		</script>
		<?php		
	}
	
	
}