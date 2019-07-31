<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

// Note that this layout opens a div with the page class suffix. If you do not use the category children
// layout you need to close this div either by overriding this file or in your main layout.
$params    = $displayData->params;
$extension = $displayData->get('category')->extension;
$canEdit   = $params->get('access-edit');
$className = substr($extension, 4);

// Include template's params
$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
$has_lazyload = $tpl_params->get('lazyload', 1);

// This will work for the core components but not necessarily for other components
// that may have different pluralisation rules.
if (substr($className, -1) == 's')
{
	$className = rtrim($className, 's');
}
$tagsData = $displayData->get('category')->tags->itemTags;
?>
<div>
	<div class="<?php echo $className .'-category' . $displayData->pageclass_sfx;?>">
		<?php if ($params->get('show_page_heading')) : ?>
			<h1>
				<?php echo $displayData->escape($params->get('page_heading')); ?>
			</h1>
		<?php endif; ?>
		<?php if($params->get('show_category_title', 1)) : ?>
			<h2>
				<?php echo JHtml::_('content.prepare', $displayData->get('category')->title, '', $extension . '.category.title'); ?>
			</h2>
		<?php endif; ?>
		<?php if ($params->get('show_cat_tags', 1)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.tags', $tagsData); ?>
		<?php endif; ?>
		<?php if ($params->get('show_description', 1) || $params->def('show_description_image', 1)) : ?>
			<div class="category-desc">
				<?php if ($params->get('show_description_image') && $displayData->get('category')->getParams()->get('image')) : ?>
                <?php 
				$cat_image = $displayData->get('category')->getParams()->get('image');
					if(strpos($cat_image, 'http://') !== false || strpos($cat_image, 'https://') !== false){
						if($has_lazyload) { ?>
                        	<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $cat_image; ?>" alt="<?php echo htmlspecialchars($displayData->get('category')->getParams()->get('image_alt')); ?>" data-expand="-10">
                    	<?php } else { ?>
                        	<img src="<?php echo $cat_image; ?>" alt="<?php echo htmlspecialchars($displayData->get('category')->getParams()->get('image_alt')); ?>">
                        <?php } 
						} else { 
                        if($has_lazyload) { ?>
                        	<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo JUri::root() . $cat_image; ?>" alt="<?php echo htmlspecialchars($displayData->get('category')->getParams()->get('image_alt')); ?>" data-expand="-10">
                    	<?php } else { ?>
                        	<img src="<?php echo $cat_image; ?>" alt="<?php echo htmlspecialchars($displayData->get('category')->getParams()->get('image_alt')); ?>">
                        <?php }
					} ?>
				<?php endif; ?>
				<?php if ($params->get('show_description') && $displayData->get('category')->description) : ?>
					<?php echo JHtml::_('content.prepare', $displayData->get('category')->description, '', $extension . '.category'); ?>
				<?php endif; ?>
				<div class="clr"></div>
			</div>
		<?php endif; ?>
		<?php echo $displayData->loadTemplate($displayData->subtemplatename); ?>

		<?php if ($displayData->get('children') && $displayData->maxLevel != 0) : ?>
			<div class="cat-children">
				<h3>
					<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
				</h3>

				<?php echo $displayData->loadTemplate('children'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

