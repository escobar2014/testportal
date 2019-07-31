<?php
/**
 * Flex @package SP Page Builder
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
// no direct access
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonPlyr extends SppagebuilderAddons{

	public function render() {
		
		$app = JFactory::getApplication();

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';
		
		$media = (isset($this->addon->settings->media) && $this->addon->settings->media) ? $this->addon->settings->media : '';
		$video = (isset($this->addon->settings->video) && $this->addon->settings->video) ? $this->addon->settings->video : '';
		$video_poster = (isset($this->addon->settings->video_poster) && $this->addon->settings->video_poster) ? $this->addon->settings->video_poster : '';
		$captions = (isset($this->addon->settings->captions) && $this->addon->settings->captions) ? $this->addon->settings->captions : '';
		$captions_lang_label = (isset($this->addon->settings->captions_lang_label) && $this->addon->settings->captions_lang_label) ? $this->addon->settings->captions_lang_label : '';
		$captions_srclang = (isset($this->addon->settings->captions_srclang) && $this->addon->settings->captions_srclang) ? $this->addon->settings->captions_srclang : '';
		$audio = (isset($this->addon->settings->audio) && $this->addon->settings->audio) ? $this->addon->settings->audio : '';
		$youtube_vimeo_url = (isset($this->addon->settings->youtube_vimeo_url) && $this->addon->settings->youtube_vimeo_url) ? $this->addon->settings->youtube_vimeo_url : '';
		$autoplay = (isset($this->addon->settings->autoplay) && $this->addon->settings->autoplay) ? $this->addon->settings->autoplay : '';
		
		$captions_toggle = (isset($this->addon->settings->captions_toggle) && $this->addon->settings->captions_toggle) ? $this->addon->settings->captions_toggle : '';
		$tooltips = (isset($this->addon->settings->tooltips) && $this->addon->settings->tooltips) ? $this->addon->settings->tooltips : '';
		
		$randomid = rand(1,1000); //random ID number to avoid conflict if there is more then one Plyr media player on the same page
		
		$autoplay == '1' ? $autoplay = 'autoplay:true,' : $autoplay = '';
		$captions_toggle == '1' ? $captions_toggle = 'captions:{defaultActive:true},' : $captions_toggle = '';
		$tooltips == '1' ? $tooltips = 'tooltips:{controls:true,seek:true},' : $tooltips = '';
		
		$show_captions = '';
		$src_captions = '';
		$show_minimal = '';
		$src_video = '';
		$src_video_poster = '';
		$src_audio = '';
		$data_type = '';


	$class != '' ? $class = ' ' . $class : $class = '';
	
	if($video) {
		if (false === strpos($video, '://')) {
		    $src_video = JURI::root() . $video;
		} else {
			$src_video = $video;
		}
	}
	
	if($video_poster) {
		if (false === strpos($video_poster, '://')) {
		    $src_video_poster = JURI::root() . $video_poster;
		} else {
			$src_video_poster = $video_poster;
		}
	}	
	
	if($captions) {
		if (false === strpos($captions, '://')) {
		    $src_captions = JURI::root() . $captions;
		} else {
			$src_captions = $captions;
		}
		$show_captions = '\'captions\', ';
	}
	
	if($audio) {
		if (false === strpos($audio, '://')) {
		    $src_audio = JURI::root() . $audio;
		} else {
			$src_audio = $audio;
		}
	}

	if($youtube_vimeo_url) {

		$yv_video = parse_url($youtube_vimeo_url);
		
		switch($yv_video['host']) {
			case 'youtu.be':
				$id = trim($yv_video['path'],'/');
				$src_youtube = $id;
				$data_type = 'youtube';
			break;
			
			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($yv_video['query'], $query);
				$id = $query['v'];
				$src_youtube = $id;
				$data_type = 'youtube';
			break;
			
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($yv_video['path'],'/');
				$src_vimeo = $id;
				$data_type = 'vimeo';
		}
	}
	
		$output  = '<div class="sppb-addon sppb-addon-plyr' . $class . '">';

		if($title) {

			$title_style = '';
			if($title_margin_top) $title_style .= 'margin-top:' . (int) $title_margin_top . 'px;';
			if($title_margin_bottom) $title_style .= 'margin-bottom:' . (int) $title_margin_bottom . 'px;';
			if($title_text_color) $title_style .= 'color:' . $title_text_color  . ';';
			if($title_fontsize) $title_style .= 'font-size:'.$title_fontsize.'px;line-height:'.$title_fontsize.'px;';
			if($title_fontweight) $title_style .= 'font-weight:'.$title_fontweight.';';

			$output .= '<'.$heading_selector.' class="sppb-addon-title" style="' . $title_style . '">' . $title . '</'.$heading_selector.'>';
		}

		// Plyr Starts
		
		if ($media == '1') {		
			$output .= '<div id="plyr-media-player-'.$randomid.'">';
			$output .= '<video poster="'.$src_video_poster.'" controls crossorigin>';
			$output .= '<source src="'.$src_video.'" type="video/mp4">';
			$output .= '<!-- Text track file -->';
			$output .= '<track kind="captions" label="'.$captions_lang_label.'" srclang="'.$captions_srclang.'" src="'.$src_captions.'" default>';
			$output .= '<!-- Fallback for browsers that don’t support the <video> element -->';
			$output .= '<a href="'.$src_video.'">'. JText::_('FLEX_DOWNLOAD') .'</a>';
			$output .= '</video>';
			$output .= '</div>';
		}
		
		if ($media == '2') {	
			// Audio Player
			$output .= '<div id="plyr-media-player-'.$randomid.'">';
			$output .= '<audio controls>
			<source src="' . $src_audio . '" type="audio/mp3"><a href="' . $src_audio . '">'. JText::_('FLEX_DOWNLOAD') .'</a>
			</audio>';
			$output .= '</div>';
		}
		
		
		if ($media == '3') {	
			// Youtube 		
			if ($data_type == "youtube") {
				$output .= '<div id="plyr-media-player-'.$randomid.'"><div data-video-id="'.$src_youtube.'" data-type="'.$data_type.'"></div></div>';
			}
			// Vimeo
			if ($data_type == "vimeo") {
			$output .= '<div id="plyr-media-player-'.$randomid.'"><div data-video-id="'.$src_vimeo.'" data-type="vimeo"></div></div>';
			}
		
		}
		
		$output .= '</div>';
		
		// Add JS at the end
	    $output .= '<script type="text/javascript">
		var player = plyr.setup(\'#plyr-media-player-'.$randomid.'\', {
		'.$autoplay.'
		showPosterOnEnd: true,
		controls: [\'play-large\', \'play\', \'progress\', \'current-time\', \'mute\', \'volume\', '.$show_captions.'\'fullscreen\'],
		i18n: {
			play:"'. JText::_('FLEX_ADDON_PLYR_PLAY') .'",
			pause:"'. JText::_('FLEX_ADDON_PLYR_PAUSE') .'",
			toggleMute:"'. JText::_('FLEX_ADDON_PLYR_TOGGLE_MUTE') .'",
			toggleCaptions:"'. JText::_('FLEX_ADDON_PLYR_TOGGLE_CAPTIONS') .'",
			toggleFullscreen:"'. JText::_('FLEX_ADDON_PLYR_TOGGLE_FULLSCREEN') .'"
		},
		'.$captions_toggle.'
		'.$tooltips.'
		volume: 7,
		iconUrl:"'.JURI::base(true) . '/templates/'.$app->getTemplate().'/sppagebuilder/addons/plyr/assets/css/plyr.svg"
		});</script>';
		
		$output = preg_replace('/[\n\t]+/m', '', $output); // Remove whitespace
		
		return $output;
	}
	
	
	public function stylesheets() {
		$app = JFactory::getApplication();
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();	
		return array( $tmplPath.'/sppagebuilder/addons/plyr/assets/css/plyr.css');
	}
	

	public function scripts() {
		$app = JFactory::getApplication();
		$tmplPath = JURI::base(true) . '/templates/'.$app->getTemplate();	
		return array( $tmplPath.'/sppagebuilder/addons/plyr/assets/js/plyr.js');
	}

}
