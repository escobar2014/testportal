<?php // no direct access
defined ('_JEXEC') or die('Restricted access');
// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
vmJsApi::jPrice();


$col = 1;
$pwidth = ' width' . floor (100 / $products_per_row);
if ($products_per_row > 1) {
	$float = "floatleft";
} else {
	$float = "center";
}
if ($products_per_row == 1) {
	$col_sm = '12';
} else if ($products_per_row > 3) {
	$col_sm = '4';
} else {
	$col_sm = '6';
}
?>
<?php if ($display_style == "div") { ?>
<div class="featured-view <?php echo $params->get ('moduleclass_sfx') ?>">
<?php } else { ?>
<div class="vmgroup listview clearfix <?php echo $params->get ('moduleclass_sfx') ?>">
<?php } ?>

	<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
	<?php
}
	if ($display_style == "div") {
		?>
		<div class="row productwrap vmproduct<?php echo $params->get ('moduleclass_sfx'); ?> productdetails">
			<?php foreach ($products as $product) { ?>
			<div class="products product-list-style col-sm-<?php echo $col_sm; ?> col-md-<?php echo round(12/$products_per_row) ?> <?php echo $float ?>">
				<div class="spacer center">
					<div class="spacer-img">
					<?php
					if (!empty($product->images[0])) {
						$image = $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE);
					} else {
						$image = '';
					}
					echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
					echo '</div>';
					
					$url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
						$product->virtuemart_category_id); ?>
					<div class="spacer-inner">	
                  <h2><a href="<?php echo $url ?>"><?php echo $product->product_name ?></a></h2>
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
					<?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product)); ?>
					<div class="clear"></div>
                    
					<?php } ?>
                   </div> <!--spacer-inner-->
			    </div>
			</div>
			<?php
			if ($col == $products_per_row && $products_per_row && $col < $totalProd) {
				//echo "	</div><div style='clear:both;'>";
				$col = 1;
			} else {
				$col++;
			}
		} ?>
		</div>
		<div class="clear"></div>

		<?php
	} else {
		$last = count ($products) - 1;
		?>

		<ul class="vm-product<?php echo $params->get ('moduleclass_sfx'); ?> productdetails">
			<?php foreach ($products as $product) : ?>
			<li class="col-sm-<?php echo $col_sm; ?> col-md-<?php echo round(12/$products_per_row) ?> <?php echo $float ?>">
			
				<div class="spacer-img">
				<?php
				if (!empty($product->images[0])) {
					$image = $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE);
				} else {
					$image = '';
				}
				echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
				echo '</div>';
				
				$url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
					$product->virtuemart_category_id); ?>
				<div class="spacer-inner">		
					<h5><a href="<?php echo $url ?>"><?php echo $product->product_name ?></a></h5>
                    <?php 
					if ($show_price and isset($product->prices)) {
						echo '<h4 class="product-price">'.$currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
						if ($product->prices['salesPriceWithDiscount'] > 0) {
							echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
						}
						echo '</h4>';
						
					}
				if ($show_addtocart) {
					echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product));
				}
				echo '</div>';
				?>
				
			</li>
			<?php
			if ($col == $products_per_row && $products_per_row && $last) {
				echo '</ul>';
		       
		    echo '<ul class="vm-product' . $params->get ('moduleclass_sfx') . ' productdetails">';
				$col = 1;
			} else {
				$col++;
			}
			$last--;
		endforeach; ?>
		</ul>
		

		<?php
	}
	if ($footerText) : ?>
		<div class="vmfooter<?php echo $params->get ('moduleclass_sfx') ?>">
			<?php echo $footerText ?>
		</div>
		<?php endif; ?>
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