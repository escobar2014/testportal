<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get item params and decode it
$item_decode = json_decode($item->params);

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = $item->title;
}

$icon = '';
//Add Menu Pixeden Icon (for Flex)
if (isset($item_decode->peicon) && $item_decode->peicon) {
	$icon = ' <i class="pe ' . $item_decode->peicon . '"></i>';
} else {
	if (isset($item_decode->icon) && $item_decode->icon) {
		$icon = ' <i class="fa ' . $item_decode->icon . '"></i>';
	}
	
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
	default:
	case 0:
		$link_rel = ($item->anchor_rel) ? 'rel="' . $item->anchor_rel . '"' : '' ;
		?>
		<a <?php echo $class; ?> href="<?php echo $flink; ?>" <?php echo $title; ?> <?php echo $link_rel ?>><?php echo $icon . ' ' . $linktype; ?></a><?php
	break;
	case 1:
		// _blank
		$link_rel = ($item->anchor_rel == 'nofollow') ? 'noopener noreferrer nofollow' : 'noopener noreferrer';
		?>
		<a <?php echo $class; ?>href="<?php echo $flink; ?>" rel="<?php echo $link_rel ?>" target="_blank" <?php echo $title; ?>><?php echo $icon . ' ' . $linktype; ?></a><?php
	break;
	case 2:
		// Use JavaScript "window.open"
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
			?><a <?php echo $class; ?>href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;" <?php echo $title; ?>><?php echo $icon . ' ' . $linktype; ?></a><?php
	break;
endswitch;
