<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include template's params
$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
$has_lazyload = $tpl_params->get('lazyload', 1);

// Note that there are certain parts of this layout used only when there is exactly one tag.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$isSingleTag = (count($this->item) == 1);
?>
<div class="tag-category<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php endif; ?>
	<?php if ($this->params->get('show_tag_title', 1)) : ?>
		<h2>
			<?php echo JHtml::_('content.prepare', $this->tags_title, '', 'com_tag.tag'); ?>
		</h2>
	<?php endif; ?>
	<?php // We only show a tag description if there is a single tag. ?>
	<?php if (count($this->item) == 1 && (($this->params->get('tag_list_show_tag_image', 1)) || $this->params->get('tag_list_show_tag_description', 1))) : ?>
		<div class="category-desc">
			<?php $images = json_decode($this->item[0]->images); ?>
			<?php if ($this->params->get('tag_list_show_tag_image', 1) == 1 && !empty($images->image_fulltext)) : ?>
            	<?php 
				if(strpos($images->image_fulltext, 'http://') !== false || strpos($images->image_fulltext, 'https://') !== false){
					if($has_lazyload) { ?>
						<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $images->image_fulltext; ?>" alt="<?php echo JHtml::_('content.prepare', $this->tags_title, '', 'com_tag.tag'); ?>" data-expand="-10">
					<?php } else { ?>
						<img src="<?php echo htmlspecialchars($images->image_fulltext); ?>">
					<?php } 
					} else { 
					if($has_lazyload) { ?>
						<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo JUri::root() . $images->image_fulltext; ?>" alt="<?php echo JHtml::_('content.prepare', $this->tags_title, '', 'com_tag.tag'); ?>" data-expand="-10">
					<?php } else { ?>
						<img src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo JHtml::_('content.prepare', $this->tags_title, '', 'com_tag.tag'); ?>">
					<?php }
				} ?>
			<?php endif; ?>
			<?php if ($this->params->get('tag_list_show_tag_description') == 1 && $this->item[0]->description) : ?>
				<?php echo JHtml::_('content.prepare', $this->item[0]->description, '', 'com_tags.tag'); ?>
			<?php endif; ?>
			<div class="clr"></div>
		</div>
	<?php endif; ?>
	<?php // If there are multiple tags and a description or image has been supplied use that. ?>
	<?php if ($this->params->get('tag_list_show_tag_description', 1) || $this->params->get('show_description_image', 1)): ?>
		<?php if ($this->params->get('show_description_image', 1) == 1 && $this->params->get('tag_list_image')) : ?>
			<img src="<?php echo $this->params->get('tag_list_image'); ?>">
		<?php endif; ?>
		<?php if ($this->params->get('tag_list_description', '') > '') : ?>
			<?php echo JHtml::_('content.prepare', $this->params->get('tag_list_description'), '', 'com_tags.tag'); ?>
		<?php endif; ?>

	<?php endif; ?>

	<?php echo $this->loadTemplate('items'); ?>
	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>
