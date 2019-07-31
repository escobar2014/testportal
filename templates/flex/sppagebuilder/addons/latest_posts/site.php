<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined('_JEXEC') or die;

JLoader::register('JHtmlString', JPATH_LIBRARIES.'/joomla/html/html/string.php');

AddonParser::addAddon('sp_latest_posts','sp_latest_posts_addon');

function get_categories($parent=1) {
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);

	$query
	->select('*')
	->from($db->quoteName('#__categories'))
	->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
	->where($db->quoteName('published') . ' = ' . $db->quote(1))
	->where($db->quoteName('parent_id') . ' = ' . $db->quote($parent))
	->order($db->quoteName('created_time') . ' DESC');

	$db->setQuery($query);

	$cats = $db->loadObjectList();

	$categories = array($parent);

	foreach ($cats as $key => $cat) {
		$categories[] = $cat->id;
	}

	return $categories;
}

function sp_latest_posts_addon($atts){

	extract(spAddonAtts(array(
		"title" 				    => '',
		"heading_selector" 		=> 'h3',
		"title_fontsize" 		=> '',
		"title_text_color" 		=> '',
		"title_margin_top" 		=> '',
		"title_margin_bottom" 	=> '',
		"show_image"			=> '',
		"show_date"			    => '',
		"date_format"		    => '',
		"show_category"			=> '',
		"show_intro_text"		=> '',
		"show_readmore"	    	=> '',
		"readmore_button" 	    => '',
		"readmore_button_position" => '',
		"button_type" 	        => '',
		"button_appearance"     => '',
		"button_size" 	    	=> '',
		"button_shape" 	    	=> '',
		"button_block" 	    	=> '',
		"show_author"		    => '',
		"item_limit"			=> '',
		"intro_text_limit"		=> '100',
		"column_no"				=> '3',
		"image_alignment" 		=> '',
		"category"				=> '',
		"enable_masonry"		=> '0',
		"style" 		      		=> '',
		"class" 					=> '',
		), $atts));
	
	$doc = JFactory::getDocument();
	$app = JFactory::getApplication();
	
	// Database Query
	require_once JPATH_SITE . '/components/com_content/helpers/route.php';

	// Access filter
	$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
	$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

	$catid = '';
	
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$nullDate = $db->quote($db->getNullDate());
	$nowDate  = $db->quote(JFactory::getDate()->toSql());

	$query
	->select('a.*')
	->from($db->quoteName('#__content', 'a'))
	->select($db->quoteName('b.alias', 'category_alias'))
	->select($db->quoteName('b.title', 'category'))
	->join('LEFT', $db->quoteName('#__categories', 'b') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')')
	->where($db->quoteName('b.extension') . ' = ' . $db->quote('com_content'))
	->where($db->quoteName('a.state') . ' = ' . $db->quote(1))
	->where($db->quoteName('a.catid')." IN (" . implode( ',', get_categories($category) ) . ")")
	->where($db->quoteName('a.access')." IN (" . implode( ',', $authorised ) . ")")	
	->order($db->quoteName('a.created') . ' DESC')
	->setLimit($item_limit);
	
	$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
	$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
	
	$db->setQuery($query);

	$items = $db->loadObjectList();
	
	// End Database Query
	$match_height = '';
	$masonry = '';
	if ($enable_masonry == '1' && $column_no > '1') {
		$masonry = ' masonry_item';
	}

	$style == 'flex' ? $flex_style = ' flex' : $flex_style = '';
	$style == 'blog' ? $blog_style = ' blog' : $blog_style = '';
	$readmore_button != '' ? $button_text = $readmore_button : $button_text = JText::_('READ_MORE_TITLE');
	$readmore_button_position == 'left' ? $readmore_button_position = ' pull-left' : $readmore_button_position = ' pull-right';
	
	$date_format != '' ? $date_format : 'DATE_FORMAT_LC1';
	
	// Readmore button
	$button_classes = (isset($button_size) && $button_size) ? ' sppb-btn-' . $button_size : '';
	$button_classes .= (isset($button_type) && $button_type) ? ' sppb-btn-' . $button_type : ' sppb-btn-default';
	$button_classes .= (isset($button_shape) && $button_shape) ? ' sppb-btn-' . $button_shape: ' sppb-btn-rounded';
	$button_classes .= (isset($button_appearance) && $button_appearance) ? ' sppb-btn-' . $button_appearance : '';
	$button_classes .= (isset($button_block) && $button_block) ? ' ' . $button_block : '';
	
	//random ID number to avoid conflict if there is more then one Slick carousel on the same page
	$randomid = rand(1,1000);

	$output  = '<div class="sppb-addon-latest-posts'. $flex_style . $blog_style .' row ' . $class . '">';

	if ($title) {
		$output .= '<div class="sppb-section-title">';
			$output .= '<'.$heading_selector.' class="sppb-addon-title" style="' . $title_style . '"> ' . $title . '</' . $heading_selector . '>';
		$output .= '</div>'; // END :: title
	}

	$output .= '<div class="sppb-addon-content">';
	$output .= '<div id="lp-'.$randomid.'" class="latest-posts clearfix">';

	foreach(array_chunk($items, $column_no) as $items) {
		$output .= '<div>';
		foreach ($items as $item) {

			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;
			$item->user    = JFactory::getUser($item->created_by)->name;


			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
				$item->catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug, $item->catid, $item->language));
			} else {
				$item->link = JRoute::_('index.php?option=com_users&view=login');
				$item->catlink = JRoute::_('index.php?option=com_users&view=login');
			}
			
			$tplParams 		= JFactory::getApplication()->getTemplate(true)->params;
			//$params  		= $item->params;
			$attribs 		= json_decode($item->attribs);
			$images 			= json_decode($item->images);
			$imgsize 		= $tplParams->get('blog_list_image', 'default');
			$intro_image 	= '';

			if(isset($attribs->spfeatured_image) && $attribs->spfeatured_image != '') {
			
				if($imgsize == 'default') {
					$intro_image = $attribs->spfeatured_image;
				} else {
					$intro_image = $attribs->spfeatured_image;
					$basename = basename($intro_image);
					$list_image = JPATH_ROOT . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
					if(file_exists($list_image)) {
						$intro_image = JURI::root(true) . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
					}
				}
			} elseif(isset($images->image_intro) && !empty($images->image_intro)) {
				$intro_image = $images->image_intro;
			}
		
	
		if($column_no == '1') {
			if ($show_image) {
			$image_alignment == 'left' ? $img_column = 'col-sm-4 column-1 pull-left match-height' : $img_column = 'col-sm-4 column-1 pull-right match-height';
			}
			if ($show_image) {
				$image_alignment == 'right' ? $content_column = 'col-sm-8 column-1 pull-left match-height' : $content_column = 'col-sm-8 column-1 pull-right match-height';
			} else {		
				$image_alignment == 'right' ? $content_column = 'col-sm-12 column-1' : $content_column = 'col-sm-12 column-1';
			}
			$h2style = ' style="font-size:180%;line-height:1.4;"';
			$img_wrapper_margin = ' style="margin:0;"';
			
			if ($image_alignment == 'left') {
				$inner_padding = ' style="padding:0 0 0 30px;"';
			} else {
				$inner_padding = ' style="padding:0 30px 0 0;"';
			}
		} else {
			$h2style = '';
			$img_wrapper_margin = '';
		}
		
		if ($enable_masonry == '0') {
			// match-height
			$column_no > '1' ? $match_height = ' match-height' : $match_height = ' match-height';
		} else {
			$column_no == '1' ? $match_height = ' match-height' : $match_height = '';
		}
		
		// Flex Style
		if($style == 'flex') {
			$output .= '<div class="latest-post'.$masonry.' col-sm-' . round(12/$column_no) . ' columns-'.$column_no.'">';
			$output .= '<div class="latest-post-item'. $match_height .'">';
		
			if($column_no == '1') {
				$output .= '<div class="row-fluid">';
			}
			
			if(!empty($intro_image) || (isset($images->image_intro) && !empty($images->image_intro))) {
				if ($show_image) {
					
					if($column_no == '1') {
						$output .= '<div style="padding:0" class="'.$img_column.'">';
					}
					$output .= '<div class="img-wrapper">';
					$output .= '<a href="' . $item->link . '"><img class="post-img" src="' . $intro_image . '" alt="' . $item->title . '" /><div class="caption-content">' . $item->title;
					if ($show_category) {
						$output .= '<em class="caption-category"><span class="posted-in">'. JText::_('COM_SPPAGEBUILDER_ADDON_POSTED_IN') .'</span>'. $item->category . '</em>';
					}
					$output .= '</div></a>';
					$output .= '</div>';
					
					if($column_no == '1') {
						$output .= '</div>';
					}
				}
			}
				if($column_no == '1') {
					$output .= '<div'.$inner_padding.' class="'.$content_column.'">';
				}
				$output .= '<div class="latest-post-inner">';
				

				if (($show_date || $show_intro_text || $show_author) != 1)  {
				   $output .= '<h2 style="margin:0" class="entry-title"><a href="' . $item->link . '">' . $item->title . '</a></h2>';
				} else {
				   $output .= '<h2'.$h2style.' class="entry-title"><a href="' . $item->link . '">' . $item->title . '</a></h2>';
				}
				if ($show_date) {
					$output .= '<div class="entry-meta"><span class="entry-date">' . JHtml::_('date', $item->created, $date_format) . '</span></div>';
					
				}
				if ($show_intro_text) {
					if ($intro_text_limit != '') {
						$output .= '<p class="intro-text" >' . JHtml::_('string.truncate', strip_tags($item->introtext), $intro_text_limit) . '</p>';
					} else {
						$output .= '<p class="intro-text" >' . JHtml::_('string.truncate', $item->introtext, $intro_text_limit) . '</p>';
					}
				}
				
				$show_author || $show_category ? $output .= '<hr />' : $output .= '';
				if ($show_author) {	
					$output .= '<span class="post-author"><span class="entry-author">' . JText::_('COM_SPPAGEBUILDER_ADDON_POSTED_BY'). '</span> ' . $item->user . '</span>';
				}
				if ($show_category) {	
				    $show_author ? $posted_in_category = ' cat-inline' : $posted_in_category = '';
					$output .= '<span class="category'.$posted_in_category.'"><span class="posted-in">'. JText::_('COM_SPPAGEBUILDER_ADDON_CATEGORY') .'</span><a href="' . $item->catlink . '">'. $item->category . '</a></span>';
				}
				
				if($column_no == '1') {
					$output .= '</div>';
					$output .= '</div>';
				}
			//Readmore button
			if ($show_readmore == '1') {	
			    $output .= '<div class="clearfix" style="margin-top:25px;"><a class="sppb-btn' . $button_classes . ''.$readmore_button_position.'" href="' . $item->link . '">'. $button_text. '</a></div>';
				
			}
				
			$output .= '</div>';
			if($column_no == '1') {
				$output .= '<div class="post-divider"></div>';
			}
			$output .= '</div>';
		
		// Default & Blog styles	
		} else {
				
			$output .= '<div class="latest-post'.$masonry.' col-sm-' . round(12/$column_no) . ' columns-'.$column_no.'">';
			$output .= '<div class="latest-post-inner'. $match_height .'">';
				
			if($column_no == '1') {
				$output .= '<div class="row-fluid">';
			}
				if ($show_image) {
					if($column_no == '1') {
						$output .= '<div class="'.$img_column.'">';
					}
					$output .= '<div'.$img_wrapper_margin.' class="img-wrapper">';
					$output .= '<a href="' . $item->link . '"><img class="post-img" src="' . $intro_image . '" alt="' . $item->title . '" /></a>';
					$output .= '</div>';
					
					if($column_no == '1') {
						$output .= '</div>';
					}
				}
				
			if($column_no == '1') {
				$output .= '<div class="'.$content_column.'">';
			}
				if ($show_date) {
					$output .= '<div class="entry-meta"><span class="entry-date"> ' . JHtml::_('date', $item->created, $date_format) . '</span></div>';
				}
				$output .= '<h2'.$h2style.' class="entry-title"><a href="' . $item->link . '">' . $item->title . '</a></h2>';
				if ($show_intro_text) {
					if ($intro_text_limit != '') {
						$output .= '<p class="intro-text" >' . JHtml::_('string.truncate', strip_tags($item->introtext), $intro_text_limit) . '</p>';
					} else {
						$output .= '<p class="intro-text" >' . JHtml::_('string.truncate', $item->introtext, $intro_text_limit) . '</p>';
					}
				}
				$show_author || $show_category ? $output .= '<hr />' : '';
				if ($show_author) {	
					$output .= '<span class="post-author"><span class="entry-author">' . JText::_('COM_SPPAGEBUILDER_ADDON_POSTED_BY'). ' ' . $item->user . '</span></span>';
				}
				if ($show_category) {	
				$show_author ? $posted_in_category = ' cat-inline' : $posted_in_category = '';
					$output .= '<span class="category'.$posted_in_category.'"><span class="posted-in">'. JText::_('COM_SPPAGEBUILDER_ADDON_CATEGORY') .'</span><a href="' . $item->catlink . '">'. $item->category . '</a></span>';
				}
				if($column_no == '1') {
					$output .= '</div>';
					$output .= '</div>';
				}
				
				//Readmore button
				if ($show_readmore == '1') {	
					$output .= '<div class="clearfix" style="margin-top:25px;"><a class="sppb-btn' . $button_classes . ''.$readmore_button_position.'" href="' . $item->link . '">'. $button_text. '</a></div>';
				}
			
				$output .= '</div>';	
			}
			$output .= '</div>';
		}
		$output .= '</div>';
	}

	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	
	
	$column_no == '1' ? $column_no_1 = '.column-1 {margin:10px auto;padding:0!important;}' : $column_no_1 = '';
	
	// Add styles @media rulepost-img
	if($style == 'flex') {
		$custom_style = ''
				. '@media screen and (max-width: 768px) {'
				. $column_no_1
				. '.img-wrapper a {font-size:150%;line-height:1.5;}'
				. '}';
		$doc->addStyleDeclaration($custom_style);
	}
	
	if ($column_no>='3') {
	$custom_style_3 = ''
			. '@media screen and (min-width: 992px) and (max-width: 1199px){'
			. '.columns-'.$column_no.'{width:33.3333%;}'
			. '}'
			. '@media screen and (min-width: 768px) and (max-width: 991px){'
			. '.columns-'.$column_no.'{width:50%}'
			. '}';
	$doc->addStyleDeclaration($custom_style_3);
	}
	if($column_no=='5') {
	$custom_style_5 = ''
			. '.columns-'.$column_no.' {width:20%}'
			. '@media screen and (min-width: 992px) and (max-width: 1199px){'
			. '.columns-'.$column_no.'{width:33.3333%;}'
			. '}'
			. '@media screen and (min-width: 768px) and (max-width: 991px){'
			. '.columns-'.$column_no.'{width:50%}'
			. '}'
			. '@media screen and (max-width: 767px){'
			. '.columns-'.$column_no.'{width:100%}'
			. '}';
	$doc->addStyleDeclaration($custom_style_5);
	
	}
	
	if ($enable_masonry == '1' && $column_no > '1') {
		// Add masonry js
		$js ='jQuery(function($){
		$("#lp-'.$randomid.'").imagesLoaded(function(){
			$("#lp-'.$randomid.'").masonry({
			  itemSelector:\'.masonry_item\'
			});
		}); 
		});';
		$js = preg_replace(array('/([\s])\1+/', '/[\n\t]+/m'), '', $js); // Remove whitespace
		$doc->addScriptdeclaration($js);
	}
	
	return $output;
	
}
