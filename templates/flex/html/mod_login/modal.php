<?php
/**
* @package		Joomla.Site
* @subpackage	mod_login
* @copyright	Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
* @license		GNU General Public License version 2 or later; see LICENSE.txt
*/

// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser();

?>
<div class="modal-login-wrapper">
	<span class="top-divider"></span>
    <div class="ap-modal-login" >
        <span class="ap-login">
            <a href="#" role="button" data-toggle="modal" data-target="#login">
                <i class="pe pe-7s-user"></i>
                <span class="info-content">
                <?php echo JText::_('FLEX_LOGIN'); ?>
                </span>
            </a>  
        </span>
    
        <!--Modal-->
        <div id="login" class="modal fade modal-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pe pe-7s-close-circle"></i></button>
                        <h2 class="title"><i class="pe pe-7s-user"></i><?php echo ($user->id>0) ? JText::_('MY_ACCOUNT') : JText::_('JLOGIN'); ?></h2>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo JRoute::_(htmlspecialchars(JUri::getInstance()->toString()), true, $params->get('usesecure')); ?>" method="post" id="modal-login-form" >
                            <?php if ($params->get('pretext')): ?>
                                <div class="pretext">
                                    <p><?php echo $params->get('pretext'); ?></p>
                                </div>
                            <?php endif; ?>
                            <fieldset class="userdata">
                                <input id="modallgn-username" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" type="text" name="username" class="input-block-level" required="required"  />
                                <input id="modallgn-passwd" type="password" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" name="password" class="input-block-level" required="required" />
                                <?php if (count($twofactormethods) > 1) : ?>
                                <!-- Secret Key -->
                                <div class="clearfix"></div>
                                <div id="form-login-secretkey" class="control-group">
                                    <div class="controls">
                                        <?php if (!$params->get('usetext')) : ?>
                                            <div class="input-prepend input-append">
                                                <span class="add-on">
                                                    <span class="fa fa-star"></span>
                                                        <label for="modallgn-secretkey" class="hidden"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
                                                </span>
                                                <input id="modallgn-secretkey" autocomplete="off" type="text" name="secretkey" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
                                                <span class="add-on hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                                                    <span class="fa fa-question-circle"></span>
                                                </span>
                                        </div>
                                        <?php else : ?>
                                        <div class="input-append">
                                            <label for="modallgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
                                            <input id="modallgn-secretkey" autocomplete="off" type="text" name="secretkey" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
                                            <span class="add-on hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                                                <span class="fa fa-question-circle"></span>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="clearfix"></div>
                                <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                                    <div class="modlgn-remember remember-wrap">
                                        <input id="modallgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                                        <label for="modallgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
                                    </div>
                                <?php endif; ?>
                                <div class="button-wrap pull-left">
                                    <input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>" />
                                </div>
                                <div class="forget-name-link pull-right">
                                    <?php echo JText::_('MOD_LOGIN_FORGOT'); ?> <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                                    <?php echo JText::_('MOD_LOGIN_FORGOT_USERNAME'); ?></a> <?php echo jText::_('MOD_LOGIN_OR'); ?> <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                                    <?php echo JText::_('MOD_LOGIN_FORGOT_PASSWORD'); ?></a> <?php echo JText::_('FLEX_QUESTION_MARK'); ?>
                                </div>
    
                                <input type="hidden" name="option" value="com_users" />
                                <input type="hidden" name="task" value="user.login" />
                                <input type="hidden" name="return" value="<?php echo $return; ?>" />
                                <?php echo JHtml::_('form.token'); ?>
                            </fieldset>
                            <?php if ($params->get('posttext')): ?>
                                <div class="posttext">
                                    <p><?php echo $params->get('posttext'); ?></p>
                                </div>
                            <?php endif; ?>
                        </form>
    
                    </div>
                    <!--/Modal body-->
    
                        <?php
                        $usersConfig = JComponentHelper::getParams('com_users');
                        if ($usersConfig->get('allowUserRegistration')) : ?>
                        <div class="modal-footer">
                        <?php echo JText::_('MOD_NEW_REGISTER'); ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                                <?php echo JText::_('MOD_LOGIN_REGISTER'); ?>
                            </a>
                         </div>
                        <?php endif; ?>
                    <!--/Modal footer-->
                </div> <!-- Modal content-->
            </div> <!-- /.modal-dialog -->
        </div><!--/Modal-->
    </div>
</div>