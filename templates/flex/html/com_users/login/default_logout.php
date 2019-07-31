<?php
/**
 * Flex @package Helix3 Framework
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

defined('_JEXEC') or die;
?>
<div class="row">
	<div class="col-sm-4 col-sm-offset-4">
		<div class="logout<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h2>
					<?php echo $this->escape($this->params->get('page_heading')); ?>
				</h2>
                <hr />
			<?php endif; ?>

			<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
			<div class="logout-description">
			<?php endif; ?>

				<?php if ($this->params->get('logoutdescription_show') == 1) : ?>
					<p><?php echo $this->params->get('logout_description'); ?></p>
				<?php endif; ?>

				<?php if (($this->params->get('logout_image') != '')) :?>
					<p><img src="<?php echo $this->escape($this->params->get('logout_image')); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/></p>
				<?php endif; ?>

			<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
			</div>
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
				<div class="form-group">
					<button type="submit" class="btn btn-primary"><i style="margin-left:-3px;margin-right:9px;" class="fa fa-unlock-alt"></i><?php echo JText::_('JLOGOUT'); ?></button>
				</div>
				<?php if ($this->params->get('logout_redirect_url')) : ?>
					<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_url', $this->form->getValue('return'))); ?>" />
				<?php else : ?>
					<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_menuitem', $this->form->getValue('return'))); ?>" />
				<?php endif; ?>
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	</div>
</div>
