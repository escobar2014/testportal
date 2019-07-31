<?php
/**
*	@package	Ajax Intro Articles Module
*	@copyright	Copyright (C) 2018 Aplikko. All rights reserved.
*	@website:	http://www.aplikko.com
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

$doc = JFactory::getDocument();

$limit_words = $params->get('limit_words');
$columns = $params->get('columns', 3);
$intro_format = $params->get('intro_format', 1);
$article_style = $params->get('article_style', 1);
$inner_spacing = $params->get('inner_spacing', 0);

$overlay_intro_top = '';
$desc_top = '';
$article_style_start = '';
$article_style_end = '';
$entry_style = '';
$effects = ''; 
$intro_alignment = '';
$intro_width = '';
$desc_width = '';
$sppb_readmore = '';	
$margin_top_desc = '';

$md_grid = 12/$columns;
$sm_grid = '';
$xs_grid = '12';

if ($columns == 2) {
	$sm_grid = $md_grid;
} elseif ($columns == 3) {
    $sm_grid = '6';
	$md_grid = '4';
} elseif ($columns == 4) {
    $sm_grid = '4';
	$md_grid = '3';
} elseif ($columns == 6) {
    $sm_grid = '3';
	$xs_grid = '6';
} else {
    $sm_grid = '12';
}

// Equal Heights for Columns
($params->get('equal_heights') == 1 && $columns != 1) ? $equal_heights = 'match-height ' : $equal_heights = '';

if ($article_style != 2) { 
	$article_style_start = '<div class="article_style">';
	$article_style_end = '</div>';
	
	$margin_top_desc = 'margin-top:-'.$params->get('inner_spacing').'px;';
	
	if ($intro_format == 2) {
		$overlay_intro_top = '<div style="height:'.( $params->get('inner_spacing') * 2 ).'px;" class="clearfix"></div>';
		$desc_top = '<div style="height:'.$params->get('inner_spacing').'px;" class="clearfix"></div>';
	} 
}

if ($article_style == 3) {
	$entry_style = ' overlay';
}

$effect = $params->get('overlay_effects'); 
if ($effect != '') {
	$effects = ' ' . implode(' ', $params->get('overlay_effects')); 
}

// 1 Column Stylings
if ($columns == 1) { 
  $intro_alignment = ' '. $params->get('intro_alignment', 'intro-center');
  
	if ($params->get('intro_alignment') == 'intro-left') {
		$intro_width = ' style="width:'. $params->get('intro_width', '50').'%;float:left;"';
		$desc_width = ' style="width:'. ( 100 - $params->get('intro_width', '50') ).'%;float:left;padding-left:4%;'. $margin_top_desc .'"'; 
	} elseif ($params->get('intro_alignment') == 'intro-center') {
		$intro_width = '';
		$desc_width = '';
	
	} elseif ($params->get('intro_alignment') == 'intro-right') {
		$intro_width = ' style="width:'. $params->get('intro_width', '50').'%;float:right;"';
		$desc_width = ' style="width:'. ( 100 - $params->get('intro_width', '50') ).'%;float:right;padding-right:4%;text-align:right;'. $margin_top_desc .'"';
	} else {
		$intro_width = '';
		$desc_width = '';
	}
}

if ($params->get('readmore_button', 'default') == 'default') {
	$sppb_readmore = 'sppb-';
}

?>
<?php foreach ($list as $idx => $item) :  ?>

    <?php if ($idx %2 == 0): ?>
	<?php endif; 
	
			$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
			$post_attribs = new JRegistry(json_decode( $item->attribs ));
			$post_format = $post_attribs->get('post_format');
			$arrow_size = $post_attribs->get('arrow_size');
			$spfeatured_image = $post_attribs->get('spfeatured_image');
	
			$images = json_decode($item->images);
			$imgsize = $tpl_params->get('blog_list_image', 'default');
			$intro_image = '';
			$img_alt = '';
			$no_img = '';
			
			// Alt for images
			if(isset($images->image_intro_alt) && $images->image_intro_alt != '') {
				$img_intro_alt = htmlspecialchars($images->image_intro_alt);
			} else {
				$img_intro_alt = htmlspecialchars($item->title);
			}
		
			// Intro images
			if(isset($spfeatured_image) && $spfeatured_image != '') {

				if($imgsize == 'default') {
					$intro_image = $spfeatured_image;
				} else {
					$intro_image = $spfeatured_image;
					$basename = basename($intro_image);
					$list_image = JPATH_ROOT . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
					if(file_exists($list_image)) {
						$intro_image = JURI::root(true) . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
					}
				}
			} elseif (isset($images->image_intro) && !empty($images->image_intro)) {
				$intro_image = $images->image_intro;
			} elseif ($params->get('article_style', 1) == 2) {
				$no_img = ' no-intro-img';
			}
			
			// Link Intro images
			if ($params->get('image_intro_link') == 1) { 
			  $image_intro_link_start = '<a href="'. $item->link .'" itemprop="url">';
			  $image_intro_link_end = '</a>';
			} else {
			  $image_intro_link_start = '';
			  $image_intro_link_end = '';
			} 
			
			// Category in Overlay style
			$category_overlay = '';
			if ($params->get('show_category')) {
					$category_overlay = '<em class="caption-category"><span class="posted-in">'. JText::_('MOD_AJAX_INTRO_ARTICLES_POSTED') .'</span>'. $item->category_title .'</em>';
			} 
			
			// Hits in Overlay style
			$show_hits = ($params->get('show_hits') == 1) ? $show_hits = '<small class="hits"><i class="pe pe-7s-look"></i><meta itemprop="interactionCount" content="UserPageVisits:'.$item->hits.'" />'. $item->hits .'</small>' : $show_hits = '';
			
			// Article Rating
			$post_rating = '';
			if ($params->get('show_rating')) {
			$rating = (int) $item->rating;
			$post_rating = '<small class="ratings"><dd class="post_rating" id="post_vote_'.$item->id.'" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">'.JText::_('MOD_AJAX_INTRO_ARTICLES_RATING').': <div class="voting-symbol" itemprop="ratingValue">';
			$j = 0;
			for($i = $rating; $i < 5; $i++){ $post_rating .= '<span class="star" data-number="'.(5-$j).'" itemprop="ratingValue"></span>'; $j = $j+1;}
			for ($i = 0; $i < $rating; $i++) { $post_rating .= '<span class="star active" data-number="'.($rating - $i).'"></span>'; } $post_rating .= '</dd></small>'; }			
	?>
    <article class="post masonry_item col-xs-<?php echo $xs_grid; ?> col-sm-<?php echo $sm_grid; ?> col-md-<?php echo $md_grid; ?> ajax-post<?php echo $idx == 0 ? ' first' : ''; ?>" itemscope itemtype="http://schema.org/Article">
    <div class="<?php echo $equal_heights; ?>inner<?php echo $entry_style . $intro_alignment; ?>">
            <?php // Start DIV for 1 Column Layout 
			if(isset($intro_image) && $intro_image != '' && !empty($intro_image) && ($columns == 1) && ($params->get('intro_alignment') != 'intro-center')) { ?><div<?php echo $intro_width; ?>><?php } 
			echo $article_style_start;
            // Post Formats ?> 
			<?php if($intro_format != 1) {	
				if(isset($intro_image) && $intro_image != '' && !empty($intro_image)) {
					echo '<div class="entry-image intro-image'. $effects .'">';
					echo $article_style == 3 ? '<a href="'. $item->link .'" itemprop="url"><span class="caption-content"><span itemprop="name">'.$item->title.'</span>
					'.$category_overlay.'
					<span class="clearfix">' . $show_hits . $post_rating . '</span></span>' : $image_intro_link_start;
					echo '<img class="post-img" src="'. htmlspecialchars($intro_image) .'" alt="'. $img_intro_alt .'" itemprop="thumbnailUrl"/>';
					echo $article_style == 3 ? '</a>' : $image_intro_link_end;
					echo '</div>';
				} 
			} else {	
				if($post_format=='standard' && isset($intro_image) && $intro_image != '' && !empty($intro_image)) {
						echo '<div class="entry-image intro-image'. $effects .'">';
						echo $article_style == 3 ? '<a href="'. $item->link .'" itemprop="url"><span class="caption-content"><span itemprop="name">'.$item->title.'</span><em class="caption-category"><span class="posted-in">'. JText::_('MOD_AJAX_INTRO_ARTICLES_POSTED') .'</span>'. $item->category_title .'</em><span>' . $show_hits . $post_rating . '</span></span>' : $image_intro_link_start;
						echo '<img class="post-img" src="'. htmlspecialchars($intro_image) .'" alt="'. $img_intro_alt .'" itemprop="thumbnailUrl"/>';
						echo $article_style == 3 ? '</a>' : $image_intro_link_end;
						echo '</div>';
					//}
				} else {
					echo JLayoutHelper::render('joomla.content.post_formats.post_' . $post_format, array('params' => $post_attribs, 'item' => $item ));
				}
			}
			// Wrap for Flex and Overlay
			echo $article_style_end;
			// Start DIV for 1 Column Layout  
			if(isset($intro_image) && $intro_image != '' && !empty($intro_image) && ($columns == 1) && ($params->get('intro_alignment') != 'intro-center')) { ?></div>
            <div<?php echo $desc_width; ?>>
			<?php } ?>
         
   			<?php // Start If not Overlay Style
			if ($params->get('show_title') != 0) { ?>
            <?php echo ($article_style != 2  && $intro_image != '' && !empty($intro_image)) ? $desc_top : ''; ?>
                <h3 class="aga_heading<?php echo $no_img; ?>" itemscope>
                <a href="<?php echo $item->link; ?>" itemprop="url">
                    <span itemprop="name">
                        <?php echo $item->title; ?>
                    </span>
                </a>
                </h3>
                <?php } ?>
            <?php if ($params->get('show_introtext') != 0) { ?>
                <?php echo ($article_style != 2) && !empty($intro_image) && ($params->get('show_title') == 0) ? $overlay_intro_top : ''; ?>
                <div<?php echo (($article_style != 2) && !empty($intro_image) && ($params->get('show_title') == 0) || $inner_spacing == 0) ? ' style="margin-top:15px"' : ''; ?> itemprop="description" class="item-intro">
					 <?php if ($limit_words != '' && $limit_words != 0) {
						$text = ModAjaxIntroArticlesHelper::_cleanIntrotext($item->introtext); 
						$container = explode(' ', strip_tags($item->introtext));
						if(count($container) > $limit_words){
							$container = explode(" ", $text);
							//rebuild text by limit
							$text = implode(" ", array_slice($container, 0, $limit_words));
							//add a [...] icon
							$text .= ' <i style="vertical-align:bottom;margin:0 1px 1px;" class="pe pe-7s-more"></i>';	
							echo $text;
						} else {
							echo $text;
						}
					} else {
						echo ModAjaxIntroArticlesHelper::_cleanIntrotext($item->introtext);
					} ?>
				</div>
			 <?php } ?>
          
        <?php if ($params->get('show_author') || $params->get('show_category') || $params->get('show_date') || $params->get('show_hits') || $params->get('show_rating')) : ?>
   
            <dl<?php echo ($params->get('article_style', 1) != 2) && ($params->get('show_title') == 0) && ($params->get('show_introtext') == 0) ? ' style="margin:'.$params->get('inner_spacing').'px 0 0;"' : ''; ?> class="article-info" itemscope>
                <?php if ($params->get('show_author')): ?>
                <dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <span class="fa fa-user"></span>
                    <span data-toggle="tooltip" title="Written by" itemprop="creator" itemscope itemtype="https://schema.org/Person"><?php echo $item->created_by_alias ? $item->created_by_alias : $item->author;?></span></dd>	
                <?php endif; ?>
                <?php // Start If not Overlay Style
				if ($params->get('article_style') != 3) { ?> 
                <?php if ($params->get('show_category')): ?>
                <dd class="category-name">
				<i class="fa fa-folder-open-o"></i>
                <a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid)); ?>" class="item-category"> <?php echo $item->category_title; ?></a>
                </dd>
                <?php endif; ?>
                <?php } ?>
                
                <?php if ($params->get('show_date')): ?>
                <dd class="published" itemprop="datePublished">
                <i class="fa fa-calendar-o"></i>
                <time class="item-time" data-toggle="tooltip" title="Published Date" datetime="<?php echo JHtml::_('date', $item->created, 'c'); ?>" itemprop="dateCreated"><?php echo JHtml::_('date', $item->created, JText::_($params->get('show_date_format', 'DATE_FORMAT_LC3'))) ;?>
           		</time>
                </dd>
                <?php endif; ?>
             	<?php // Start If not Overlay Style
				if ($params->get('article_style') != 3) { ?>
                <?php if ($params->get('show_hits')): ?>
                    <dd class="hits">
                    <span class="fa fa-eye"></span>
                    <meta itemprop="interactionCount" content="UserPageVisits:<?php echo $item->hits; ?>" />
                    <?php echo $item->hits; ?>
                    </dd>
                <?php endif; ?>
                
				<?php 
                    // Article Rating
                    if ($params->get('show_rating')):
                        $rating = (int) $item->rating;
                    ?>
                    <dd class="post_rating" id="post_vote_<?php echo $item->id; ?>" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                    <?php echo JText::_('MOD_AJAX_INTRO_ARTICLES_RATING'); ?>: <div class="voting-symbol" itemprop="ratingValue">
                    <?php
                    $j = 0;
                    for($i = $rating; $i < 5; $i++){
                        echo '<span class="star" data-number="'.(5-$j).'" itemprop="ratingValue"></span>';
                        $j = $j+1;
                    }
                    for ($i = 0; $i < $rating; $i++)
                    {
                        echo '<span class="star active" data-number="'.($rating - $i).'"></span>';
                    }
                    ?>
                    </dd>
                <?php endif; ?>
                <?php } ?>
			</dl>   
       <?php endif; ?>         

				<?php // Read More ?>
                <?php if ($params->get('show_readmore')): ?>
                <div class="readmore pull-<?php echo $params->get('align_readmore_button', 'left'); ?>"><a class="btn <?php echo $sppb_readmore; ?>btn-<?php echo $params->get('readmore_button', 'default'); ?>" href="<?php echo $item->link; ?>" itemprop="url">
				<?php echo $params->get('readmore_btn_text', JText::_('MOD_AJAX_INTRO_ARTICLES_AJAX_READMORE_BUTTON_TXT')); ?>
            	</a></div>
                <?php endif; 
				
				// Social Share buttons
				if ($params->get('show_social_share')):
				echo JLayoutHelper::render('joomla.content.social_share.entrylist_share', $item); 
				endif;
				
				//Tags 
                if ($params->get('show_tags')):
					$item->tagLayout = new JLayoutFile('joomla.content.tags');
					echo '<div class="clearfix"></div>'.
					$item->tagLayout->render($item->tags->itemTags);
				endif; ?>
        <?php if(isset($intro_image) && $intro_image != '' && !empty($intro_image) && ($columns == 1) && ($params->get('intro_alignment') != 'intro-center')) { ?></div><?php } ?> 
        <div class="clearfix"></div>
    </div>     
</article>
<?php endforeach; ?>