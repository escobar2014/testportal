<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jPrice();

?>
<div class="vmgroup singleview <?php echo $params->get ('moduleclass_sfx') ?>">
<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
<?php } ?>
<?php foreach ($products as $product) { ?>
	<div class="product-single-style">
    <div class="spacer">  
    <div class="product-image col-xs-12 col-sm-5 col-md-3 match-height">
<?php if (!empty($product->images[0]) )
 $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" ',false) ;
 else $image = ''; 
 echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,array('title' => $product->product_name) );
 //echo '<div class="clear"></div>'; ?>
  	</div>

    <?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>
     <div class="spacer-inner col-xs-12 col-sm-7 col-md-9 match-height mobile-centered">
     
        <h3><a href="<?php echo $url; ?>"><?php echo $product->product_name ?></a></h3>
            <?php if ($show_price) {  ?>
                <?php if (!empty($product->prices['salesPrice'])) {
                    echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                }
            
                if (!empty($product->prices['salesPriceWithDiscount'])) {
                    echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                }
            } ?>
        
            <?php if ($show_addtocart) {  ?>
                <hr /><div class="clear"></div>
            <div class="mobile-centered"><?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product)); ?></div>
            <div class="clear"></div>
            
            <?php } ?>
        </div> <!--spacer-->
     <div class="clear"></div><hr />
   </div>

	<?php } ?>
<?php if ($footerText) { ?>
	<div class="vmheader"><?php echo $footerText ?></div>
<?php } ?>
<?php
if(VmConfig::get ('jdynupdate', TRUE)){

$j = "jQuery(document).ready(function($) {
var productCustomization=$('.cd-customization'),cart=$('.cd-cart'),animating=false;initCustomization(productCustomization);$('body').on('click',function(event){if($(event.target).is('body')||$(event.target).is('.cd-gallery')){deactivateCustomization()}});function initCustomization(items){items.each(function(){var actual=$(this),addToCartBtn=actual.find('.add-to-cart'),touchSettings=actual.next('.cd-customization-trigger');addToCartBtn.on('click',function(){if(!animating){animating=true;resetCustomization(addToCartBtn);addToCartBtn.addClass('is-added').find('path').eq(0).animate({'stroke-dashoffset':0},300,function(){setTimeout(function(){updateCart();addToCartBtn.removeClass('is-added').find('.addtocart-button').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){addToCartBtn.find('path').eq(0).css('stroke-dashoffset','19.79');animating=false});if($('.no-csstransitions').length>0){addToCartBtn.find('path').eq(0).css('stroke-dashoffset','19.79');animating=false}},600)})}});touchSettings.on('click',function(event){event.preventDefault();resetCustomization(addToCartBtn)})})}function resetCustomization(selectOptions){selectOptions.siblings('[data-type=\"select\"]').removeClass('is-open').end().parents('.cd-single-item').addClass('hover').parent('li').siblings('li').find('.cd-single-item').removeClass('hover').end().find('[data-type=\"select\"]').removeClass('is-open')}function deactivateCustomization(){productCustomization.parent('.cd-single-item').removeClass('hover').end().find('[data-type=\"select\"]').removeClass('is-open')}function updateCart(){(!cart.find('.total_products').hasClass('items-added'))&&cart.find('.total_products').addClass('items-added').removeClass('empty_basket');var cartItems=cart.find('span'),text=parseInt(cartItems.text())+1;cartItems.text(text)}
	
});";
vmJsApi::addJScript('vmPreloader',$j);
}
echo vmJsApi::writeJS();
?>
</div>