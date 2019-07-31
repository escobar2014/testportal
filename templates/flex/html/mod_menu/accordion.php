<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//random ID number to avoid conflict if there is more modules on the same page
$randomid = rand(1,1000);
?>
<ul class="accordion-menu <?php echo $class_sfx;?>"<?php
	$tag = '';

	if ($params->get('tag_id') != null)
	{
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
?>>
<?php
foreach ($list as $i => &$item) {
	$class = 'item-' . $item->id;
	$active_collapse = '';

	if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
		$active_collapse .= ' in';
	}
	elseif ($item->type == 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type == 'separator') { 
		$class .= ' divider-separator'; 
	}
	
	if ($item->type == 'heading') { 
		$class .= ' separator';
	}

	if ($item->deeper) {
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}

	if (!empty($class))
	{
		$class = ' class="' . trim($class) . '"';
	}

	echo '<li' . $class . '>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'accordion_component');
			break;

		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'accordion_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper)
	{
		//if($module->position == 'offcanvas') {
			//echo '<ul class="collapse" id="collapse-menu-'. $item->id .'">';
		//} else {
			echo '<ul class="collapse' . $active_collapse . '" id="collapse-menu-'. $item->id .'-'.$randomid.'">';
		//}
		
	}
	elseif ($item->shallower)
	{
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	else
	{
		// The next item is on the same level.
		echo '</li>';
	}
}
?></ul>
