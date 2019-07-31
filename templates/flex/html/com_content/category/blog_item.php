<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $this->item->params;
$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$canEdit = $this->item->params->get('access-edit');
$info = $params->get('info_block_position', 0);

// Post Format
$post_attribs = new JRegistry(json_decode( $this->item->attribs ));
$post_format = $post_attribs->get('post_format', 'standard');
$has_post_format = $tpl_params->get('show_post_format');

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (JLanguageAssociations::isEnabled() && $params->get('show_associations'));
// Group the params
$useDefList = ($params->get('show_title') || $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam);
?>

<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<?php $show_icons = JLayoutHelper::render('joomla.content.post_formats.icons',  $post_format); ?>

<?php if ($tpl_params->get('blog_layout') == 'masonry') { ?>
<div class="<?php echo $tpl_params->get('show_post_format') ? ' has-post-format-masonry': ''; ?>">
	<?php if ($has_post_format) { ?>
    <span class="post-format-masonry"><?php echo $show_icons; ?></span>
    <?php } ?> 
</div>

<?php } ?>

<?php
	if($post_format=='standard') {
		echo JLayoutHelper::render('joomla.content.intro_image', $this->item);
	} else {
		echo JLayoutHelper::render('joomla.content.post_formats.post_' . $post_format, array('params' => $post_attribs, 'item' => $this->item));
	}
?>
<?php if ($tpl_params->get('blog_layout') == 'masonry') { ?>
<!-- START Post-intro --><div class="post_intro">
<div class="entry-header">
	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>	
        <?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
        <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
    <?php endif; ?>
</div>
<?php } else { ?>
<div class="entry-header<?php echo $tpl_params->get('show_post_format') ? ' has-post-format': ''; ?>">
	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>	
		<?php if ($has_post_format) { ?>
        <span class="post-format"><?php echo $show_icons; ?></span>
        <?php } ?> 
        <?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
        <?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>
</div>
<?php } ?>

<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<div class="edit-article pull-right"><?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?></div>
<?php endif; ?>

<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
<div class="entry-header<?php echo $tpl_params->get('show_post_format') ? ' has-post-format': ''; ?>">
	<?php if($has_post_format) { ?>
    <span class="post-format"><?php echo $show_icons; ?></span>
    <?php } ?> 
    <?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
	<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
</div>
<?php endif; ?>
<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>	
<?php // Removed $rating from Helix3 2.5.1 // $rating = (int) $this->item->rating; ?>
<?php echo $this->item->introtext; ?>
<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>
    <div class="clearfix"></div>	
 
	<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>
	<?php echo JLayoutHelper::render('joomla.content.social_share.entrylist_share', $this->item); //Social Share ?>
    
<?php endif; ?>

	<div class="clearfix"></div>	
	<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>	
        <?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
    <?php endif; ?>
    <?php if ($params->get('show_readmore') && $this->item->readmore) {  ?>
    <?php } else {   ?>
	   <?php echo JLayoutHelper::render('joomla.content.social_share.entrylist_share', $this->item); //Social Share ?>
	<?php } ?>
  	<div class="clearfix"></div>
	<?php if ($tpl_params->get('blog_layout') == 'masonry') { ?>
    	<?php if ($tpl_params->get('blog_item_spacing') == 0) { ?>
    	<hr class="blog_hr" />
        <?php } ?>
        </div><!-- END Post-intro -->
    <?php } else { ?>
		<hr />
	<?php } ?>
 
<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != JFactory::getDbo()->getNullDate())) : ?>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
