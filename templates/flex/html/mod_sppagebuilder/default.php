<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted access');

require_once JPATH_ROOT .'/components/com_sppagebuilder/parser/addon-parser.php';
JHtml::_('jquery.framework'); // make sure jQuery is loaded before scripts. Important fix.
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base(true).'/components/com_sppagebuilder/assets/css/animate.min.css');
$doc->addStyleSheet(JUri::base(true).'/components/com_sppagebuilder/assets/css/sppagebuilder.css');
$doc->addScript(JUri::base(true).'/components/com_sppagebuilder/assets/js/jquery.parallax.js');
$doc->addScript(JUri::base(true).'/components/com_sppagebuilder/assets/js/sppagebuilder.js');
?>
<div class="mod-sppagebuilder <?php echo $moduleclass_sfx ?> sp-page-builder" data-module_id="<?php echo $module->id; ?>">
	<div class="page-content">
		<?php echo AddonParser::viewAddons(json_decode($data), true, 'module' );?>
	</div>
</div>