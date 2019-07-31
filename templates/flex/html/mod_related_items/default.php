<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="relateditems <?php echo $moduleclass_sfx; ?>">
<?php foreach ($list as $item) :	?>
<li>
	<a href="<?php echo $item->route; ?>">
		<?php // <i class="fa fa-file-text-o"></i> ?>
		<?php echo $item->title; ?>
        <?php if ($showDate) echo '<div class="related-date"><i class="fa fa-clock-o"></i>'.JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC3')).'</div>'; ?></a>
</li>
<?php endforeach; ?>
</ul>
