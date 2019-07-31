<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined('_JEXEC') or die;

SpAddonsConfig::addonConfig(
	array(
		'type'=>'content',
		'addon_name'=>'sp_plyr',
		'title'=>JText::_('FLEX_ADDON_PLYR'),
		'desc'=>JText::_('FLEX_ADDON_PLYR_DESC'),
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
					'std'=>'h4',
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
				'separator1'=>array(
					'type'=>'separator', 
					'title'=>JText::_('FLEX_ADDON_PLYR_SEPARATOR_MEDIA')
					),	
				'media'=>array(
					'type'=>'select', 
					'title'=>JText::_('FLEX_ADDON_PLYR_MEDIA'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_MEDIA_DESC'),
					'values'=> array(	
						'1'=>JText::_('FLEX_VIDEO'),
						'2'=>JText::_('FLEX_AUDIO'),
						'3'=>JText::_('FLEX_YOUTUBE_VIMEO')
					),
					'std'=>'1'
					),
					
				'video'=>array(
					'type'=>'text', 
					'title'=>JText::_('FLEX_ADDON_PLYR_VIDEO_URL'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_VIDEO_URL_DESC'),
					'placeholder'=>JText::_('FLEX_ADDON_PLYR_VIDEO_URL_PLACEHOLDER'),
					'std'=>'',
					'depends'=>array('media'=>'1')
					),
				'video_poster'=>array(
					'type'=>'text', 
					'title'=>JText::_('FLEX_ADDON_PLYR_VIDEO_POSTER'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_VIDEO_POSTER_DESC'),
					'placeholder'=>JText::_('FLEX_ADDON_PLYR_VIDEO_POSTER_PLACEHOLDER'),
					'std'=>'',
					'depends'=>array('media'=>'1')
					),
					
				'captions'=>array(
					'type'=>'text', 
					'title'=>JText::_('FLEX_ADDON_PLYR_CAPTIONS'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_CAPTIONS_DESC'),
					'placeholder'=>JText::_('FLEX_ADDON_PLYR_CAPTIONS_PLACEHOLDER'),
					'std'=>'',
					'depends'=>array('media'=>'1')
					),
				'captions_lang_label'=>array(
					'type'=>'text', 
					'title'=>JText::_('Language Label for Captions'),
					'desc'=>JText::_('Specifies the title of the text track. Here you can insert your desired language for captions. For example: “English” or “English captions”.'),
					'placeholder'=>JText::_('English'),
					'std'=>'',
					'depends'=>array('media'=>'1')
				),
					
				'captions_srclang'=>array(
					'type'=>'text', 
					'title'=>JText::_('Language for captions'),
					'desc'=>JText::_('Specifies the language of the track text data. For example “en” for English language.'),
					'placeholder'=>JText::_('en'),
					'std'=>'',
					'depends'=>array('media'=>'1')
				),
				
				'audio'=>array(
					'type'=>'text', 
					'title'=>JText::_('FLEX_ADDON_PLYR_AUDIO_URL'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_AUDIO_URL_DESC'),
					'placeholder'=>JText::_('FLEX_ADDON_PLYR_AUDIO_URL_PLACEHOLDER'),
					'std'=>'',
					'depends'=>array('media'=>'2')
				),	
					
				'youtube_vimeo_url'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_VIDEO_URL'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_YOUTUBE_VIMEO_URL_DESC'),
					'placeholder'=>JText::_('FLEX_ADDON_PLYR_YOUTUBE_VIMEO_URL_PLACEHOLDER'),
					'std'=>'',
					'depends'=>array('media'=>'3')
					),
			
				'separator2'=>array(
					'type'=>'separator', 
					'title'=>JText::_('FLEX_ADDON_PLYR_SEPARATOR_SETTINGS')
					),	
					
				'autoplay'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_ADDON_PLYR_AUTOPLAY'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_AUTOPLAY_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
					),
				'captions_toggle'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_ADDON_PLYR_TOGGLE_CAPTIONS'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_TOGGLE_CAPTIONS_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>1,
					'depends'=>array('media'=>'1')
					),
				'tooltips'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_ADDON_PLYR_TOOLTIPS'),
					'desc'=>JText::_('FLEX_ADDON_PLYR_TOOLTIPS_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
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