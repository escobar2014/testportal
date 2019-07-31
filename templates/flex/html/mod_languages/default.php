<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('stylesheet', 'mod_languages/template.css', array(), false);

if ($params->get('dropdown', 1) && !$params->get('dropdownimage', 0)) {
	JHtml::_('formbehavior.chosen');
}
?>
<div class="mod-languages<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) : ?>
	<div class="pretext"><p><?php echo $headerText; ?></p></div>
<?php endif; ?>

<?php if ($params->get('dropdown', 1) && !$params->get('dropdownimage', 0)) : ?>
	<div class="btn-group">
		<?php foreach ($list as $language) : ?>
			<?php if ($language->active) : ?>
				<?php $flag = ''; ?>
				<?php $flag .=  $language->title_native; ?>
				<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $flag; ?><i class="pe pe-7s-angle-down"></i></a>
			<?php endif; ?>
		<?php endforeach;?>
		<ul class="<?php echo $params->get('lineheight', 1) ? 'lang-block' : 'lang-block'; ?> dropdown-menu" dir="<?php echo JFactory::getLanguage()->isRtl() ? 'rtl' : 'ltr'; ?>">
		<?php foreach ($list as $language) : ?>
			<?php if ($params->get('show_active', 0) || !$language->active) : ?>
				<li class="<?php echo $language->active ? 'lang-active' : 'no-flag'; ?>" >
				<a href="<?php echo $language->link;?>">
					<?php echo $language->title_native; ?>
				</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
<?php elseif ($params->get('dropdown', 1) && $params->get('dropdownimage', 0)) : ?>
	<div class="btn-group">
		<?php foreach ($list as $language) : ?>
			<?php if ($language->active) : ?>
				<?php $flag = ''; ?>
				<?php $flag .= JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true); ?>
				<?php $flag .=  $language->title_native; ?>
				<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $flag; ?><i class="pe pe-7s-angle-down"></i></a>
			<?php endif; ?>
		<?php endforeach;?>
		<ul class="<?php echo $params->get('lineheight', 1) ? 'lang-block' : 'lang-block'; ?> dropdown-menu" dir="<?php echo JFactory::getLanguage()->isRtl() ? 'rtl' : 'ltr'; ?>">
		<?php foreach ($list as $language) : ?>
			<?php if ($params->get('show_active', 0) || !$language->active) : ?>
				<li class="<?php echo $language->active ? 'lang-active' : ''; ?>" >
				<a href="<?php echo $language->link;?>">
						<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true); ?>
						<?php echo $language->title_native; ?>
				</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>
<?php else : ?>
	<ul class="<?php echo $params->get('inline', 1) ? 'lang-inline' : 'lang-block vertical';?>">
	<?php foreach ($list as $language) : ?>
		<?php if ($params->get('show_active', 0) || !$language->active):?>
			<li class="<?php echo $language->active ? 'lang-active' : '';?>" dir="<?php echo JLanguage::getInstance($language->lang_code)->isRtl() ? 'rtl' : 'ltr' ?>">
			<a href="<?php echo $language->link;?>">
			<?php if ($params->get('image', 1)):?>
				
				<?php if ($params->get('inline') == 0) { ?>
                	<?php echo JHtml::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, array('title' => $language->title_native), true);?>
                    <span><?php echo ($params->get('full_name', 1)) ? $language->title_native : '' ;?></span>
                    <?php echo $language->active ? '<i class="fa fa-check"></i>' : '';?>
                <?php } else {?>
                <img src="<?php echo 'media/mod_languages/images/' . $language->image . '.gif' ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $language->title_native; ?>" alt="<?php echo $language->title_native; ?>" />
                 <?php } ?>
 
			<?php else : ?>
				<p>
					<?php echo ($params->get('full_name', 1)) ? $language->title_native : $language->image; ?>
				</p>
			<?php endif; ?>
			</a>
			</li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
<?php endif; ?>

<?php if ($footerText) : ?>
	<div class="posttext"><p><?php echo $footerText; ?></p></div>
<?php endif; ?>
</div>
