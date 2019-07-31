<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('restricted access');

SpAddonsConfig::addonConfig(
	array(
		'type'=>'content',
		'addon_name'=>'sp_latest_posts',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_DESC'),
		'category'=>'Flex',
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),
	
				'title'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
					'std'=>'',
					),
	
				'heading_selector'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
					'values'=>array(
						'h1'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H1'),
						'h2'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H2'),
						'h3'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H3'),
						'h4'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H4'),
						'h5'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H5'),
						'h6'=>JText::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_H6'),
						),
					'std'=>'h3',
				),
				'show_image'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_IMG'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_IMG_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
					),
				'show_date'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_DATE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_DATE_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
					),	
				'date_format'=>array(
					'type'=>'select',
					'title'=>JText::_('FLEX_GLOBAL_DATE_FORMAT'),
					'desc'=>JText::_('FLEX_GLOBAL_DATE_FORMAT_DESC'),
					'values'=>array(
						'DATE_FORMAT_LC1'=>JText::_('DATE_FORMAT_LC1: Tuesday, 07 October 2019 (l, d F Y)'),
						'DATE_FORMAT_LC2'=>JText::_('DATE_FORMAT_LC2: Tuesday, 07 October 2009 14:15 (l, d F Y H:i)'),
						'DATE_FORMAT_LC3'=>JText::_('DATE_FORMAT_LC3: 07 October 2019 (d F Y)'),
						'DATE_FORMAT_LC4'=>JText::_('DATE_FORMAT_LC4: 2019-10-07 (Y-m-d)'),
						'DATE_FORMAT_JS1'=>JText::_('DATE_FORMAT_JS1: 19-10-07 (y-m-d)'),
					),
					'std'=>'DATE_FORMAT_LC1',
					'depends'=> array(
						array('show_date', '=', '1'),
					)
				),
		
				'show_intro_text'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_INTROTEXT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_INTROTEXT_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
				),
				'intro_text_limit'=>array(
					'type'=>'number', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_INTROTEXT_LIMIT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_INTROTEXT_LIMIT_DESC'),
					'std'=>'100',
					'depends'=>array('show_intro_text'=>'1')
					),
				'show_author'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_AUTHOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_AUTHOR_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
				),
					
				'show_readmore'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_ADDON_READMORE'),
					'desc'=>JText::_('FLEX_ADDON_READMORE_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
					),
								
				//Readmore Button
				'readmore_button'=>array(
					'type'=>'text',
					'title'=>JText::_('FLEX_ADDON_READMORE_BUTTON_TEXT'),
					'desc'=>JText::_('FLEX_ADDON_READMORE_BUTTON_TEXT_DESC'),
					'std'=>'Read More',
					'depends'=>array('show_readmore'=>'1')
				),
				'readmore_button_position'=>array(
					'type'=>'select',
					'title'=>JText::_('FLEX_ADDON_READMORE_BUTTON_POSITION'),
					'values'=>array(
						'left'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
						'right'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
					),
					'depends'=>array('show_readmore'=>'1')
				),
		
				'button_type'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE_DESC'),
					'values'=>array(
						'default'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_DEFAULT'),
						'flex'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_FLEX'),
						'dark'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_DARK'),
						'light'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LIGHT'),
						'primary'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_PRIMARY'),
						'success'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_SUCCESS'),
						'info'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INFO'),
						'warning'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_WARNING'),
						'danger'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_DANGER'),
						'link'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
					),
					'std'=>'default',
					'depends'=> array(
						array('show_readmore', '=', '1'),
						array('button_text', '!=', ''),
					)
				),
	
				'button_appearance'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_DESC'),
					'values'=>array(
						''=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_FLAT'),
						'outline'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_OUTLINE'),
						'3d'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_3D'),
					),
					'std'=>'flat',
					'depends'=> array(
						array('show_readmore', '=', '1'),
						array('button_text', '!=', ''),
					)
				),
	
				'button_size'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DESC'),
					'values'=>array(
						''=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DEFAULT'),
						'lg'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_LARGE'),
						'xlg'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_XLARGE'),
						'sm'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_SMALL'),
						'xs'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_EXTRA_SAMLL'),
					),
					'depends'=> array(
						array('show_readmore', '=', '1'),
						array('button_text', '!=', ''),
					)
				),
	
				'button_shape'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_DESC'),
					'values'=>array(
						'rounded'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUNDED'),
						'square'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_SQUARE'),
						'round'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUND'),
					),
					'depends'=> array(
						array('show_readmore', '=', '1'),
						array('button_text', '!=', ''),
					)
				),
	
				'button_block'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK_DESC'),
					'values'=>array(
						''=>JText::_('JNO'),
						'sppb-btn-block'=>JText::_('JYES'),
					),
					'depends'=> array(
						array('show_readmore', '=', '1'),
						array('button_text', '!=', ''),
					)
				),
		
				'show_category'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_CATEGORY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_CATEGORY_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
					),
				'item_limit'=>array(
					'type'=>'number', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_LIMIT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_LIMIT_DESC'),
					'std'=>'6'
					),	
				'column_no'=>array(
					'type'=>'select', 
					'title'=>JText::_('FLEX_LATEST_POSTS_COLUMN_NO'),
					'desc'=>JText::_('FLEX_LATEST_POSTS_COLUMN_NO_DESC'),
					'values'=>array(
						'1'=>JText::_('1'),
						'2'=>JText::_('2'),
						'3'=>JText::_('3'),
						'4'=>JText::_('4'),
						'5'=>JText::_('5'),
						'6'=>JText::_('6'),
						),
					'std'=>'3',
				),
				'image_alignment'=>array(
					'type'=>'select',
					'title'=>JText::_('FLEX_LATEST_POSTS_IMAGE_ALIGNMENT'),
					'desc'=>JText::_('FLEX_LATEST_POSTS_IMAGE_ALIGNMENT_DESC'),
					'values'=>array(
						'left'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_LEFT'),
						'right'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_RIGHT'),
						),
					'std'=>'left',
					'depends'=>array('column_no'=>'1')
				),
				'category'=>array(
					'type'=>'category',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_SELECT_CATEGORY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_LATEST_POSTS_SELECT_CATEGORY_DESC'),
					'std'=>''
					),
					
				'enable_masonry'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_ADDON_ENABLE_MASONRY'),
					'desc'=>JText::_('FLEX_ADDON_ENABLE_MASONRY_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
					'depends'=>array(array('column_no', '!=', '1')),
				),
			
				'style'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_TYPE'),
					'desc'=>JText::_(''),
					'values'=>array(
						'default'=>JText::_('Default'),
						'blog'=>JText::_('Blog'),
						'flex'=>JText::_('Flex'),
						),
					'std'=>'Default',
				),
				'class'=>array(
						'type'=>'text',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
						'std'=>''
				),
			),
		),
	)
);