<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$lang = JFactory::getLanguage(); ?>

<nav role="pagination">
    <ul class="cd-pagination no-space animated-buttons custom-icons">
		<?php if ($row->prev) :
			$direction = $lang->isRTL() ? 'right' : 'left'; ?>
            <li class="button btn-previous">
                <a href="<?php echo $row->prev; ?>" rel="prev"><i><?php echo JText::_('JPREV'); ?></i></a>
            </li>
        <?php endif; ?>
        
       <?php if ($row->next) :
			$direction = $lang->isRTL() ? 'left' : 'right'; ?>
            <li class="button btn-next">
                <a href="<?php echo $row->next; ?>" rel="next"><i><?php echo JText::_('JNEXT'); ?></i></a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
