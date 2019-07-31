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
		'type'=>'content',
		'addon_name'=>'sp_bootstrap_modal',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BOOTSTRAP_MODAL'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BOOTSTRAP_MODAL_DESC'),
		'category'=>'Flex',
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),

				'modal_selector'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_SELECTOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_SELECTOR_DESC'),
					'values'=>array(
						'button'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_SELECTOR_BUTTON'),
						'image'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_SELECTOR_IMAGE'),
						),
					'std'=>'button',
					),
				'button_text'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_TEXT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_TEXT_DESC'),
					'std'=>'Button',
					'depends'=>array('modal_selector'=>'button')
					),
				'button_size'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE_DESC'),
					'values'=>array(
						''=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE_DEFAULT'),
						'lg'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE_LARGE'),
						'sm'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE_SMALL'),
						'xs'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_SIZE_EXTRA_SAMLL'),
						),
					'depends'=>array('modal_selector'=>'button')
					),
				'button_type'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_TYPE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_TYPE_DESC'),
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
					'depends'=>array('modal_selector'=>'button')
					),
				'button_peicon'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_PIXEDEN_ICON'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_PIXEDEN_ICON_DESC'),
					'std'=> '',
					'depends'=>array('modal_selector'=>'button'),
					'values'=> $peicon_list
					),
				'button_icon'=>array(
					'type'=>'icon', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_FONTAWESOME_ICON'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_FONTAWESOME_ICON_DESC'),
					'depends'=>array('modal_selector'=>'button')
					),
				'button_block'=>array(
					'type'=>'select', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_BLOCK'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BUTTON_BLOCK_DESC'),
					'values'=>array(
						''=>JText::_('JNO'),
						'sppb-btn-block'=>JText::_('JYES'),
						),
					'depends'=>array(
						array('modal_selector', '=', 'button')
					),
				),
				'button_icon_position'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_POSITION'),
					'values'=>array(
						'left'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
						'right'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
					),
					'depends'=>array(
						array('modal_selector', '=', 'button')
					),
				),

				'selector_image'=>array(
					'type'=>'media', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_SELECTOR_IMAGE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_IMAGE_SELECT_DESC'),
					'depends'=>array('modal_selector'=>'image')
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
				//Admin Only
				'separator'=>array(
					'type'=>'separator', 
					'title'=>JText::_('COM_SPPAGEBUILDER_MODAL_CONTENT'),
					),
				'modal_window_title'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_BOOTSTRAP_MODAL_WINDOW_TITLE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_BOOTSTRAP_MODAL_WINDOW_TITLE_DESC'),
					'std'=>'Modal'
					),
				'modal_content_text'=>array(
					'type'=>'editor', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_TEXT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_TEXT_DESC'),
					'std'=>'Credibly reintermediate backend ideas for cross-platform models. Continually reintermediate integrated processes through technically sound intellectual capital. Holistically foster superior methodologies without market-driven best practices.&lt;br&gt;&lt;br&gt;Distinctively exploit optimal alignments for intuitive bandwidth. Quickly coordinate e-business applications through revolutionary catalysts for change. Seamlessly underwhelm optimal testing procedures whereas bricks-and-clicks processes. Synergistically evolve 2.0 technologies rather than just in time initiatives. Quickly deploy strategic networks with compelling e-business. Credibly pontificate highly efficient manufactured products and enabled data.',
					),
				'modal_content_image'=>array(
					'type'=>'media', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_IMAGE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_IMAGE_DESC'),
					),
				'modal_content_video_url'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_VIDEO'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_VIDEO_URL_DESC'),
					),
				'modal_window_size'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_WINDOW_SIZE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_WINDOW_SIZE_DESC'),
					'values'=>array(
						''=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_WINDOW_SIZE_STANDARD'),
						'sppb-modal-lg'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_WINDOW_SIZE_LARGE'),
						'sppb-modal-sm'=>JText::_('COM_SPPAGEBUILDER_ADDON_MODAL_WINDOW_SIZE_SMALL'),
						),
					'std'=>'',
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