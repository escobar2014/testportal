<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

	if ($this->category->haschildren) {
	    $iCol = 1;
	    $iCategory = 1;
	    $categories_per_row = VmConfig::get('categories_per_row', 3);
	    $category_cellwidth = ' width' . floor(100 / $categories_per_row);
	    $verticalseparator = " vertical-separator";
	    ?>

	    <div class="category-view">

		<?php
		// Start the Output
		if (!empty($this->category->children)) {
		    foreach ($this->category->children as $category) {

			// Show the horizontal seperator
			if ($iCol == 1 && $iCategory > $categories_per_row) {
			    ?>
		    	<div class="horizontal-separator"></div>
			    <?php
			}

			// this is an indicator whether a row needs to be opened or not
			if ($iCol == 1) {
			    ?>
		    	<div class="row productwrap">
				<?php
			    }

			    // Show the vertical seperator
			    if ($iCategory == $categories_per_row or $iCategory % $categories_per_row == 0) {
				$show_vertical_separator = ' ';
			    } else {
				$show_vertical_separator = $verticalseparator;
			    }

			    // Category Link
			    $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);

			    // Show Category
				if (VmConfig::get('showcategory', 1)) {
			    ?>
                <div class="clear"></div>
			    <div class="category col-sm-6 col-md-<?php echo round(12/$categories_per_row) . $show_vertical_separator ?>">
                  <div class="spacer">
                      <div class="spacer-img">
                        <a href="<?php echo $caturl ?>">
                          <?php // if ($category->ids) {
                            echo $category->images[0]->displayMediaThumb("",false);
                            echo '<span class="overlay"><h3>'.vmText::_($category->category_name).'</h3></span>';
                          //} ?>
                          </a>
                      </div>
                  </div>
                </div>
			    <?php 
				}
			    $iCategory++;

			    // Do we need to close the current row now?
			    if ($iCol == $categories_per_row) {
				?>
		    	    <div class="clear"></div>
		    	</div>
			    <?php
			    $iCol = 1;
			} else {
			    $iCol++;
			}
		    }
		}
		// Do we need a final closing row tag?
		if ($iCol != 1) {
		    ?>
	    	<div class="clear"></div>
	        </div>
	<?php } ?>
	</div>
    <?php }