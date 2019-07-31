<?php
/**
 * @package     SP Simple Portfolio @ Flex
 * @subpackage  mod_spsimpleportfolio
 * @copyright   Copyright (C) 2010 - 2019 JoomShaper. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;
jimport( 'joomla.filesystem.file' );
$layout_type = $params->get('layout_type', 'default');
$column_bg = $params->get('column_bg');
$show_view_button = $params->get('show_view_button', 1);
$show_zoom_button = $params->get('show_zoom_button', 1);
$show_tags = $params->get('show_tags', 1);
$show_filter = $params->get('show_filter');
$filter_divider = $params->get('filter_divider');
$filter_style = $params->get('filter_style');
$show_all_txt = $params->get('show_all_txt');
$filter_margin = $params->get('filter_margin');
$video_width = $params->get('video_width');
$video_height = $params->get('video_height');

//Params
$cparams 	= JComponentHelper::getParams('com_spsimpleportfolio');
$square 	= strtolower( $cparams->get('square', '600x600') );
$rectangle 	= strtolower( $cparams->get('rectangle', '600x400') );
$tower 		= strtolower( $cparams->get('tower', '600x800') );

//Add js and css files
$doc = JFactory::getDocument();

//First unset default files
unset($doc->_styleSheets[ JURI::root(true) . '/components/com_spsimpleportfolio/assets/css/featherlight.min.css' ]);
unset($doc->_styleSheets[ JURI::root(true) . '/components/com_spsimpleportfolio/assets/css/spsimpleportfolio.css' ]);
unset($doc->_scripts[ JURI::root(true) . '/components/com_spsimpleportfolio/assets/js/jquery.shuffle.modernizr.min.js' ]);
unset($doc->_scripts[ JURI::root(true) . '/components/com_spsimpleportfolio/assets/js/featherlight.min.js' ]);
unset($doc->_scripts[ JURI::root(true) . '/components/com_spsimpleportfolio/assets/js/spsimpleportfolio.js' ]);

//Add updated js and css files
$doc->addStylesheet( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/css/featherlight.css' );
$doc->addStylesheet( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/css/featherlight.gallery.css' );
$doc->addStylesheet( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/css/spsimpleportfolio.css' );
$doc->addScript( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/js/jquery.shuffle.modernizr.min.js' );
$doc->addScript( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/js/featherlight.min.js' );
$doc->addScript( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/js/featherlight.gallery.min.js' );
$doc->addScript( JURI::root(true) . '/templates/flex/html/com_spsimpleportfolio/assets/js/spsimpleportfolio.js' );

$menu 	= JFactory::getApplication()->getMenu();
$itemId = '';
if(is_object($menu->getActive())) {
	$active = $menu->getActive();
	$itemId = '&Itemid=' . $active->id;
}

$filter_divider != '' ? $filter_divider = '<span class="simple-divider">'.$filter_divider.'</span>' : '';
$filter_style == 'simple' ? $simple_style = $filter_divider : $simple_style = '';

$showbtns = '';
if($show_zoom_button==0 && $show_view_button==0 && $layout_type=='default') { 
   $showbtns = '.sp-simpleportfolio .sp-simpleportfolio-item .sp-simpleportfolio-overlay-wrapper .sp-simpleportfolio-overlay {background:transparent}';
}

$addstyle = 'body.rtl .sp-simpleportfolio .sp-simpleportfolio-filter > ul > li:first-child {'
	. 'margin-left:'.$filter_margin.'px;margin-right:0;'
	. '}'
	. $showbtns
	; 
$doc->addStyleDeclaration( $addstyle );

//random ID number to avoid conflict if there is more then one galleries on the same page
$randomid = rand(1,1000);

$i = 0;
//Sizes
$sizes = array(
	$rectangle,
	$tower,
	$square,

	$tower,
	$rectangle,
	$square,

	$square,
	$rectangle,
	$tower,

	$square,
	$tower,
	$rectangle
	);

?>

<div id="mod-sp-simpleportfolio" class="sp-simpleportfolio sp-simpleportfolio-view-items layout-<?php echo str_replace('_', '-', $layout_type); ?> <?php echo $moduleclass_sfx; ?>">
	<?php if($params->get('show_filter', 1)) { ?>
		<div class="sp-simpleportfolio-filter">
			<ul<?php echo ($params->get('filter_style') == 'simple') ? ' class="simple"' : ' class="flex"'; ?>>
				<li class="active<?php if($params->get('filter_margin') == 0) { ?> no-margin<?php } ?>" data-group="all"><a href="#">
                <?php if($params->get('show_all_txt') != '') { ?>
                   <?php echo $show_all_txt; ?>
                <?php } else { ?>
                  <?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_SHOW_ALL'); ?>
                <?php } ?>
                </a></li>
				<?php
					$filters = SpsimpleportfolioHelper::getTagList( $items );
					foreach ($filters as $filter) { ?>
						<li<?php echo ($params->get('filter_margin') == 0) ? ' class="no-margin" ' : ' style="margin-left:'.$filter_margin.'px;" '; ?>data-group="<?php echo $filter->alias; ?>"><?php echo $simple_style; ?><a href="#"><?php echo $filter->title; ?></a></li>
				<?php }	?>
			</ul>
		</div>
	<?php } 
	$video_width != '' ? $video_width : $video_width = '700';
	$video_height != '' ? $video_height : $video_height = '400';
	$column_bg != '' ? $column_background = ' style="background-color:'.$column_bg.'"' : $column_background = '';
		//Videos
		foreach ($items as $item) {

			if($item->video) {
				$video = parse_url($item->video);

				switch($video['host']) {
					case 'youtu.be':
					$video_id 	= trim($video['path'],'/');
					$video_src 	= '//www.youtube.com/embed/' . $video_id;
					break;

					case 'www.youtube.com':
					case 'youtube.com':
					parse_str($video['query'], $query);
					$video_id 	= $query['v'];
					$video_src 	= '//www.youtube.com/embed/' . $video_id;
					break;

					case 'vimeo.com':
					case 'www.vimeo.com':
					$video_id 	= trim($video['path'],'/');
					$video_src 	= "//player.vimeo.com/video/" . $video_id;
				}
				echo '<iframe class="sp-simpleportfolio-lightbox" src="'. $video_src .'" width="'. $video_width .'" height="'. $video_height .'" id="sp-simpleportfolio-video'.$item->spsimpleportfolio_item_id.'" style="border:none;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}
		}
	?>
	<div class="sp-simpleportfolio-items sp-simpleportfolio-columns-<?php echo $params->get('columns', 3); ?>">
		<?php foreach ($items as $item) { 
			$tags = SpsimpleportfolioHelper::getTags( $item->spsimpleportfolio_tag_id );
			$newtags = array();
			$filter = '';
			$groups = array();
			foreach ($tags as $tag) {
				$newtags[] 	 = $tag->title;
				$filter 	.= ' ' . $tag->alias;
				$groups[] 	.= '"' . $tag->alias . '"';
			}

			$groups = implode(',', $groups);
			?>
			<div<?php echo $column_background; ?> class="sp-simpleportfolio-item" data-groups='[<?php echo $groups; ?>]'>
				<?php $item->url = JRoute::_('index.php?option=com_spsimpleportfolio&view=item&id='.$item->spsimpleportfolio_item_id.':'.$item->alias. ModSpsimpleportfolioHelper::getItemid()); ?>
				<div class="sp-simpleportfolio-overlay-wrapper clearfix">	
					<?php if($item->video) { ?>
						<span class="sp-simpleportfolio-icon-video"></span>
					<?php } ?>
					<?php if($params->get('thumbnail_type', 'masonry') == 'masonry') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_' . $sizes[$i] . '.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } else if($params->get('thumbnail_type', 'masonry') == 'rectangular') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'. $rectangle .'.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } else { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'. $square .'.' . JFile::getExt($item->image); ?>" alt="<?php echo $item->title; ?>">
					<?php } 
					
                    // Popup Image (default = "original image", square, rectangle, tower)
                    $popup_image = $params->get('popup_image', 'default');
                    if($popup_image == 'quare') {
                        $item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'. $square .'.' . JFile::getExt($item->image);
                    } else if($popup_image == 'rectangle') {
                        $item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'. $rectangle .'.' . JFile::getExt($item->image);
                    } else if($popup_image == 'tower') {
                        $item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $item->alias . '/' . JFile::stripExt(JFile::getName($item->image)) . '_'. $tower .'.' . JFile::getExt($item->image);
                    } else {
                        $item->popup_img_url = JURI::base() . $item->image;
                    } ?>
					<div class="sp-simpleportfolio-overlay">
						<div class="sp-vertical-middle">
							<div>
								<div class="sp-simpleportfolio-btns">
									<?php if($show_view_button!=0) { ?> 
                                         <?php if( $item->video ) { ?>
											 <?php if($show_zoom_button!=0) { ?>
                                                <a class="btn-zoom gallery-<?php echo $randomid; ?>" href="#" data-featherlight="#sp-simpleportfolio-video<?php echo $item->spsimpleportfolio_item_id; ?>"><?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_WATCH'); ?></a>
                                                <a class="btn-view" href="<?php echo $item->url; ?>"><?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_VIEW'); ?></a>
                                                <?php } else { ?>
                                             <a class="btn-view-only" href="<?php echo $item->url; ?>"><i class="fa fa-link"></i></a>
                                             <?php } ?>    
                                        <?php } else { ?>
                                          <?php if($show_zoom_button!=0) { ?>
                                            <a class="btn-zoom gallery-<?php echo $randomid; ?>" href="<?php echo $item->popup_img_url; ?>" data-featherlight="image"><?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_ZOOM'); ?></a>
                                            <a class="btn-view" href="<?php echo $item->url; ?>"><?php echo JText::_('MOD_SPSIMPLEPORTFOLIO_VIEW'); ?></a>  
                                           <?php } else { ?>
                                        	<a class="btn-view-only" href="<?php echo $item->url; ?>"><i class="fa fa-link"></i></a> 
                                        <?php } ?> 
                                       <?php } ?> 
                                     <?php } else { ?>
                                      <?php if($show_zoom_button!=0) { ?>
                                        <?php if( $item->video ) { ?>
                                            <a class="btn-zoom-icon gallery-<?php echo $randomid; ?>" href="#" data-featherlight="#sp-simpleportfolio-video<?php echo $item->spsimpleportfolio_item_id; ?>"><i class="fa fa-search"></i></a>
                                        <?php } else { ?>
                                          <a class="btn-zoom-icon gallery-<?php echo $randomid; ?>" href="<?php echo $item->popup_img_url; ?>" data-featherlight="image"><i class="fa fa-search"></i></a>
                                        <?php } ?>
                                      <?php } ?>
                                    <?php } ?>
								</div>
								<?php if($layout_type!='default') { ?>
								<h3 class="sp-simpleportfolio-title">
									<a href="<?php echo $item->url; ?>">
										<?php echo $item->title; ?>
									</a>
								</h3>
									<?php if($show_tags!=0) { ?>
                                        <div class="sp-simpleportfolio-tags">
                                            [ <?php echo implode(', ', $newtags); ?> ]
                                        </div>
                                    <?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php if($layout_type=='default') { ?>
					<div class="sp-simpleportfolio-info">
						<h3 class="sp-simpleportfolio-title">
							<a href="<?php echo $item->url; ?>">
								<?php echo $item->title; ?>
							</a>
						</h3>
                        <?php if($show_tags!=0) { ?>
                            <div class="sp-simpleportfolio-tags">
                                <?php echo (count($newtags) > 1) ? '<i class="fa fa-tags"></i>' : '<i class="fa fa-tag"></i>'; ?><?php echo implode(', ', $newtags); ?>
                            </div>
                        <?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php
			$i++;
			if($i==11) {
				$i = 0;
			}
			?>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">jQuery(document).ready(function(){jQuery('.sp-simpleportfolio-btns .gallery-<?php echo $randomid; ?>').featherlightGallery({previousIcon: '<i class="arrow-previous-thin"></i>',nextIcon: '<i class="arrow-next-thin"></i>'});});</script>
