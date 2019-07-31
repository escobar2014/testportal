<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$params 	= JFactory::getApplication()->getTemplate(true)->params;

$format = $displayData;

if($params->get('show_post_format')) {

	//echo '<span class="post-format">';

	if (  $format == 'audio' ) {
		if ( $params->get('custom_audio_icon') != '' ) {
			echo '<i class="' .$params->get('custom_audio_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-music"></i>';
		}
	} else if (  $format == 'video' ) {
		if ( $params->get('custom_video_icon') != '' ) {
			echo '<i class="' .$params->get('custom_video_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-video-camera"></i>';
		}
	} else if (  $format == 'gallery' ) {
		if ( $params->get('custom_gallery_icon') != '' ) {
			echo '<i class="' .$params->get('custom_gallery_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-picture-o"></i>';
		}
	} else if (  $format == 'quote' ) {
		if ( $params->get('custom_quote_icon') != '' ) {
			echo '<i class="'. $params->get('custom_quote_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-quote-left"></i>';
		}
	} else if (  $format == 'link' ) {
		if ( $params->get('custom_link_icon') != '' ) {
			echo '<i class="'. $params->get('custom_link_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-link"></i>';
		}
	} else if (  $format == 'status' ) {
		if ( $params->get('custom_status_icon') != '' ) {
			echo '<i class="'. $params->get('custom_status_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-comment-o"></i>';
		}
	} else if (  $format == 'custom' ) {
		if ( $params->get('custom_post_icon') != '' ) {
			echo '<i class="'. $params->get('custom_post_icon') .'"></i>';
		} else {
			echo '<i class="fa fa-thumbs-o-up"></i>';
		}
	} else {
		if ( $params->get('custom_standard_icon') != '' ) {
			echo '<i class="'. $params->get('custom_standard_icon') .'"></i>';
		} else {
			echo '<i style="margin-right:-6px;" class="fa fa-pencil-square-o"></i>';
		}
	}
	//echo '</span>';

} 
