<?php
/**
 * @package     SP Simple Portfolio @ Flex
 * @copyright   Copyright (C) 2010 - 2019 JoomShaper. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die();

require_once JPATH_COMPONENT . '/helpers/helper.php';
jimport( 'joomla.filesystem.file' );
$layout_type = $this->params->get('layout_type', 'default');
$column_bg = $this->params->get('column_bg');
$show_view_button = $this->params->get('show_view_button', 1);
$show_zoom_button = $this->params->get('show_zoom_button', 1);
$show_tags = $this->params->get('show_tags', 1);
$show_filter = $this->params->get('show_filter');
$filter_divider = $this->params->get('filter_divider');
$filter_style = $this->params->get('filter_style');
$show_all_txt = $this->params->get('show_all_txt');
$filter_margin = $this->params->get('filter_margin');
$video_width = $this->params->get('video_width');
$video_height = $this->params->get('video_height');

//Load the method jquery script.
JHtml::_('jquery.framework');

//Params
$params = JComponentHelper::getParams('com_spsimpleportfolio');
$square = strtolower( $params->get('square', '600x600') );
$rectangle = strtolower( $params->get('rectangle', '600x400') );
$tower = strtolower( $params->get('tower', '600x800') );

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

if( $this->params->get('show_page_heading') && $this->params->get( 'page_heading' ) ) {
	echo "<h1 class='page-header'>" . $this->params->get( 'page_heading' ) . "</h1>";
}

$showbtns = '';

$filter_divider != '' ? $filter_divider = '<span class="simple-divider">'.$filter_divider.'</span>' : $filter_divider = '';
$filter_style == 'simple' ? $simple_style = $filter_divider : $simple_style = '';

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
<div id="sp-simpleportfolio" class="sp-simpleportfolio sp-<?php echo $randomid; ?> sp-simpleportfolio-view-items layout-<?php echo str_replace('_', '-', $layout_type); ?>">

	<?php if($this->params->get('show_filter', 1)) { ?>
		<div class="sp-simpleportfolio-filter">
			<ul<?php echo ($this->params->get('filter_style') == 'simple') ? ' class="simple"' : ' class="flex"'; ?>>
				<li class="active<?php if($this->params->get('filter_margin') == 0) { ?> no-margin<?php } ?>" data-group="all"><a href="#">
				
                <?php if($this->params->get('show_all_txt') != '') { ?>
                   <?php echo $show_all_txt; ?>
                <?php } else { ?>
                  <?php echo JText::_('COM_SPSIMPLEPORTFOLIO_SHOW_ALL'); ?>
                <?php } ?>
                </a></li>
				<?php
					$filters = SpsimpleportfolioHelper::getTagList( $this->items );
					foreach ($filters as $filter) {
						?>
							<li<?php echo ($this->params->get('filter_margin') == 0) ? ' class="no-margin" ' : ' style="margin-left:'.$filter_margin.'px;" '; ?>data-group="<?php echo $filter->alias; ?>"><?php echo $simple_style; ?><a href="#"><?php echo $filter->title; ?></a></li>
						<?php } ?>
			</ul>
		</div>
	<?php } ?>

	<?php
	$video_width != '' ? $video_width : $video_width = '700';
	$video_height != '' ? $video_height : $video_height = '400';
	$column_bg != '' ? $column_background = ' style="background-color:'.$column_bg.'"' : $column_background = '';
	
	
		//Videos
		foreach ($this->items as $key => $this->item) {

			if($this->item->video) {
				$video = parse_url($this->item->video);

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

				echo '<iframe class="sp-simpleportfolio-lightbox" src="'. $video_src .'" width="'. $video_width .'" height="'. $video_height .'" id="sp-simpleportfolio-video'.$this->item->spsimpleportfolio_item_id.'" style="border:none;" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}
		}
	?>
	<div class="sp-simpleportfolio-items sp-simpleportfolio-columns-<?php echo $this->params->get('columns', 3); ?>">
		<?php foreach ($this->items as $this->item) { 
			$tags = SpsimpleportfolioHelper::getTags( $this->item->spsimpleportfolio_tag_id );
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

			<div class="sp-simpleportfolio-item" data-groups='[<?php echo $groups; ?>]'>
				<?php $this->item->url = JRoute::_('index.php?option=com_spsimpleportfolio&view=item&id='.$this->item->spsimpleportfolio_item_id.':'.$this->item->alias . $itemId); ?>
				
				<div class="sp-simpleportfolio-overlay-wrapper clearfix">
					
					<?php if($this->item->video) { ?>
						<span class="sp-simpleportfolio-icon-video"></span>
					<?php } ?>

					<?php if($this->params->get('thumbnail_type', 'masonry') == 'masonry') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_' . $sizes[$i] . '.' . JFile::getExt($this->item->image); ?>" alt="<?php echo $this->item->title; ?>">
					<?php } else if($this->params->get('thumbnail_type', 'masonry') == 'rectangular') { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_'. $rectangle .'.' . JFile::getExt($this->item->image); ?>" alt="<?php echo $this->item->title; ?>">
					<?php } else { ?>
						<img class="sp-simpleportfolio-img" src="<?php echo JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_'. $square .'.' . JFile::getExt($this->item->image); ?>" alt="<?php echo $this->item->title; ?>">
					<?php } 
					
					// Popup Image (default = "original image", square, rectangle, tower)
                    $popup_image = $this->params->get('popup_image', 'default');
                    if($popup_image == 'quare') {
                        $this->item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_'. $square .'.' . JFile::getExt($this->item->image);
                    } else if($popup_image == 'rectangle') {
                        $this->item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_'. $rectangle .'.' . JFile::getExt($this->item->image);
                    } else if($popup_image == 'tower') {
                        $this->item->popup_img_url = JURI::base(true) . '/images/spsimpleportfolio/' . $this->item->alias . '/' . JFile::stripExt(JFile::getName($this->item->image)) . '_'. $tower .'.' . JFile::getExt($this->item->image);
                    } else {
                        $this->item->popup_img_url = JURI::base() . $this->item->image;
                    } ?>
					<div class="sp-simpleportfolio-overlay">
						<div class="sp-vertical-middle">
							<div>
								<div class="sp-simpleportfolio-btns">
									<?php if($show_view_button!=0) { ?>
                                        <?php if( $this->item->video ) { ?>
											 <?php if($show_zoom_button!=0) { ?>
                                                <a class="btn-zoom gallery-<?php echo $randomid; ?>" href="#" data-featherlight="#sp-simpleportfolio-video<?php echo $this->item->spsimpleportfolio_item_id; ?>"><?php echo JText::_('COM_SPSIMPLEPORTFOLIO_WATCH'); ?></a><a class="btn-view" href="<?php echo $this->item->url; ?>"><?php echo JText::_('COM_SPSIMPLEPORTFOLIO_VIEW'); ?></a>       
                                                <?php } else { ?>
                                             <a class="btn-view-only" href="<?php echo $this->item->url; ?>"><i class="fa fa-link"></i></a>
                                             <?php } ?>          
                                        <?php } else { ?> 
                                         <?php if($show_zoom_button!=0) { ?>
                                            <a class="btn-zoom gallery-<?php echo $randomid; ?>" href="<?php echo $this->item->popup_img_url; ?>" data-featherlight="image"><?php echo JText::_('COM_SPSIMPLEPORTFOLIO_ZOOM'); ?></a>
                                            <a class="btn-view" href="<?php echo $this->item->url; ?>"><?php echo JText::_('COM_SPSIMPLEPORTFOLIO_VIEW'); ?></a>       
                                         <?php } else { ?>
                                        	<a class="btn-view-only" href="<?php echo $this->item->url; ?>"><i class="fa fa-link"></i></a>
                                         <?php } ?> 
                                        <?php } ?> 
                                     <?php } else { ?>
                                     <?php if($show_zoom_button!=0) { ?> 
                                        <?php if( $this->item->video ) { ?>
                                            <a class="btn-zoom-icon gallery-<?php echo $randomid; ?>" href="#" data-featherlight="#sp-simpleportfolio-video<?php echo $this->item->spsimpleportfolio_item_id; ?>"><i class="fa fa-search"></i></a>
                                        <?php } else { ?>
                                            <a class="btn-zoom-icon gallery-<?php echo $randomid; ?>" href="<?php echo $this->item->popup_img_url; ?>" data-featherlight="image"><i class="fa fa-search"></i></a>
                                        <?php } ?>
                                      <?php } ?>   
                                    <?php } ?>
								</div>
								<?php if($layout_type!='default') { ?>
								<h3 class="sp-simpleportfolio-title">
									<a href="<?php echo $this->item->url; ?>">
										<?php echo $this->item->title; ?>
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
					<div<?php echo $column_background; ?> class="sp-simpleportfolio-info">
						<h3 class="sp-simpleportfolio-title">
							<a href="<?php echo $this->item->url; ?>">
								<?php echo $this->item->title; ?>
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
	<?php if ($this->pagination->get('pages.total') >1) { ?>
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">jQuery(document).ready(function(){jQuery('.gallery-<?php echo $randomid; ?>').featherlightGallery({
previousIcon: '<i class="arrow-previous-thin"></i>',nextIcon: '<i class="arrow-next-thin"></i>'});});</script>