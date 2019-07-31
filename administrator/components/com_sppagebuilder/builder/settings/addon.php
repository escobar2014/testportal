<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2019 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

$addon_global_settings = array(
	'style' => array(
		'global_options'=>array(
			'type'=>'separator',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
		),
		'global_text_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR')
		),
		'global_link_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LINK_COLOR'),
		),
		'global_link_hover_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LINK_COLOR_HOVER'),
		),
		'global_background_type'=>array(
			'type'=>'buttons',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
			'std'=>'none',
			'values'=>array(
				array(
					'label' => 'None',
					'value' => 'none'
				),
				array(
					'label' => 'Color',
					'value' => 'color'
				),
				array(
					'label' => 'Image',
					'value' => 'image'
				),
				array(
					'label' => 'Gradient',
					'value' => 'gradient'
				),
			)
		),
		'global_background_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
			'depends'=>array(
				array('global_background_type', '!=', 'none'),
				array('global_background_type', '!=', 'video'),
				array('global_background_type', '!=', 'gradient'),
			)
		),
		'global_background_gradient'=>array(
			'type'=>'gradient',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_GRADIENT'),
			'std'=> array(
				"color" => "#00c6fb",
				"color2" => "#005bea",
				"deg" => "45",
				"type" => "linear"
			),
			'depends'=>array(
				array('global_background_type', '=', 'gradient')
			)
		),
		'global_background_image'=>array(
			'type'=>'media',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_IMAGE'),
			'depends'=>array(
				array('global_background_type', '=', 'image')
			)
		),
		'global_background_repeat'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT'),
			'values'=>array(
				'no-repeat'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_NO_REPEAT'),
				'repeat-all'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_ALL'),
				'repeat-x'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
				'repeat-y'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
				'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
			),
			'std'=>'no-repeat',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_background_image', '!=', ''),
			)
		),
		'global_background_size'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_DESC'),
			'values'=>array(
				'cover'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_COVER'),
				'contain'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_CONTAIN'),
				'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
			),
			'std'=>'cover',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_background_image', '!=', ''),
			)
		),
		'global_background_attachment'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_DESC'),
			'values'=>array(
				'fixed'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
				'scroll'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_SCROLL'),
				'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
			),
			'std'=>'inherit',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_background_image', '!=', ''),
			)
		),
		'global_background_position'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_POSITION'),
			'values'=>array(
				'0 0'=>JText::_('COM_SPPAGEBUILDER_LEFT_TOP'),
				'0 50%'=>JText::_('COM_SPPAGEBUILDER_LEFT_CENTER'),
				'0 100%'=>JText::_('COM_SPPAGEBUILDER_LEFT_BOTTOM'),
				'50% 0'=>JText::_('COM_SPPAGEBUILDER_CENTER_TOP'),
				'50% 50%'=>JText::_('COM_SPPAGEBUILDER_CENTER_CENTER'),
				'50% 100%'=>JText::_('COM_SPPAGEBUILDER_CENTER_BOTTOM'),
				'100% 0'=>JText::_('COM_SPPAGEBUILDER_RIGHT_TOP'),
				'100% 50%'=>JText::_('COM_SPPAGEBUILDER_RIGHT_CENTER'),
				'100% 100%'=>JText::_('COM_SPPAGEBUILDER_RIGHT_BOTTOM'),
			),
			'std'=>'50% 50%',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_background_image', '!=', ''),
			)
		),
		'global_overlay_separator'=>array(
			'type'=>'separator',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY_OPTIONS'),
			'depends'=>array(
				array('global_background_type', '=', 'image')
			),
		),
		'global_use_overlay'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OVERLAY'),
			'std'=>0,
			'depends'=>array(
				array('global_background_type', '=', 'image')
			)
		),
		'global_overlay_type'=>array(
			'type'=>'buttons',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_CHOOSE'),
			'std'=>'overlay_none',
			'values'=>array(
				array(
					'label' => 'None',
					'value' => 'overlay_none'
				),
				array(
					'label' => 'Color',
					'value' => 'overlay_color'
				),
				array(
					'label' => 'Gradient',
					'value' => 'overlay_gradient'
				),
				array(
					'label' => 'Pattern',
					'value' => 'overlay_pattern'
				)
			),
			'depends'=>array(
				array('global_use_overlay', '!=', 0),
			),
		),
		'global_background_overlay'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY'),
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_use_overlay', '=', 1),
				array('global_overlay_type', '=', 'overlay_color'),
			)
		),
		'global_gradient_overlay'=>array(
			'type'=>'gradient',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT_DESC'),
			'std'=> array(
				"color" => "rgba(127, 0, 255, 0.8)",
				"color2" => "rgba(225, 0, 255, 0.7)",
				"deg" => "45",
				"type" => "linear"
			),
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_use_overlay', '=', 1),
				array('global_overlay_type', '=', 'overlay_gradient'),
			)
		),
		'global_pattern_overlay'=>array(
			'type'=>'media',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_DESC'),
			'std'=> '',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_use_overlay', '=', 1),
				array('global_overlay_type', '=', 'overlay_pattern'),
			)
		),
		'global_overlay_pattern_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR_DESC'),
			'std'=> '',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_use_overlay', '=', 1),
				array('global_overlay_type', '=', 'overlay_pattern'),
			)
		),
		'blend_mode'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BLEND_MODE'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BLEND_MODE_DESC'),
			'values'=>array(
				'normal'=>'Normal',
				'color'=>'Color',
				'color-burn'=>'Color Burn',
				'color-dodge'=>'Color Dodge',
				'darken'=>'Darken',
				'difference'=>'Difference',
				'exclusion'=>'Exclusion',
				'hard-light'=>'Hard Light',
				'hue'=>'Hue',
				'lighten'=>'Lighten',
				'luminosity'=>'Luminosity',
				'multiply'=>'Multiply',
				'overlay'=>'Overlay',
				'saturation'=>'Saturation',
				'screen'=>'Screen',
				'soft-light'=>'Soft Light',
			),
			'std'=>'normal',
			'depends'=>array(
				array('global_background_type', '=', 'image'),
				array('global_use_overlay', '=', 1),
			)
		),

		'global_user_border'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_USE_BORDER'),
			'std'=>0
		),
		'global_border_width'=>array(
			'type'=>'slider',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
			'std'=>'',
			'depends'=>array('global_user_border'=>1),
			'responsive'=> true
		),
		'global_border_color'=>array(
			'type'=>'color',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
			'depends'=>array('global_user_border'=>1)
		),
		'global_boder_style'=>array(
			'type'=>'select',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE'),
			'values'=>array(
				'none'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_NONE'),
				'solid'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
				'double'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOUBLE'),
				'dotted'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
				'dashed'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED'),
			),
			'depends'=>array('global_user_border'=>1)
		),
		'global_border_radius'=>array(
			'type'=>'slider',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
			'std'=>0,
			'max'=>500,
			'responsive'=> true
		),
		'global_margin'=>array(
			'type'=>'margin',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
			'std'=>array('md'=> '0px 0px 30px 0px', 'sm'=> '0px 0px 20px 0px', 'xs'=> '0px 0px 10px 0px'),
			'responsive' => true
		),
		'global_padding'=>array(
			'type'=>'padding',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
			'std'=>'',
			'responsive' => true
		),
		'global_boxshadow'=>array(
			'type'=>'boxshadow',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BOXSHADOW'),
			'std'=>'0 0 0 0 #ffffff'
		),
		'global_use_animation'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_USE_ANIMATION'),
			'std'=>0
		),
		'global_animation'=>array(
			'type'=>'animation',
			'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DESC'),
			'depends'=>array('global_use_animation'=>1)
		),

		'global_animationduration'=>array(
			'type'=>'number',
			'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DURATION'),
			'desc'=> JText::_('COM_SPPAGEBUILDER_ANIMATION_DURATION_DESC'),
			'std'=>'300',
			'placeholder'=>'300',
			'depends'=>array('global_use_animation'=>1)
		),

		'global_animationdelay'=>array(
			'type'=>'number',
			'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DELAY'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DELAY_DESC'),
			'std'=>'0',
			'placeholder'=>'300',
			'depends'=>array('global_use_animation'=>1)
		),

		'global_custom_css'=>array(
			'type'=>'css',
			'title'=>JText::_('COM_SPPAGEBUILDER_CUSTOM_CSS'),
			'std'=>'',
		),
	),

	'advanced' => array(
		'use_global_width'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_USE_WIDTH'),
			'std'=>'0',
		),
		'global_width' => array(
			'type'=>'slider',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
			'max'=>100,
			'responsive'=>true,
			'depends'=>array('use_global_width'=>1)
		),
		'hidden_md'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD_DESC'),
			'std'=>'0',
			),

		'hidden_sm'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM_DESC'),
			'std'=>'0',
			),

		'hidden_xs'=>array(
			'type'=>'checkbox',
			'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS'),
			'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS_DESC'),
			'std'=>'0',
			),

		'acl' => array(
			'type' => 'accesslevel',
			'title' => JText::_('COM_SPPAGEBUILDER_ACCESS'),
			'desc' => JText::_('COM_SPPAGEBUILDER_ACCESS_DESC'),
			'placeholder' => '',
			'std' 			=> '',
			'multiple' => true
			)
		)
	);
