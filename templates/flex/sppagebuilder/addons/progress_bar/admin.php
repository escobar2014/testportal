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
		'type'=>'general',
		'addon_name'=>'sp_progress_bar',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_DESC'),
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
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_TYPE_DESC'),
					'values'=>array(
						'sppb-progress-bar-default'=>JText::_('Default'),
						'flex'=>JText::_('Flex'),
						'sppb-progress-bar-primary'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_PRIMARY'),
						'sppb-progress-bar-success'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_SUCCESS'),
						'sppb-progress-bar-info'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INFO'),
						'sppb-progress-bar-warning'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_WARNING'),
						'sppb-progress-bar-danger'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_DANGER'),
						'custom'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
						),
					'std'=>'sppb-progress-bar-default',
				),

				'progress'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_PROGRESS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_PROGRESS_DESC'),
					'std'=>60,
					'min'=>1,
					'max'=>100
				),
				
				'custom_height'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_HEIGHT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_HEIGHT_DESC'),
					'std'=>20,
					'min'=>1,
					'max'=>100
				),
			
				'bar_background'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_BACKGROUND_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_BACKGROUND_COLOR_DESC'),
					'std' => '#cccccc',
				),
				
				'custom_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_CUSTOM_COLOR_DESC'),
					'std' => '#f14833',
				),

				'stripped'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_STRIPPED'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_STRIPPED_DESC'),
					'values'=>array(
						''=>JText::_('JNO'),
						'sppb-progress-bar-striped'=>JText::_('JYES'),
					),
				),
				
				'active'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ACTIVE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ACTIVE_DESC'),
					'values'=>array(
						''=>JText::_('JNO'),
						'active'=>JText::_('JYES'),
						),
				),

				'text'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_TEXT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_TEXT_DESC'),
					'std'=>'',
				),
				
				'text_color'=>array(
					'type'=>'color', 
					'title'=>JText::_('HELIX_TEXT_COLOR'),
					'desc'=>JText::_('HELIX_TEXT_COLOR_DESC'),
					'std'=>'',
					'depends'=>array(array('text', '!=', '')),
				),

				'animation_duration'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ANIMATION_DURATION'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ANIMATION_DURATION_DESC'),
					//'placeholder'=>2,
					'std'=>2,
					'min'=>1,
					'max'=>10
				),
					
				'animation_delay'=>array(
					'type'=>'slider',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ANIMATION_DELAY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_ANIMATION_DELAY_DESC'),
					//'placeholder'=>0,
					'std'=>0,
					'min'=>0,
					'max'=>10
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
