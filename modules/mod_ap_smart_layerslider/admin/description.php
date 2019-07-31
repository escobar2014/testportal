<?php
/**
 * @package 	description.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldDescription extends JFormField {
	protected $type = 'Description';

	/**
	* Method to get a form field markup for the field input.
	*/
	protected function getInput() {
	
	//$doc = JFactory::getDocument();
	$srcpath = JURI::root(true).'/modules/'.basename(dirname(__DIR__));
	$thumbName = str_replace('mod_', '', basename(dirname(__DIR__)));
	$moduleName = str_replace('_',' ',str_replace('mod_', '', basename(dirname(__DIR__))));
	$moduleName = ucwords($moduleName);
    $moduleName[1] = strtoupper($moduleName[1]);

	return '
    <div class="intro">
		<h2>AP Smart LayerSlider<span class="pro hasTooltip" title="Premium version">PRO</span><span class="version hasTooltip" title="Extension version">ver. 3.6</span><span style="background:#D85D50;margin-left:7px;" class="pro hasTooltip" title="Joomla module">M</span></h2>
		<p>AP Smart LayerSlider is a premium, fully responsive and touch-enabled Joomla module that allows you to create professional, multi-purpose sliders with smooth hardware accelerated transitions. This slider was built with user experience in mind, providing a clean and intuitive user interface in the admin area and a smooth navigation experience for the end-users.
</p>
		<h4 class="features">Main Features:</h4>
		<ul>
			<li><i class="fa fa-check-square-o"></i><strong>CSS3 Transitions</strong>. Fast, accelerated CSS3 animations. All animations in the slider are powered by CSS3 transitions, ensuring the smoothest animations that are possible at the moment.</li>
			<li><i class="fa fa-check-square-o"></i><strong>Animated Layers</strong>. Layers can be both animated and static and they can hold any HTML content. Also, layers can be scaled down automatically or with CSS.</li>
			<li><i class="fa fa-check-square-o"></i><b>Touch-swipe</b>. The slider\'s touch-swipe capabilities provides a native-like navigation experience on touch-screen devices. Swipe gestures are enabled for desktop devices as well. </li>
			</li>
			<li><i class="fa fa-check-square-o"></i><b>Fully Responsive</b>. AP Smart LayerSlider is responsive by default. Not only the images will scale down, but the animated layers (where you can add any content) will be scaled down automatically as well.</li>
			<li><i class="fa fa-check-square-o"></i>Chosen <b>Image Folder</b>. Use images from specific folder in admin, re-order and customize easily (custom title, desription, link).</li>
			<li><i class="fa fa-check-square-o"></i><b>CSS-only controls</b> All the navigation controls (i.e., arrows, bullets) are CSS-only (no graphics).</li>
			<li><i class="fa fa-check-square-o"></i><b>Smart Video Support</b>. Videos inside the slider will be controlled automatically. For example, when a video starts playing, the autoplay stops, or, when another slide is selected, the video stops.</li>         
			<li class="page-scroll"><i class="fa fa-check-square-o"></i><b>Thumbnails</b>. Thumbnails can contain text, images or both. Also, they can be positioned at top, bottom, left or right of the slides.
			</li>
			<li><i class="fa fa-check-square-o"></i><b>Lazy Loading</b>. Enables the loading of images only when they are in a visible area, thus saving bandwidth and speeding up the initial page load.</li>
			<li><i class="fa fa-check-square-o"></i><b>Auto Height</b>. The height of the slider can be set to adjust automatically to the full height of the currently selected slide.</li>
			<li><i class="fa fa-check-square-o"></i><b>Keyboard Navigation</b>. Slides can be navigated by using the keyboard arrow keys. Also, if a slide contains a link it can be activated by using the Enter key.</li>
			<li><i class="fa fa-check-square-o"></i><b>Rendering images</b> via php resize (custom width/height.)</li>
			<li><i class="fa fa-check-square-o"></i><b>Full-screen Support</b>. The slider can be viewed in full-screen mode in all browsers that support the HTML5 Full Screen.</li>
			<li><i class="fa fa-check-square-o"></i><b>5 Custom Styles</b> included.</li>
			<li><i class="fa fa-check-square-o"></i><b>Dynamic Images</b>. Loads images from specific folder.</li>
			<li><i class="fa fa-check-square-o"></i><b>HTML5 Uploader</b>. You can upload multiple images into specific folder, using HTML5 uploader.</li>
			<li><i class="fa fa-check-square-o"></i><b>Dynamic Content</b>. Easily load content from your Joomla or K2 articles (i.e., article\'s image, title, descriptions, etc.). You can even combine multiple content types in the same slider.</li>
		</ul>
		<div class="license">
			<img class="img-rounded" style="width:110px;height:auto;float:left;margin:0 20px 0 20px;" src="'.$srcpath.'/admin/images/'.$thumbName.'.png" alt="" />
			<span class="title">AP Smart LayerSlider Module<span class="pro">PRO</span><small style="color:#fff;">ver. 3.6</small><br /><br /></span>
			<div class="getmore">Get more extensions from Aplikko <a class="hasTooltip" title="Aplikko Extensions Page" href="http://www.aplikko.com/joomla-extensions" target="_blank">extensions</a> page.<br />Powerfully simple! From <a href="http://www.aplikko.com" target="_blank">Aplikko.com</a>.</div>
		</div>  
    </div>
	';	
	}
	
	
	/**
	 * Method to get a control group with label and input.
	 * @since   3.2
	 */
	public function renderField($options = array()) {
	  return $this->getInput();
 	}
	
}