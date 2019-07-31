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

class SppagebuilderAddonAnimated_headlines extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$before_text = (isset($this->addon->settings->before_text) && $this->addon->settings->before_text) ? $this->addon->settings->before_text : '';
		$after_text = (isset($this->addon->settings->after_text) && $this->addon->settings->after_text) ? $this->addon->settings->after_text : '';
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : '';
		$effect = (isset($this->addon->settings->effect) && $this->addon->settings->effect) ? $this->addon->settings->effect : 'slide reset-width';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h2';
		$title_margin = (isset($this->addon->settings->title_margin) && $this->addon->settings->title_margin) ? $this->addon->settings->title_margin : '';
		$title_padding = (isset($this->addon->settings->title_padding) && $this->addon->settings->title_padding) ? $this->addon->settings->title_padding : '';
		
		$class = '';
		$output = '';
		$centered = '';
		$title_style = '';
		
		if($before_text && $after_text) {
			$centered = ' centered';
		}

		$output  = '<div class="sppb-addon-animated_headlines ' . $alignment . ' ' . $class . '">';
		
		// Before Text
		$output .= '<'.$heading_selector.' class="sppb-addon-title animated_headlines '. $effect .'">';
		if($before_text) {
			$output .= '<span class="before-text match-height">';
			$output .= nl2br($before_text);
			$output .= '</span>';
		}
		$output .= '<span class="ah-words-wrapper'. (($effect == 'letters type') ? ' waiting' : '' ) .' match-height">';
	
		// Repeatable Items
		foreach ($this->addon->settings->sp_animated_headlines_item as $key => $value) {
			
			$headline_class = (isset($value->headline_class) && $value->headline_class) ? $value->headline_class : '';
			
			if($value->title) {
				$text = (isset($value->title) && $value->title) ? $value->title: '';
				$output .= '<b id="headline-'. ($this->addon->id + $key) .'"'. (($key == 0) ? ' class="is-visible match-height animated_headline'. $headline_class. '"': ' class="'. $headline_class .' match-height animated_headline'. $centered .'"' ) .'>';
				if ($effect == 'letters rotate-2' || $effect == 'letters rotate-3' || $effect == 'letters scale') {
					$text = str_replace(" ", "&nbsp;", $text); // add &nbsp; between words for "letters only" words
				}
				$output .= nl2br($text);
				$output .= '</b>';
			}
		}
		$output .= '</span>';
		// After Text
		if($after_text) {
			$output .= '<span class="after-text match-height">';
			$output .= nl2br($after_text);
			$output .= '</span>';
		}
		
		$output  .= '</'.$heading_selector.'>';
		$output  .= '</div>';

		return $output;
	}
	
	public function css() {
        $addon_id = '#sppb-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';

        $style = '';
        $style_sm = '';
        $style_xs = '';
		$line_height = '';
		$line_height_sm = '';
		$line_height_xs = '';
		
        $style .= (isset($this->addon->settings->title_margin) && $this->addon->settings->title_margin) ? 'margin: ' . $this->addon->settings->title_margin  . '; ' : '0';
        $style_sm .= (isset($this->addon->settings->title_margin_sm) && $this->addon->settings->title_margin_sm) ? 'margin: ' . $this->addon->settings->title_margin_sm  . '; ' : '0';
        $style_xs .= (isset($this->addon->settings->title_margin_xs) && $this->addon->settings->title_margin_xs) ? 'margin: ' . $this->addon->settings->title_margin_xs  . '; ' : '0';

        $style .= (isset($this->addon->settings->title_padding) && $this->addon->settings->title_padding) ? 'padding: ' . $this->addon->settings->title_padding  . '; ' : '0';
        $style_sm .= (isset($this->addon->settings->title_padding_sm) && $this->addon->settings->title_padding_sm) ? 'padding: ' . $this->addon->settings->title_padding_sm  . '; ' : '0';
        $style_xs .= (isset($this->addon->settings->title_padding_xs) && $this->addon->settings->title_padding_xs) ? 'padding: ' . $this->addon->settings->title_padding_xs  . '; ' : '0';

        $heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h2';
		
		// Headlines
		$style .= (isset($this->addon->settings->title_fontsize) && $this->addon->settings->title_fontsize) ? 'font-size: ' . $this->addon->settings->title_fontsize . 'px;' : '';
		$style_sm .= (isset($this->addon->settings->title_fontsize_sm) && $this->addon->settings->title_fontsize_sm) ? 'font-size: ' . $this->addon->settings->title_fontsize_sm . 'px;' : '';
        $style_xs .= (isset($this->addon->settings->title_fontsize_xs) && $this->addon->settings->title_fontsize_xs) ? 'font-size: ' . $this->addon->settings->title_fontsize_xs . 'px;' : '';
		$style .= (isset($this->addon->settings->title_lineheight) && $this->addon->settings->title_lineheight) ? 'line-height: ' . $this->addon->settings->title_lineheight . 'px;' : '';
		$style_sm .= (isset($this->addon->settings->title_lineheight_sm) && $this->addon->settings->title_lineheight_sm) ? 'line-height: ' . $this->addon->settings->title_lineheight_sm . 'px;' : '';
        $style_xs .= (isset($this->addon->settings->title_lineheight_xs) && $this->addon->settings->title_lineheight_xs) ? 'line-height: ' . $this->addon->settings->title_lineheight_xs . 'px;' : '';
		$style .= (isset($this->addon->settings->title_letterspace) && $this->addon->settings->title_letterspace) ? 'letter-spacing: ' . $this->addon->settings->title_letterspace . ';' : '';
		
        if(isset($this->addon->settings->title_text_shadow) && is_object($this->addon->settings->title_text_shadow)){
            $ho = (isset($this->addon->settings->title_text_shadow->ho) && $this->addon->settings->title_text_shadow->ho != '') ? $this->addon->settings->title_text_shadow->ho.'px' : '0';
            $vo = (isset($this->addon->settings->title_text_shadow->vo) && $this->addon->settings->title_text_shadow->vo != '') ? $this->addon->settings->title_text_shadow->vo.'px' : '0';
            $blur = (isset($this->addon->settings->title_text_shadow->blur) && $this->addon->settings->title_text_shadow->blur != '') ? $this->addon->settings->title_text_shadow->blur.'px' : '0';
            $color = (isset($this->addon->settings->title_text_shadow->color) && $this->addon->settings->title_text_shadow->color != '') ? $this->addon->settings->title_text_shadow->color : '#777';

            $style .= "text-shadow: ${ho} ${vo} ${blur} ${color};";
        }

        $style .= (isset($this->addon->settings->title_text_transform) && $this->addon->settings->title_text_transform) ? 'text-transform: ' . $this->addon->settings->title_text_transform  . '; ' : '';
		
		// Loading Bar
		$loading_bar = (isset($this->addon->settings->loading_bar) && $this->addon->settings->loading_bar) ? 'height: ' . $this->addon->settings->loading_bar  . 'px;' : '';
		$loading_bar_color = (isset($this->addon->settings->loading_bar_color) && $this->addon->settings->loading_bar_color != '') ? 'background-color:' .$this->addon->settings->loading_bar_color  . ';' : '';
		
		$typing_cursor_color = (isset($this->addon->settings->typing_cursor_color) && $this->addon->settings->typing_cursor_color != '') ? 'border-right:3px solid ' .$this->addon->settings->typing_cursor_color  . ';' : '';
		
		$clip_cursor_color = (isset($this->addon->settings->clip_cursor_color) && $this->addon->settings->clip_cursor_color != '') ? 'background-color:' .$this->addon->settings->clip_cursor_color  . ';' : '';
		
		// Line Height
		$line_height .= (isset($this->addon->settings->title_lineheight) && $this->addon->settings->title_lineheight) ? 'line-height: ' . $this->addon->settings->title_lineheight . 'px;' : '';
		$line_height_sm .= (isset($this->addon->settings->title_lineheight_sm) && $this->addon->settings->title_lineheight_sm) ? 'line-height: ' . $this->addon->settings->title_lineheight_sm . 'px;' : '';
        $line_height_xs .= (isset($this->addon->settings->title_lineheight_xs) && $this->addon->settings->title_lineheight_xs) ? 'line-height: ' . $this->addon->settings->title_lineheight_xs . 'px;' : '';


        $css = '';
        if ($style) {
            $css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title {' . $style . '}';
        }

        if ($style_sm) {
            $css .= '@media (min-width: 768px) and (max-width: 991px) {';
            $css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title {' . $style_sm . '}';
			$css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title span, ' . $addon_id . ' ' . $heading_selector . '.sppb-addon-title span b {' . $line_height_sm . '}';
            $css .= '}';
        }

        if ($style_xs) {
            $css .= '@media (max-width: 767px) {';
            $css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title {' . $style_xs . '}';
			$css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title span, ' . $addon_id . ' ' . $heading_selector . '.sppb-addon-title span b {' . $line_height_xs . '}';
            $css .= '}';
        }
		
		if ($typing_cursor_color) {
			$css .= $addon_id . ' ' . $heading_selector . '.type .ah-words-wrapper::after {' . $typing_cursor_color .'}';
		}
		
		if ($clip_cursor_color) {
			$css .= $addon_id . ' ' . $heading_selector . '.clip .ah-words-wrapper::after {' . $clip_cursor_color .'}';
		}
		
		
		if ($loading_bar || $loading_bar_color) {
			$css .= $addon_id . ' ' . $heading_selector . '.loading-bar .ah-words-wrapper::after {' . $loading_bar . $loading_bar_color .'}';
		}
		
	
		// Repeatable Items
		$headline_style = '';
		$headline_fontstyle = '';	
		
		if(isset($this->addon->settings->sp_animated_headlines_item) && count($this->addon->settings->sp_animated_headlines_item)){
			foreach ($this->addon->settings->sp_animated_headlines_item as $key => $value) {
				$headline_color = (isset($value->headline_color) && $value->headline_color != '') ? $value->headline_color : '';
				$headline_fontstyle = (isset($value->headline_fontstyle) && $value->headline_fontstyle != '') ? $value->headline_fontstyle : '';

				
				if($value->title) {
					
					if(is_array($headline_fontstyle) && count($headline_fontstyle)) {
						if(in_array('uppercase', $headline_fontstyle)) {
						  $headline_style .= 'text-transform: uppercase;';
						} 
				
						if(in_array('italic', $headline_fontstyle)) {
						  $headline_style .= 'font-style: italic;';
						} 
				
						if(in_array('lighter', $headline_fontstyle)) {
						  $headline_style = 'font-weight: lighter;';
						} else if(in_array('normal', $headline_fontstyle)) {
						  $headline_style .= 'font-weight: normal;';
						} else if(in_array('bold', $headline_fontstyle)) {
						  $headline_style .= 'font-weight: bold;';
						} else if(in_array('bolder', $headline_fontstyle)) {
						  $headline_style .= 'font-weight: bolder;';
						} 
					}
		
					$options = new stdClass;
					
					$options->headline_font_family = (isset($value->headline_font_family) && $value->headline_font_family) ? $value->headline_font_family : '';
					$options->headline_font_family_selector = (isset($value->font_family_selector) && $value->font_family_selector) ? $value->font_family_selector : '';
				
					$selector_css = new JLayoutFile('addon.css.selector', $layout_path);
					$css .= $selector_css->render(
					  array(
					    'options'=>$value,
					    'addon_id'=>$addon_id,
					    'selector'=>'#headline-' . ($this->addon->id + $key)
					  )
					);
					

					if($headline_color || $headline_fontstyle) {
						$css .= $addon_id . ' ' . $heading_selector . '.sppb-addon-title #headline-'. ($this->addon->id + $key) .', ' .$addon_id . ' ' . $heading_selector . '.sppb-addon-title #headline-' . ($this->addon->id + $key) . ' i, ' .$addon_id . ' ' . $heading_selector . '.sppb-addon-title #headline-' . ($this->addon->id + $key) . ' span, ' .$addon_id . ' ' . $heading_selector . '.sppb-addon-title #headline-' . ($this->addon->id + $key) . ' b {'. $headline_style .'color: ' . $headline_color . '}';
					}
				}
			}
		}
        return $css;
    }
	
	    public static function getTemplate() {
        $output = '
        <#
            var margin = "";
			var margin_sm = "";
			var margin_xs = "";
			if(data.title_margin){
				if(_.isObject(data.title_margin)){
                    if(data.title_margin.md.trim() != ""){
                        margin = data.title_margin.md.split(" ").map(item => {
                            if(_.isEmpty(item)){
                                return "0";
                            }
                            return item;
                        }).join(" ")
                    }
                    if(data.title_margin.sm.trim() != ""){
                        margin_sm = data.title_margin.sm.split(" ").map(item => {
                            if(_.isEmpty(item)){
                                return "0";
                            }
                            return item;
                        }).join(" ")
                    }
                    if(data.title_margin.xs.trim() != ""){
                        margin_xs = data.title_margin.xs.split(" ").map(item => {
                            if(_.isEmpty(item)){
                                return "0";
                            }
                            return item;
                        }).join(" ")
                    }
				} else {
                    if(data.title_margin.trim() != ""){
                        margin = data.title_margin.split(" ").map(item => {
                            if(_.isEmpty(item)){
                                return "0";
                            }
                            return item;
                        }).join(" ")
                    }
				}

			}

			var padding = "";
			var padding_sm = "";
			var padding_xs = "";
			if(data.title_padding){
				if(_.isObject(data.title_padding)){
					if(data.title_padding.md.trim() !== ""){
						padding = data.title_padding.md.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.title_padding.sm.trim() !== ""){
						padding_sm = data.title_padding.sm.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.title_padding.xs.trim() !== ""){
						padding_xs = data.title_padding.xs.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}
				} else {
					padding = data.title_padding.split(" ").map(item => {
						if(_.isEmpty(item)){
							return "0";
						}
						return item;
					}).join(" ")
				}

            }
   
            var titleTextShadow = "";
			if(_.isObject(data.title_text_shadow)){
				let ho = data.title_text_shadow.ho || 0,
					vo = data.title_text_shadow.vo || 0,
					blur = data.title_text_shadow.blur || 0,
					color = data.title_text_shadow.color || 0;

                titleTextShadow = ho+\'px \'+vo+\'px \'+blur+\'px \'+color;
			}
        #>
        <style type="text/css">
            #sppb-addon-{{ data.id }} {{ data.heading_selector }}.sppb-addon-title{
                margin: {{ margin }};
                padding: {{ padding }};
                text-shadow: {{ titleTextShadow }};
                text-transform: {{ data.title_text_transform }};
            }

            @media (min-width: 768px) and (max-width: 991px) {
                #sppb-addon-{{ data.id }} {{ data.heading_selector }}.sppb-addon-title{
                    margin: {{ margin_sm }};
                    padding: {{ padding_sm }};
                }
            }
            @media (max-width: 767px) {
                #sppb-addon-{{ data.id }} {{ data.heading_selector }}.sppb-addon-title{
                    margin: {{ margin_xs }};
                    padding: {{ padding_xs }};
                }
            }
        </style>
		<div class="sppb-addon-animated_headlines {{ data.class }} {{ data.alignment }}">
			<{{ data.heading_selector }} class="sppb-addon-title animated_headlines {{ data.effect }}">
			<span class="before-text match-height">{{{ data.before_text }}}</span>
			<span class="ah-words-wrapper match-height">
			<# _.each(data.sp_animated_headlines_item, function(animated_headlines_item, key){
				var activeClass = (key == 0) ? "is-visible " : ""; 
				#>
				<# if(animated_headlines_item.title){ #>
					<b class="{{ activeClass }} match-height animated_headline">
					{{ animated_headlines_item.title }}
					</b>
				<# } #>
			<# }); #>
			</span>
			<span class="after-text match-height">{{{ data.after_text }}}</span>
			</{{ data.heading_selector }}>
        </div>
        ';

        return $output;
    }

}