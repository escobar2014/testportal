<?php
/**
*
* Layout for the add to cart popup
*
* @package	VirtueMart
* @subpackage Cart
* @author Max Milbers
*
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2013 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$media_model 	= VmModel::getModel('media');
$product_model 	= VmModel::getModel('product');

if (!class_exists('CurrencyDisplay')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
$currency = CurrencyDisplay::getInstance( );

?>

<!-- popup cart -->
<div class="popup-cart"> 

	<?php if($this->products){ ?>
	<h3 class="title"><?php echo JText::_('VM_POPUP_PRODUCT_ADDED_SUCCESS'); ?> 
		<span><?php echo JText::_('VM_POPUP_YOUR_SH0PPING_CART'); ?></span>
	</h3>
	
	<div class="item-wrap">
	<?php foreach($this->products as $product){
			if($product->quantity>0){
				$images  = $media_model->createMediaByIds($product->virtuemart_media_id, $product->quantity); 
				$prices  = $product_model->getPrice($product->virtuemart_product_id, 1);
				?>
				<div class="col-sm-5">
					<?php if(isset($images[0]) && $images[0]) {
						echo $images[0]->displayMediaThumb ('class="ProductImage"', FALSE); 
					} ?>	
				</div> <!-- /.col-sm-5 -->
				<div class="col-sm-7">
					<h3 class="item-name"><?php echo $product->product_name; ?></h3>
					<div class="vm-price-box">
						<?php if ( isset($product->allPrices[0]['product_override_price']) && round($product->allPrices[0]['product_override_price']) != 0) { ?>
	                    	<ins>
				                <?php echo $currency->createPriceDiv ('salesPrice', '', $prices, FALSE, FALSE, 1.0, TRUE); ?>
                                <?php echo $currency->createPriceDiv ('salesPriceTt', '', $prices, FALSE, FALSE, 1.0, TRUE); ?>
				            </ins>
				            <del>    
				                <?php echo $currency->createPriceDiv ('basePriceVariant', '', $prices, FALSE, FALSE, 1.0, TRUE); ?>
				            </del>
                    	<?php } else{ ?>
                    		<ins>
				                <?php echo $currency->createPriceDiv ('salesPrice', '', $prices, FALSE, FALSE, 1.0, TRUE); ?>
                                <?php echo $currency->createPriceDiv ('salesPriceTt', '', $prices, FALSE, FALSE, 1.0, TRUE); ?>
				            </ins>
                    	<?php } ?>
					</div>
					<p class="popup-cart-product-quantity">
						<span><?php echo JText::_('VM_POPUP_SH0PPING_CART_QUANTITY'); ?> </span>
						<span class="badge"><?php echo $product->quantity; ?></span>
					</p>
				</div> <!-- /.col-sm-7 -->
			<?php } else { 
				if(!empty($product->errorMsg)){ ?>
					<div><?php echo $product->errorMsg ?></div>
				<?php } // !empty($product->errorMsg)
			} // else

		} // END:: foreach
	} // has product ?>
	</div> <!-- //item-wrap -->
    <div class="clear"></div>

	<div class="button-group row">
		<a class="continue_link button" href="<?php echo $this->continue_link; ?>" >
			<?php echo vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING'); ?> 
		</a>
		<a class="showcart floatright" href="<?php echo  $this->cart_link; ?>">
			<?php echo vmText::_('VM_CART_SHOW_TITLE'); ?>
		</a>
	</div> <!-- //button-group -->

</div> <!-- //.popup-cart -->
<br style="clear:both">
