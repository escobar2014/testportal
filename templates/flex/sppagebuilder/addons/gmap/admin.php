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
		'addon_name'=>'sp_gmap',
		'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP'),
		'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_DESC'),
		'attr'=>array(
			'general' => array(

				'admin_label'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std'=> ''
				),

				// Title
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

				'title_lineheight'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_LINE_HEIGHT'),
					'std'=>'',
					'depends'=>array(array('title', '!=', '')),
				),

				'title_fontstyle'=>array(
					'type'=>'select',
					'title'=> JText::_('COM_SPPAGEBUILDER_ADDON_TITLE_FONT_STYLE'),
					'values'=>array(
						'underline'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_UNDERLINE'),
						'uppercase'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_UPPERCASE'),
						'italic'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_ITALIC'),
						'lighter'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_LIGHTER'),
						'normal'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_NORMAL'),
						'bold'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_BOLD'),
						'bolder'=> JText::_('COM_SPPAGEBUILDER_GLOBAL_FONT_STYLE_BOLDER'),
					),
					'multiple'=>true,
					'std'=>'',
					'depends'=>array(array('title', '!=', '')),
				),

				'title_letterspace'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LETTER_SPACING'),
					'values'=>array(
						'0'=> 'Default',
						'1px'=> '1px',
						'2px'=> '2px',
						'3px'=> '3px',
						'4px'=> '4px',
						'5px'=> '5px',
						'6px'=>	'6px',
						'7px'=>	'7px',
						'8px'=>	'8px',
						'9px'=>	'9px',
						'10px'=> '10px'
					),
					'std'=>'0',
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

				// Map

				'separator_addon_options'=>array(
					'type'=>'separator',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_ADDON_OPTIONS')
				),
				
				'api_key'=>array(
					'type'=>'text', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GOOGLE_API_KEY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GOOGLE_API_KEY_DESC'),
					'placeholder'=>JText::_('COM_SPPAGEBUILDER_ADDON_GOOGLE_API_KEY_PLACEHOLDER'),
					'std'=> ''
				), 

				'map'=>array(
					'type'=>'gmap',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCATION'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCATION_DESC'),
				),
				
				'infowindow'=>array(
					'type'=>'textarea',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_INFOWINDOW'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_INFOWINDOW_DESC'),
				),

				'height'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HEIGHT'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HEIGHT_DESC'),
					'placeholder'=>'300',
					'std'=>'300',
					'depends'=>array(array('map', '!=', '')),
				),

				'type'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE_DESC'),
					'values'=>array(
						'ROADMAP'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE_ROADMAP'),
						'SATELLITE'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE_SATELLITE'),
						'HYBRID'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE_HYBRID'),
						'TERRAIN'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_TYPE_TERRAIN'),
					),
					'depends'=>array(array('map', '!=', '')),
				),

				'zoom'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ZOOM'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ZOOM_DESC'),
					'placeholder'=>'18',
					'std'=>'18',
					'depends'=>array(array('map', '!=', '')),
				),

				'mousescroll'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_DISABLE_MOUSE_SCROLL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_DISABLE_MOUSE_SCROLL_DESC'),
					'values'=>array(
						'false'=>JText::_('JYES'),
						'true'=>JText::_('JNO'),
					),
					'std'=>'true',
					'depends'=>array(array('map', '!=', '')),
				),
				
				'street_view_control'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_STREET_VIEW_CONTROL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_STREET_VIEW_CONTROL_DESC'),
					'values'=>array(
						'false'=>JText::_('JYES'),
						'true'=>JText::_('JNO'),
					),
					'std'=>'false',
					'depends'=>array(array('map', '!=', '')),
				),
				
				'map_type_control'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_MAP_TYPE_CONTROL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_MAP_TYPE_CONTROL_DESC'),
					'values'=>array(
						'false'=>JText::_('JYES'),
						'true'=>JText::_('JNO'),
					),
					'std'=>'false',
					'depends'=>array(array('map', '!=', '')),
				),
				
				'fullscreen_control'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_FULLSCREEN_CONTROL'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_FULLSCREEN_CONTROL_DESC'),
					'values'=>array(
						'false'=>JText::_('JYES'),
						'true'=>JText::_('JNO'),
					),
					'std'=>'false',
					'depends'=>array(array('map', '!=', '')),
				),
				
				'show_transit'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_GMAP_SHOW_TRANSIT'),
					'desc'=>JText::_('FLEX_GMAP_SHOW_TRANSIT_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
					'depends'=>array(array('map', '!=', '')),
				),
				
				'show_poi'=>array(
					'type'=>'checkbox', 
					'title'=>JText::_('FLEX_GMAP_SHOW_POI'),
					'desc'=>JText::_('FLEX_GMAP_SHOW_POI_DESC'),
					'values'=>array(
						1=>JText::_('JYES'),
						0=>JText::_('JNO'),
					),
					'std'=>0,
					'depends'=>array(array('map', '!=', '')),
				),
				
				'class'=>array(
					'type'=>'text',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
					'std'=>''
				),
				
				'separator'=>array(
					'type'=>'separator',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_COLOR_SETTINGS'),
					),
	
				'water_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_WATER_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_WATER_COLOR_DESC'),
					'class'=>'span2',
					'std'=> '',
					),
	
				'highway_stroke_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HW_STROKE_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HW_STROKE_COLOR_DESC'),
					'std'=> '',
					),
				'highway_fill_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HW_FILL_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_HW_FILL_COLOR_DESC'),
					'std'=> '',
					),
				'local_stroke_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCAL_STROKE_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCAL_STROKE_COLOR_DESC'),
					'std'=> '',
					),
				'local_fill_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCAL_FILL_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LOCAL_FILL_COLOR_DESC'),
					'std'=> '',
					),
				'poi_fill_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_POI_FILL_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_POI_FILL_COLOR_DESC'),
					'std'=> '',
					),
				'administrative_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ADMINISTRATIVE_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ADMINISTRATIVE_COLOR_DESC'),
					'std'=> '',
					),
				'landscape_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LANDSCAPE_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_LANDSCAPE_COLOR_DESC'),
					'std'=> '',
					),
				'road_text_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_TEXT_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_TEXT_COLOR_DESC'),
					'std'=> '',
					),
				'road_arterial_fill_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_ARTERIAL_FILL_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_ARTERIAL_FILL_COLOR_DESC'),
					'std'=> '',
					),
				'road_arterial_stroke_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_ARTERIAL_STROKE_COLOR'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ADDON_GMAP_ROAD_ARTERIAL_STROKE_COLOR_DESC'),
					'std'=> '',
					),
			),
		),
	)
);
