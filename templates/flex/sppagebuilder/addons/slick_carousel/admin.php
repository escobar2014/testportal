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
SpAddonsConfig::addonConfig(
	array(
		'type'=>'repeatable',
		'addon_name'=>'sp_slick_carousel',
		'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL'),
		'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_DESC'),
		'category'=>'Flex',
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),

			'separator1'=>array(
				'type'=>'separator', 
				'title'=>JText::_('FLEX_ADDON_SETTINGS')
				),
			'infiniteloop'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_LOOP'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_LOOP_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			    ),
			'lazyloading'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_LAZYLOAD'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_LAZYLOAD_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			    ),
			'slidestoshow'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SHOW'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SHOW_DESC'),
				'placeholder'=>'1',
				'std'=>'1'
				),
			'slidestoscroll'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SCROLL'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SCROLL_DESC'),
				'placeholder'=>'1',
				'std'=>'1'
				),
			'spacing'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SPACE_BETWEEN_IMG'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SPACE_BETWEEN_IMG_DESC'),
				'std'=>0
				),		
			'fade_effect'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_FADE_EFFECT'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_FADE_EFFECT_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>0,
			 ),
			'autoplay'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOPLAY'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOPLAY_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			),
			'autoplay_interval'=>array(
				'type'=>'text', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_AUTOPLAY_INTERVAL'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_AUTOPLAY_INTERVAL_DESC'),
				'placeholder'=>'5000',
				'std'=>5000,
				'depends'=>array(array('autoplay', '=', '1')),
				),
			'speed'=>array(
				'type'=>'text', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOPLAY_SPEED'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOPLAY_SPEED_DESC'),
				'placeholder'=>'500',
				'std'=>500
				),
			'arrows'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			    ),
			'arrows_size'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_SIZE'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_SIZE_DESC'),
				'placeholder'=>'44',
				'std'=>44,
				'depends'=>array(array('arrows', '=', '1')),
				),
			'arrows_color'=>array(
				'type'=>'color',
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_COLOR'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_COLOR_DESC'),
				'depends'=>array(array('arrows', '=', '1')),
				),
			'arrows_background_color'=>array(
				'type'=>'color',
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_BACKGROUND_COLOR'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_BACKGROUND_COLOR_DESC'),
				'depends'=>array(array('arrows', '=', '1')),
				),
			'arrows_class'=>array(
				'type'=>'text', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_CLASS'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ARROWS_CLASS_DESC'),
				'std'=>'',
				'depends'=>array(array('arrows', '=', '1')),
				),		
			'counter'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_COUNTER'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_COUNTER_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>0,
				'depends'=>array(array('arrows', '=', '1')),
				),
			'counter_color'=>array(
				'type'=>'color',
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_COUNTER_COLOR'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_COUNTER_COLOR_DESC'),
				'depends'=>array(
							array('arrows', '=', '1'),
							array('counter', '=', '1')
						),
				),
			'dots'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_DOTS'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_DOTS_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			    ),
			'autoheight'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOHEIGHT'),
				'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_AUTOHEIGHT_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>1,
			    ),
			'separatorRTL'=>array(
				'type'=>'separator', 
				'title'=>JText::_('FLEX_ADDON_RTL_DIR_SEPARATOR')
				),
			'rtl_support'=>array(
				'type'=>'checkbox', 
				'title'=>JText::_('FLEX_ADDON_RTL_DIR'),
				'desc'=>JText::_('FLEX_ADDON_RTL_DIR_DESC'),
				'values'=>array(
					1=>JText::_('JYES'),
					0=>JText::_('JNO'),
				),
				'std'=>0,
			 ),
					
			'separator2'=>array(
				'type'=>'separator', 
				'title'=>JText::_('FLEX_ADDON_BREAKPOINTS')
				),	
			'breakpoint1'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_BREAKPOINTS_MEDIUM_DEVICES'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_MEDIUM_DEVICES_DESC'),
				'placeholder'=>'992',
				'std'=>992
				),
			'slidestoshow_break1'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SHOW'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_MEDIUM_DEVICES_SLIDES_TO_SHOW'),
				'placeholder'=>'3',
				'std'=>3
				),
			'breakpoint2'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_BREAKPOINTS_SMALL_DEVICES'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_SMALL_DEVICES_DESC'),
				'placeholder'=>'768',
				'std'=>768
				),
			'slidestoshow_break2'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SHOW'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_SMALL_DEVICES_SLIDES_TO_SHOW'),
				'placeholder'=>'2',
				'std'=>2
				),
			'breakpoint3'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_BREAKPOINTS_EXTRASMALL_DEVICES'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_EXTRASMALL_DEVICES_DESC'),
				'placeholder'=>'480',
				'std'=>480
				),
			'slidestoshow_break3'=>array(
				'type'=>'number', 
				'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_SLIDES_TO_SHOW'),
				'desc'=>JText::_('FLEX_ADDON_BREAKPOINTS_EXTRASMALL_DEVICES_SLIDES_TO_SHOW'),
				'placeholder'=>'1',
				'std'=>1
				),
				
			'separator3'=>array(
				'type'=>'separator', 
				'title'=>JText::_('FLEX_ADDON_TITLE')
				),	
			'title'=>array(
				'type'=>'text', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
				'std'=> ''
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

			'class'=>array(
				'type'=>'text', 
				'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
				'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
				'std'=>''
				),
			'separator4'=>array(
				'type'=>'separator', 
				'title'=>JText::_('FLEX_ADDON_IMAGES')
				),

			// Repeatable Items
			'sp_slick_carousel_item'=>array(
				'title'=> 'Slides', 
				'attr'=>array(
					'title'=>array(
						'type'=>'text', 
						'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ITEM_TITLE'),
						'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ITEM_TITLE_DESC'),
						'std'=>'Item 1'
						),
					'thumb'=>array(
						'type'=>'media', 
						'title'=>JText::_('FLEX_ADDON_IMAGE'),
						'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_IMAGE'),
						),	
					'thumb_url'=>array(
						'type'=>'text', 
						'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_THUMB_URL'),
						'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_THUMB_URL_DESC'),
						'std'=>''
					),
					'url_target'=>array(
						'type'=>'select',
						'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET'),
						'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_DESC'),
						'values'=>array(
							''=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_SAME_WINDOW'),
							'_blank'=>JText::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_TARGET_NEW_WINDOW'),
						),
						'depends'=>array(
							array('thumb', '!=', ''),
							array('thumb_url', '!=', '')
						),
					),
					'description'=>array(
						'type'=>'editor', 
						'title'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ITEM_DESCRIPTION'),
						'desc'=>JText::_('FLEX_ADDON_SLICK_CAROUSEL_ITEM_DESCRIPTION_DESC'),
						'std'=> ''
						),
					),
				),
			),
		),
	)
);
