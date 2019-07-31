<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('restricted access');

//Include Pixeden Icons
require_once dirname(dirname( __DIR__ )) . '/fields/pixeden-icons.php';
SpAddonsConfig::addonConfig(
	array(
		'type'=>'repeatable',
		'addon_name'=>'sp_icons',
		'title'=>JText::_('FLEX_ADDON_ICONS'),
		'desc'=>JText::_('FLEX_ADDON_ICONS_DESC'),
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
				'std'=>  ''
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
				'depends'=>array(array('title', '!=', '')),
			),

			'title_fontsize'=>array(
				'type'=>'number', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_SIZE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_SIZE_DESC'),
				'std'=>'',
				'depends'=>array(array('title', '!=', '')),
				),

			'title_fontweight'=>array(
				'type'=>'text', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_WEIGHT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_WEIGHT_DESC'),
				'std'=>'',
				'depends'=>array(array('title', '!=', '')),
				),

			'title_text_color'=>array(
				'type'=>'color',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_TEXT_COLOR'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_TEXT_COLOR_DESC'),
				'depends'=>array(array('title', '!=', '')),
				),	

			'title_margin_top'=>array(
				'type'=>'number',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_TOP'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_TOP_DESC'),
				'placeholder'=>'10',
				'depends'=>array(array('title', '!=', '')),
				),

			'title_margin_bottom'=>array(
				'type'=>'number',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_BOTTOM'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_MARGIN_BOTTOM_DESC'),
				'placeholder'=>'10',
				'depends'=>array(array('title', '!=', '')),
				),	
				
			'margin_gap'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_ICONS_GAP'),
				'desc'=>JText::_('FLEX_ADDON_ICONS_GAP_DESC'),
				'placeholder'=>'10',
				'std'=>'',
				),
			'alignment'=>array(
				'type'=>'select',
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_CONTENT_ALIGNMENT'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_CONTENT_ALIGNMENT_DESC'),
				'values'=>array(
					'sppb-text-left'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_LEFT'),
					'sppb-text-center'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_CENTER'),
					'sppb-text-right'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_RIGHT'),
					),
				'std'=>'sppb-text-left',
				),
			'class'=>array(
				'type'=>'text', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
				'std'=> ''
				),
			'title_tooltip'=>array(
				'type'=>'select', 
				'title'=>JText::_('FLEX_ADDON_TITLE_TOOLTIP'),
				'desc'=>JText::_('FLEX_ADDON_TITLE_TOOLTIP_DESC'),
				'values'=> array(
					'0'=>JText::_('FLEX_NO'),
					'1'=>JText::_('FLEX_YES')
				),
				'std'=>'1'
				),
										
			// Repeatable Items
			'sp_icons_item'=>array(
				'title'=> 'Icons', 
				'attr'=>array(
				
					'title'=>array(
						'type'=>'text', 
						'title'=>JText::_('FLEX_ADDON_ICONS_TITLE'),
						'desc'=>JText::_('FLEX_ADDON_ICONS_TITLE_DESC'),
						'std'=>JText::_('FLEX_ADDON_ICONS_TITLE_STD')
						),

					'peicon_name'=>array( // Pixeden Icons
							'type'=>'select', 
							'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_PE_ICON_NAME'),
							'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_PE_ICON_NAME_DESC'),
							'values'=> $peicon_list
						),		
		
					'icon_name'=>array(
						'type'=>'icon', 
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_FONTAWESOME_ICON_NAME'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_FONTAWESOME_ICON_NAME_DESC'),
						'std'=> ''
						),
					'size'=>array(
						'type'=>'number',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_ICON_SIZE'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_ICON_SIZE_DESC'),
						'placeholder'=>24,
						'std'=>24,
						),
					'font_weight'=>array(
						'type'=>'text',
						'title'=>JText::_('FLEX_ADDON_GLOBAL_ICON_FONT_WEIGHT'),
						'desc'=>JText::_('FLEX_ADDON_GLOBAL_ICON_FONT_WEIGHT_DESC'),
						'placeholder'=>'300',
						'std'=>'',
						),
					'color'=>array(
						'type'=>'color',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_COLOR'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_COLOR_DESC'),
						),
		
					'background'=>array(
						'type'=>'color',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_BACKGROUND'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_BACKGROUND_DESC'),
						),
		
					'border_color'=>array(
						'type'=>'color',
						'title'=>JText::_('FLEX_GLOBAL_BORDER_COLOR'),
						'desc'=>JText::_('FLEX_GLOBAL_BORDER_COLOR_DESC'),
						),
		
					'border_width'=>array(
						'type'=>'number',
						'title'=>JText::_('FLEX_GLOBAL_BORDER_WIDTH_SIZE'),
						'desc'=>JText::_('FLEX_GLOBAL_BORDER_WIDTH_SIZE_DESC'),
						'placeholder'=>'2',
						),
		
					'border_radius'=>array(
						'type'=>'number',
						'title'=>JText::_('FLEX_GLOBAL_BORDER_RADIUS'),
						'desc'=>JText::_('FLEX_GLOBAL_BORDER_RADIUS_DESC'),
						'placeholder'=>'5',
						),
		
					'icon_margin'=>array(
						'type'=>'number',
						'title'=>JText::_('FLEX_ADDON_GLOBAL_MARGIN'),
						'desc'=>JText::_('FLEX_ADDON_GLOBAL_MARGIN_DESC'),
						'std'=>'',
						),			
		
					'padding'=>array(
						'type'=>'number',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_PADDING'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_PADDING_DESC'),
						'placeholder'=>'10',
						),

					'url'=>array(
						'type'=>'text',
						'title'=>JText::_('FLEX_ADDON_ICONS_URL'),
						'desc'=>JText::_('FLEX_ADDON_ICONS_URL_DESC'),
						'placeholder'=>'http://',
						'std'=>'',
						),
					'url_target'=>array(
						'type'=>'select', 
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_DESC'),
						'values'=>array(
							''=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_SAME_WINDOW'),
							'_blank'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_NEW_WINDOW'),
							),
						),
					),
				),
			),
		),
	)
);
