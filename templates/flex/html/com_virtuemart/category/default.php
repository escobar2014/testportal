<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 9017 2015-10-14 10:44:34Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');

if(vRequest::getInt('dynamic')){
	if (!empty($this->products)) {
		if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}

		echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

	}

	return ;
}

?> <div class="category-view">
<?php
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').click(function() {
	  jQuery(this).find('.orderlist').toggle();
	});
});
";
vmJsApi::addJScript('vm.hover',$js);

// Show child categories
if ($this->showcategory and empty($this->keyword)) {
	if (!empty($this->category->haschildren)) {
		echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children));
	}
}

if($this->showproducts){
?>
<div class="browse-view">
<?php

if ($this->showsearch or !empty($this->keyword)) {
	//id taken in the view.html.php could be modified
	$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>
	<h3 class="searched-word"><?php echo $this->keyword; ?></h3>

	<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE); ?>" method="get">

		<!--BEGIN Search Box -->
		<div class="vm-flex-search">
			<?php if(!empty($this->searchCustomList)) { ?>
			<div class="vm-search-custom-list">
				<?php echo $this->searchCustomList ?>
			</div>
			<?php } ?>

			<?php if(!empty($this->searchCustomValues)) { ?>
			<div class="vm-search-custom-values">
				<?php
                echo ShopFunctionsF::renderVmSubLayoutAsGrid(
                    'searchcustomvalues',
                    array (
                        'searchcustomvalues' => $this->searchCustomValues,
                        'options' => array (
                            'items_per_row' => array (
                                'xs' => 2,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ),
                        ),
                    )
                );
                ?>
			</div>
			<?php } ?>
			<input name="keyword" class="inputbox" type="text" size="220" value="<?php echo $this->keyword; ?>"/>
			<button type="submit" value="<?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?>" class="vm-search-button" onclick="this.form.keyword.focus();"/><i class="fa fa-search"></i></button>
		</div>
			<!-- input type="hidden" name="showsearch" value="true"/ -->
			<input type="hidden" name="view" value="category"/>
			<input type="hidden" name="option" value="com_virtuemart"/>
			<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>

	</form>
    <div style="margin-bottom:35px;" class="clear"></div>
	<!-- End Search Box -->
<?php
	$j = 'jQuery(document).ready(function() {

jQuery(".changeSendForm")
	.off("change",Virtuemart.sendCurrForm)
    .on("change",Virtuemart.sendCurrForm);
})';

	vmJsApi::addJScript('sendFormChange',$j);
} ?>
<h1><?php echo vmText::_($this->category->category_name); ?></h1>
<?php if (empty($this->keyword) and !empty($this->category)) { ?>
<div style="margin-bottom:25px;" class="clear"></div>
<div class="category_description">
	<?php echo $this->category->category_description; ?>
</div>
<?php } ?>

<hr />
<?php // Show child categories ?>
    
<div class="orderby-displaynumber">
	<div class="floatleft vm-order-list">
		<?php echo $this->orderByList['orderby']; ?>
		<?php echo $this->orderByList['manufacturer']; ?>
	</div>

	<div class="floatright display-number"><span><?php echo $this->vmPagination->getResultsCounter ();?></span><span><?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?></span></div>


	<div class="clear"></div>
</div> <!-- end of orderby-displaynumber -->



	<?php
	if (!empty($this->products)) {
		//revert of the fallback in the view.html.php, will be removed vm3.2
		if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}

	echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));
	?>
<div class="clear"></div>
<?php if(!empty($this->orderByList)) { ?>
		<div class="vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks (); ?>
		<?php // echo $this->vmPagination->getPagesCounter (); ?></div>
	<?php }
} elseif (!empty($this->keyword)) {
	echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>

<?php } ?>
</div>

<?php
if(VmConfig::get ('jdynupdate', TRUE)){
$j = "Virtuemart.container = jQuery('.category-view');
Virtuemart.containerSelector = '.category-view';";
$j = "jQuery(document).ready(function($) {var productCustomization=$('.cd-customization'),cart=$('.cd-cart'),animating=false;initCustomization(productCustomization);$('body').on('click',function(event){if($(event.target).is('body')||$(event.target).is('.cd-gallery')){deactivateCustomization()}});function initCustomization(items){items.each(function(){var actual=$(this),addToCartBtn=actual.find('.add-to-cart'),touchSettings=actual.next('.cd-customization-trigger');addToCartBtn.on('click',function(){if(!animating){animating=true;resetCustomization(addToCartBtn);addToCartBtn.addClass('is-added').find('path').eq(0).animate({'stroke-dashoffset':0},300,function(){setTimeout(function(){updateCart();addToCartBtn.removeClass('is-added').find('.addtocart-button').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',function(){addToCartBtn.find('path').eq(0).css('stroke-dashoffset','19.79');animating=false});if($('.no-csstransitions').length>0){addToCartBtn.find('path').eq(0).css('stroke-dashoffset','19.79');animating=false}},600)})}});touchSettings.on('click',function(event){event.preventDefault();resetCustomization(addToCartBtn)})})}function resetCustomization(selectOptions){selectOptions.siblings('[data-type=\"select\"]').removeClass('is-open').end().parents('.cd-single-item').addClass('hover').parent('li').siblings('li').find('.cd-single-item').removeClass('hover').end().find('[data-type=\"select\"]').removeClass('is-open')}function deactivateCustomization(){productCustomization.parent('.cd-single-item').removeClass('hover').end().find('[data-type=\"select\"]').removeClass('is-open')}function updateCart(){(!cart.find('.total_products').hasClass('items-added'))&&cart.find('.total_products').addClass('items-added').removeClass('empty_basket');var cartItems=cart.find('span'),text=parseInt(cartItems.text())+1;cartItems.text(text)}});";
//$j = preg_replace('/[\n\t]+/m', '', $j); // Remove whitespace
vmJsApi::addJScript('ajax_category',$j);
	vmJsApi::jDynUpdate();
}
?>