<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

JHtml::_('stylesheet', 'system/frontediting.css', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'system/frontediting.js', array('version' => 'auto', 'relative' => true));
//Helix3
helix3::addLess('frontend-edit', 'frontend-edit');
helix3::addJS('frontend-edit.js');	
?>
<!-- <span class="top-divider"></span> -->
<div class="ap-modal-login sp-mod-login">
	<div class="ap-my-account-menu dropdown">
		<ul class="ap-my-account">
			<li>
            	<button data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <div class="ap-signin logged-in">
					<div class="signin-img-wrap">
                        <i class="pe pe-7s-user"></i>	
					</div>
					<div class="info-wrap">
						<span class="info-text">
                          <?php if ($params->get('greeting')) : ?>
                            <?php if ($params->get('name') == 0) : ?>
                                <?php echo JText::_('FLEX_LOGIN_HI'); ?>
                                <?php echo htmlspecialchars($user->get('name')); ?>
                            <?php else : ?>
                                <?php echo JText::_('FLEX_LOGIN_HI'); ?>
                                <?php echo htmlspecialchars($user->get('username')); ?>
                            <?php endif; ?>
							<i class="pe pe-7s-angle-down"></i>
                          <?php endif; ?>
						</span>
					 </div>
				   </div>
                </button>
                <div class="dropdown-menu">	
                <?php echo JFactory::getDocument()->getBuffer('modules', 'myaccount', array('style' => 'none')); ?>
                </div>
			</li>
		</ul>
	</div>
</div>