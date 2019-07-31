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
		'type'=>'content',
		'addon_name'=>'sp_person',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESC'),
		'category'=>'Content',
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),
				
				'type'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_TYPE'),
					'desc'=>JText::_(''),
					'values'=>array(
						'default'=>JText::_('Default'),
						'flex'=>JText::_('Flex'),
						'flip'=>JText::_('Flip'),
						),
					'std'=>'default',
				),

				'image'=>array(
					'type'=>'media',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_PHOTO'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_PHOTO_DESC'),
					'std'=>'https://sppagebuilder.com/addons/person/person1.jpg',
					'format'=>'image'
				),
				
				'image_border_radius'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_IMAGE_BORDER_RADIUS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_IMAGE_BORDER_RADIUS_DESC'),
					'std'=>0,
					'max'=>400
				),

				'name'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_NAME'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_NAME_DESC'),
					'placeholder'=>'John Doe',
					'std'=>'John Doe',
					),
					
				'name_font_family'=>array(
					'type'=>'fonts',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_NAME_FONT_FAMILY'),
					'depends'=>array(array('name', '!=', '')),
					'selector'=> array(
						'type'=>'font',
						'font'=>'{{ VALUE }}',
						'css'=>'.sppb-person-name { font-family: {{ VALUE }}; }'
					)
				),

				'name_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_NAME_COLOR'),
					'depends'=>array(array('name', '!=', '')),
				),
				
				'designation'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESIGNATION'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESIGNATION_DESC'),
					'placeholder'=>'CEO & Founder',
					'std'=>'CEO & Founder',
					),
					
				'designation_font_family'=>array(
					'type'=>'fonts',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESIGNATION_FONT_FAMILY'),
					'depends'=>array(array('designation', '!=', '')),
					'selector'=> array(
						'type'=>'font',
						'font'=>'{{ VALUE }}',
						'css'=>'.sppb-person-designation { font-family: {{ VALUE }}; }'
					)
				),

				'designation_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESIGNATION_COLOR'),
					'depends'=>array(array('designation', '!=', '')),
				),

				'introtext'=>array(
					'type'=>'textarea',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_INTROTEXT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_INTROTEXT_DESC'),
					'std'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
				),
				
				'introtext_font_family'=>array(
					'type'=>'fonts',
					'title'=>JText::_('FLEX_ADDON_PERSON_INTROTEXT_FONT_FAMILY'),
					'depends'=>array(array('introtext', '!=', '')),
					'selector'=> array(
						'type'=>'font',
						'font'=>'{{ VALUE }}',
						'css'=>'.sppb-person-introtext { font-family: {{ VALUE }}; }'
					)
				),
			
				'facebook'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_FACEBOOK'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_FACEBOOK_DESC'),
					'placeholder'=>'http://www.facebook.com/joomshaper',
					'std'=>'http://www.facebook.com/joomshaper',
					),
				'twitter'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_TWITTER'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_TWITTER_DESC'),
					'placeholder'=>'http://twitter.com/joomshaper',
					'std'=>'http://twitter.com/joomshaper',
					),
				'google_plus'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_GOOGLE_PLUS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_GOOGLE_PLUS_DESC'),
					'placeholder'=>'http://plus.google.com/+Joomshapers',
					'std'=>'http://plus.google.com/+Joomshapers',
					),
				'youtube'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_YOUTUBE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_YOUTUBE_DESC'),
					),
				'linkedin'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_LINKEDIN'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_LINKEDIN_DESC'),
					),
				'pinterest'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_PINTEREST'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_PINTEREST_DESC'),
					),
				'flickr'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_FLICKR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_FLICKR_DESC'),
					),
				'dribbble'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DRIBBBLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_DRIBBBLE_DESC'),
					),
				'behance'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_BEHANCE'),
					'desc'=>JText::_('FLEX_ADDON_PERSON_BEHANCE_DESC'),
					),
				'instagram'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_INSTAGRAM'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_INSTAGRAM_DESC'),
					),
				'social_position'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_SOCIAL_ICONS_POSITION'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_SOCIAL_ICONS_POSITION'),
					'values'=>array(
						'before'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_BEFORE_INTROTEXT'),
						'after'=>JText::_('COM_SPPAGEBUILDER_ADDON_PERSON_AFTER_INTROTEXT'),
						),
					'std'=>'after',
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
				'background'=>array(
					'type'=>'color',
					'title'=>JText::_('FLEX_ADDON_PERSON_BACKGROUND'),
					'desc'=>JText::_('FLEX_ADDON_PERSON_BACKGROUND_DESC'),
					'std'=> '#fff'
					),
				'class'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
					'std'=> ''
				),

			),
		),
	)
);
