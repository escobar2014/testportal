<?php // no direct access
defined('_JEXEC') or die('Restricted access');

/* ID for jQuery dropdown */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));
?>
<ul class="accordion-menu VMmenu<?php echo $class_sfx ?>" id="<?php echo "VMmenu".$ID ?>" >
<?php foreach ($categories as $category) {
		$active_menu = '';
		$active_child = '';
		$active_collapse = '';
		$collapsed = ' collapsed';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
		if (($categoryModel->countProducts($category->virtuemart_category_id ) != 0 )) {
			$cattext = $category->category_name. ' <span class="nmb_products">'.$categoryModel->countProducts($category->virtuemart_category_id ).'</span>';
		} else {
			$cattext = $category->category_name;
		}
		if (in_array( $category->virtuemart_category_id, $parentCategories)) {
			foreach ($category->childs as $children) { 
				if ($children->virtuemart_category_id == $active_category_id) {
					$active_child = ' deeper';
				}
			}  
			$active_collapse = ' in'; 
			$active_menu = ' class="active'.$active_child.'"';
			$collapsed = '';
		} 
?>
<li<?php echo $active_menu; ?>><?php echo JHTML::link($caturl, $cattext);

if ($category->childs) { ?>

    <span class="vmmenu-toggler<?php echo $collapsed; ?>" data-toggle="collapse" data-target="#collapse-vmmenu-<?php echo $category->virtuemart_category_id; ?>"><i class="open-icon fa fa-angle-down"></i></span>
		
    <ul class="collapse<?php echo $active_collapse . $class_sfx; ?>" id="collapse-vmmenu-<?php echo $category->virtuemart_category_id; ?>">
    <?php foreach ($category->childs as $child) { 
            $active_child_menu = '';
            $catchildurl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
            if (($categoryModel->countProducts($child->virtuemart_category_id ) != 0 )) {
				$catchildtext = $child->category_name. '<span class="nmb_products">'.$categoryModel->countProducts($child->virtuemart_category_id ).'</span>';
			} else {
				$catchildtext = $child->category_name;
			}
            if ($child->virtuemart_category_id == $active_category_id) $active_child_menu = ' class="active"';
            ?>
            <li<?php echo $active_child_menu; ?>><?php echo JHTML::link($catchildurl, $catchildtext); ?></li>
    <?php } ?>
    </ul>
<?php } ?>
</li>
<?php } ?>
</ul>
