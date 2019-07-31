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
 * @version    $Id: default.php 9821 2018-04-16 18:04:39Z Milbo $
 */

// Joomla Security Check - no direct access to this file 
// Prevents File Path Exposure
defined('_JEXEC') or die('Restricted access');
?>

    <div data-vm="main-container">

        <?php //region Vendor Store Description
        if (!empty($this->vendor->vendor_store_desc) and VmConfig::get('show_store_desc', 1))
        { ?>
            <div class="vm-store-vendor-store-desc">
                <?php echo $this->vendor->vendor_store_desc; ?>
            </div>
            <hr>
        <?php } //endregion ?>

    </div>


<?php # Vendor Store Description
echo $this->add_product_link;
?>

<?php
# load categories from front_categories if exist
if ($this->categories and VmConfig::get('show_categories', 1)) echo $this->renderVmSubLayout(
    'categories', array ('categories' => $this->categories)
);

# Show template for : topten,Featured, Latest Products if selected in config BE
if (!empty($this->products))
{
    $products_per_row = VmConfig::get('homepage_products_per_row', 3);
    echo $this->renderVmSubLayout(
        $this->productsLayout,
        array ('products' => $this->products, 'currency' => $this->currency, 'products_per_row' => $products_per_row, 'showRating' => $this->showRating)
    ); //$this->loadTemplate('products');
}
