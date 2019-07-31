<?php // no direct access
defined('_JEXEC') or die('Restricted access');

	if ((int) $data->totalProductTxt == 0) {
		$basket = 'total_products empty_basket';
		$empty_cart = 'EMPTY CART';
	} else {
		$basket = 'total_products items-added';
		$empty_cart = '';
	}
	//Add js and css files
	$doc = JFactory::getDocument();
	$doc->addScript( JURI::root(true) . '/templates/flex/js/vm-cart.js' );
?>
                
<!-- Virtuemart 2 Ajax Card -->
<div class="vmCartModule <?php echo $params->get('moduleclass_sfx'); ?>" id="vmCartModule<?php echo $params->get('moduleid_sfx'); ?>" >
    
       <div id="cart-menu">
        <a id="cd-menu-trigger" href="#0" class="cd-cart">
        <i class="pe pe-7s-cart"></i>
            <?php if ((int) $data->totalProduct == 0) { ?>
                <div class="<?php echo $basket; ?>">0</div>
            <?php } else { ?>
               <div class="<?php echo $basket; ?>"><?php echo $data->totalProductTxt; ?></div>
            <?php } ?>
        </a>
    </div>
    
	<nav id="cd-lateral-nav">
		<div class="cd-navigation">
		   
          <h5 style="text-align:center;"><?php echo vmText::_('VM_RECENTLY_ADDED_ITEMS') ?></h5><hr />
      
<?php if ($show_product_list) { ?>
	<div id="hiddencontainer" class="hiddencontainer" style="display:none;">
		<div class="vmcontainer">
			<div class="product_row">
				<div class="quantity"></div>
                <div class="image cart-image pull-left"></div>                
                <div class="cart-item">
                   <div class="product_name"></div>
					<?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
                        <div class="subtotal_with_tax"></div>  
                    <?php } ?>
                    <div class="customProductData"></div>                    
                  </div>  
               <hr style="width:100%;clear:both;" /> 
			</div>
		</div>
	</div>
	<div class="vm_cart_products">
		<div class="vmcontainer">

			<?php foreach ($data->products as $product){ ?>  
          
           		 <div class="cd-single-item">   
                    <div class="quantity"><?php echo $product['quantity'] ?></div>
					<div class="image cart-image pull-left">
						<?php echo $product["image"]; ?>
                    </div>
                    <div class="cart-item">
                        <div class="product_name"><?php echo $product['product_name'] ?></div>                
						<?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
                          <div class="subtotal_with_tax"><?php echo $product['subtotal_with_tax'] ?></div>
                        <?php } ?>
                        <?php if ( !empty($product['customProductData']) ) { ?>
                            <div class="customProductData"><?php echo $product['customProductData'] ?></div>
                        <?php } ?>
                	</div>
                  <hr style="width:100%;clear:both;display:block;" />
                </div>
                
			<?php } ?>
   
         <?php if(empty($data->products)){ ?>
            <div class="empty_cart"><h3><?php echo vmText::_('VM_EMPTY_CART'); ?></h3>
            <i class="pe pe-7s-cart">
            <span class="fa fa-ban"></span>
            </i>
            </div>
          <?php } ?>   
       
     </div>    
	</div>
<?php } ?>

        <div class="total">
            <?php if ($data->totalProduct and $show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
            <?php echo $data->billTotal; ?>
            <?php } ?>
        </div>

        <div style="clear:both;display:block;" class="show_cart">
            <?php if ($data->totalProduct) echo $data->cart_show; ?>
        </div>
        
		</div>
	</nav>
<?php
$view = vRequest::getCmd('view');
if($view!='cart' and $view!='user'){
	?><div class="payments-signin-button" ></div><?php
}
?>
<noscript>
<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
</noscript>
</div>
