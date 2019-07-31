<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="row-fluid registration-wrapper">
<i class="pe pe-7s-users hidden-xs"></i>
	<div class="col-sm-6 col-sm-offset-3">
		<div class="registration<?php echo $this->pageclass_sfx?>">
			<?php if ($this->params->get('show_page_heading')) : ?>
				<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
			<?php endif; ?>
            <form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
			<?php // Iterate through the form fieldsets and display each one. ?>
            <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                <?php $fields = $this->form->getFieldset($fieldset->name); ?>
                <?php if (count($fields)) : ?>
                <fieldset class="clearfix">
                    <?php // If the fieldset has a label set, display it as the legend. ?>
                    <?php if (isset($fieldset->label)) : ?>
                        <div class="spacer clearfix"><legend><?php echo JText::_($fieldset->label); ?></legend></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="group-control">
                        <?php echo $this->form->renderFieldset($fieldset->name); ?>
                        </div>
                    </div>  
                </fieldset>    
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="form-group clearfix">
                <button type="submit" class="btn btn-success validate">
                    <?php echo JText::_('JREGISTER'); ?>
                </button>
                <a class="btn btn-danger" href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>">
                    <?php echo JText::_('JCANCEL'); ?>
                </a>
                <input type="hidden" name="option" value="com_users" />
                <input type="hidden" name="task" value="registration.register" />
            </div>
            <?php echo JHtml::_('form.token'); ?>
        </form>
		</div>
	</div>
</div>
