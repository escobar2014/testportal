<?php
/**
 * @package     Helix
 *
 * @copyright   Copyright (C) 2010 - 2018 JoomShaper. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */
defined('_JEXEC') or die('Restricted Access');

$this->helix3 = helix3::getInstance();

$data = $displayData;

$output ='';


$output .= '<div id="sp-component" class="' . $data->className . '">';

$output .= '<div class="sp-column ' . ($data->settings->custom_class) . '">';
$output .= '<jdoc:include type="message" />';

if ($this->helix3->countModules('above-component')) {
$output .= '<div class="above-component"><jdoc:include type="modules" name="above-component" /></div>';
}
$output .= '<jdoc:include type="component" />';
if ($this->helix3->countModules('below-component')) {
$output .= '<div class="below-component"><jdoc:include type="modules" name="below-component" /></div>';
}
$output .= '</div>';

$output .= '</div>';


echo $output;

