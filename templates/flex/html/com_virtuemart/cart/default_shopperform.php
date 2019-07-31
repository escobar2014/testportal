<?php
/**
 *
 * Layout for the shopper form to change the current shopper
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Maik K�nnemann
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2013 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2458 2013-07-16 18:23:28Z kkmediaproduction $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<hr style="margin:20px auto 30px;" />
<h4 style="margin:20px 0 0;"><?php echo vmText::_ ('COM_VIRTUEMART_CART_CHANGE_SHOPPER'); ?></h4>
<form action="<?php echo JRoute::_ ('index.php'); ?>" method="post" class="form-inline" style="margin:0;">
			<div class="form-group">
				<input class="form-control" type="text" name="usersearch" size="20" maxlength="50" placeholder="<?php echo vmText::_('COM_VIRTUEMART_SEARCH'); ?>...">
                <input class="form-control btn btn-primary" type="submit" name="searchShopper" title="<?php echo vmText::_('COM_VIRTUEMART_SEARCH'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SEARCH'); ?>" class="button" />
			</div>
        	<div class="form-group" style="margin:22px 0 0;">
				<?php 
				if (!class_exists ('VirtueMartModelUser')) {
					require(VMPATH_ADMIN . DS . 'models' . DS . 'user.php');
				}

				$currentUser = $this->cart->user->virtuemart_user_id;
				echo JHtml::_('Select.genericlist', $this->userList, 'userID', 'class="vm-chzn-select" style="width:170px;"', 'id', 'displayedName', $currentUser,'userIDcart');
				?>
			
				<input type="submit" name="changeShopper" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="form-control btn btn-primary" style="margin-top:10px;" />
				<input type="hidden" name="view" value="cart"/>
				<input type="hidden" name="task" value="changeShopper"/>
			</div>
    
    
			<div class="row clearfix">
				<?php if($this->adminID && $currentUser != $this->adminID) { ?>
					<hr /><b class="centered"><?php echo vmText::_('COM_VIRTUEMART_CART_ACTIVE_ADMIN') .' '.JFactory::getUser($this->adminID)->name; ?></b><hr />
				<?php } ?>
				<?php echo JHtml::_( 'form.token' ); ?>
			</div>
		
	
</form>
<div class="clear"></div>
<h4 style="margin:30px 0 0;"><?php echo vmText::_ ('COM_VIRTUEMART_CART_CHANGE_SHOPPERGROUP'); ?></h4>

<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart'); ?>" method="post" class="form-inline" style="margin:0;">
        <div class="form-group" style="margin:22px 0 0;">
            <?php 
            if ($this->shopperGroupList) {
                echo $this->shopperGroupList;
            }
            ?>
        </div>
        <div class="form-group">
            <input type="submit" name="changeShopperGroup" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="form-control button" />
            <input type="hidden" name="view" value="cart"/>
            <input type="hidden" name="task" value="changeShopperGroup"/>
            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
        <?php if (JFactory::getSession()->get('tempShopperGroups', FALSE, 'vm')) { ?>
        <div class="form-group">
            <input type="reset" title="<?php echo vmText::_('COM_VIRTUEMART_RESET'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_RESET'); ?>" class="form-control btn btn-dark" onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart&task=resetShopperGroup'); ?>'"/>
        </div>
        <?php } ?>
</form>
<hr style="margin:30px auto;" />
