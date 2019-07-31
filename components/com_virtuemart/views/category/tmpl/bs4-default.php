<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
 * @link       https://virtuemart.net
 * @copyright  Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version    $Id: bs4-default.php 9924 2018-09-09 07:51:12Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<div data-vm="main-container">

    <?php //region vendor store description
    if ($this->show_store_desc and !empty($this->vendor->vendor_store_desc))
    { ?>
        <div class="vm-store-vendor-store-desc">
            <?php echo $this->vendor->vendor_store_desc; ?>
        </div>
        <hr>
    <?php } //endregion ?>

    <?php //region search form
    if (!empty($this->products) or ($this->showsearch or $this->keyword !== FALSE))
        $formAction = JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE);
    { ?>
        <div class="vm-store-category-search">
            <form action="<?php echo $formAction ?>" method="get">

                <?php if (!empty($this->searchCustomValues)) { ?>
                    searchCustomValues
                    <div class="vm-store-category-search-custom-values">
                        <?php echo $this->searchCustomValues ?>
                    </div>
                    <hr>
                <?php } ?>

                <div class="input-group">
                    <input type="text" name="keyword" value="<?php echo $this->keyword ?>" class="form-control"
                           placeholder="<?php echo vmText::_('COM_VIRTUEMART_SEARCH') ?>"/>
                    <div class="input-group-append">
                        <input type="submit" value="<?php echo vmText::_('COM_VIRTUEMART_SEARCH') ?>"
                               class="btn-secondary" onclick="this.form.keyword.focus();"/>
                    </div>
                </div>

                <small class="form-text text-muted vm-search-desc">
                    <?php echo vmText::_('COM_VM_SEARCH_DESC') ?>
                </small>

                <input type="hidden" name="view" value="category"/>
                <input type="hidden" name="option" value="com_virtuemart"/>
                <input type="hidden" name="virtuemart_category_id"
                       value="<?php echo vRequest::getInt('virtuemart_category_id', 0); ?>"/>
                <input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>
            </form>
        </div>
        <hr>
        <?php
        $j = 'jQuery(document).ready(function() {
                    jQuery(".changeSendForm")
                        .off("change",Virtuemart.sendCurrForm)
                        .on("change",Virtuemart.sendCurrForm);
                })';
        vmJsApi::addJScript('sendFormChange', $j);
    } //endregion ?>

    <?php
    if (!empty($this->products) or ($this->showsearch or $this->keyword !== FALSE))
    {
        ?>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <?php // Show child categories

        if (!empty($this->orderByList))
        { ?>
            <div class="orderby-displaynumber">
                <div class="floatleft vm-order-list">
                    <?php echo $this->orderByList['orderby']; ?>
                    <?php echo $this->orderByList['manufacturer']; ?>
                </div>
                <div class="vm-pagination vm-pagination-top">
                    <?php echo $this->vmPagination->getPagesLinks(); ?>
                    <span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter(); ?></span>
                </div>
                <div class="floatright display-number"><?php echo $this->vmPagination->getResultsCounter(); ?>
                    <br/><?php echo $this->vmPagination->getLimitBox($this->category->limit_list_step); ?></div>

                <div class="clear"></div>
            </div> <!-- end of orderby-displaynumber -->
        <?php } ?>

        <?php if (!empty($this->category->category_name)) { ?>
        <h1><?php echo vmText::_($this->category->category_name); ?></h1>
    <?php } ?>

        <?php
        if (!empty($this->products))
        {
            //revert of the fallback in the view.html.php, will be removed vm3.2
            if ($this->fallback)
            {
                $p                 = $this->products;
                $this->products    = array ();
                $this->products[0] = $p;
                vmdebug('Refallback');
            }

            echo shopFunctionsF::renderVmSubLayout(
                $this->productsLayout,
                array ('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $this->perRow, 'showRating' => $this->showRating)
            );

            if (!empty($this->orderByList))
            { ?>
                <div class="vm-pagination vm-pagination-bottom"><?php echo $this->vmPagination->getPagesLinks(); ?>
                    <span class="vm-page-counter"><?php echo $this->vmPagination->getPagesCounter(); ?></span></div>
            <?php }
        } else if ($this->keyword !== FALSE)
        {
            echo vmText::_('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
        }
        ?>


    <?php } ?>

</div>


<?php
if (vRequest::getInt('dynamic', FALSE) and vRequest::getInt('virtuemart_product_id', FALSE))
{
    if (!empty($this->products))
    {
        if ($this->fallback)
        {
            $p                 = $this->products;
            $this->products    = array ();
            $this->products[0] = $p;
            vmdebug('Refallback');
        }

        echo shopFunctionsF::renderVmSubLayout(
            $this->productsLayout,
            array ('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $this->perRow, 'showRating' => $this->showRating)
        );

    }

    return;
}
?>
<div class="category-view">
    <?php
    $js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";
    vmJsApi::addJScript('vm-hover', $js);


    ?>







    <?php
    if (!empty($this->showcategory_desc) and empty($this->keyword))
    {
        if (!empty($this->category))
        {
            ?>
            <div class="category_description">
                <?php echo $this->category->category_description; ?>
            </div>
        <?php }
        if (!empty($this->manu_descr))
        {
            ?>
            <div class="manufacturer-description">
                <?php echo $this->manu_descr; ?>
            </div>
        <?php }
    }

    // Show child categories
    if ($this->showcategory and empty($this->keyword))
    {
        if (!empty($this->category->haschildren))
        {
            echo ShopFunctionsF::renderVmSubLayout(
                'categories',
                array ('categories' => $this->category->children, 'categories_per_row' => $this->categories_per_row)
            );
        }
    }

    ?>
</div>

<?php
if (VmConfig::get('ajax_category', FALSE))
{
    $j = "Virtuemart.container = jQuery('.category-view');
	Virtuemart.containerSelector = '.category-view';";

    vmJsApi::addJScript('ajax_category', $j);
    vmJsApi::jDynUpdate();
}
?>
<!-- end browse-view -->
