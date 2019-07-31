<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>

<ul class="accordion-menu VMmenu<?php echo $class_sfx ?>" >
<?php foreach ($categories as $category) {
		$active_menu = '';
		$active_child = '';
		$active_collapse = '';
		$collapsed = ' collapsed';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
		$cattext = $category->category_name. ' <span class="nmb_products">'.$categoryModel->countProducts($category->virtuemart_category_id ).'</span>';
	
	if (in_array( $category->virtuemart_category_id, $parentCategories)) {
		foreach ($category->childs as $children) { 
			if ($children->virtuemart_category_id == $active_category_id) {
				$active_child = ' deeper';
			}
		}  
		$active_collapse = ' in'; 
		$active_menu = ' class="active'.$active_child.'"';
		$collapsed = '';
	} ?>

<li<?php echo $active_menu; ?>><?php echo JHTML::link($caturl, $cattext); ?>
	
	<?php if ($category->childs ) { ?>	
    <span class="vmmenu-toggler<?php echo $collapsed; ?>" data-toggle="collapse" data-target="#collapse-vmmenu-<?php echo $category->virtuemart_category_id; ?>"><i class="open-icon fa fa-angle-down"></i></span>
    
    <ul class="collapse<?php echo $active_collapse . $class_sfx; ?>" id="collapse-vmmenu-<?php echo $category->virtuemart_category_id; ?>">
        <?php foreach ($category->childs as $child) {
			$active_child_menu = '';
            $catchildurl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
            $catchildtext = $child->category_name. '<span class="nmb_products">'.$categoryModel->countProducts($child->virtuemart_category_id ).'</span>'; 
			 if ($child->virtuemart_category_id == $active_category_id) $active_child_menu = ' class="active current"'; 
			 ?>
        <li<?php echo $active_child_menu; ?>><?php echo JHTML::link($catchildurl, $catchildtext); ?></li>
        <?php } ?>
    </ul>
    <?php } ?>
</li>
<?php } ?>
</ul>
