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

class SppagebuilderAddonImage_content extends SppagebuilderAddons{

	public function render() {
		
		// Include template's params
		$tpl_params 	= JFactory::getApplication()->getTemplate(true)->params;
		$has_lazyload = $tpl_params->get('lazyload', 1);

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';
		$title_margin_top = (isset($this->addon->settings->title_margin_top) && $this->addon->settings->title_margin_top) ? $this->addon->settings->title_margin_top : '';
		$title_margin_bottom = (isset($this->addon->settings->title_margin_bottom) && $this->addon->settings->title_margin_bottom) ? $this->addon->settings->title_margin_bottom : '';

		//Options
		$image = (isset($this->addon->settings->image) && $this->addon->settings->image) ? $this->addon->settings->image : '';
		$image_width = (isset($this->addon->settings->image_width) && $this->addon->settings->image_width) ? $this->addon->settings->image_width : '';
		$image_background_size = (isset($this->addon->settings->image_background_size) && $this->addon->settings->image_background_size) ? $this->addon->settings->image_background_size : 'background-size: cover;';
		$image_alignment = (isset($this->addon->settings->image_alignment) && $this->addon->settings->image_alignment) ? $this->addon->settings->image_alignment : '';
		$text = (isset($this->addon->settings->text) && $this->addon->settings->text) ? $this->addon->settings->text : '';
		$button_text = (isset($this->addon->settings->button_text) && $this->addon->settings->button_text) ? $this->addon->settings->button_text : '';
		$button_url = (isset($this->addon->settings->button_url) && $this->addon->settings->button_url) ? $this->addon->settings->button_url : '';
		$button_classes = (isset($this->addon->settings->button_size) && $this->addon->settings->button_size) ? ' sppb-btn-' . $this->addon->settings->button_size : '';
		$button_classes .= (isset($this->addon->settings->button_type) && $this->addon->settings->button_type) ? ' sppb-btn-' . $this->addon->settings->button_type : '';
		$button_classes .= (isset($this->addon->settings->button_shape) && $this->addon->settings->button_shape) ? ' sppb-btn-' . $this->addon->settings->button_shape: ' sppb-btn-rounded';
		$button_classes .= (isset($this->addon->settings->button_appearance) && $this->addon->settings->button_appearance) ? ' sppb-btn-' . $this->addon->settings->button_appearance : '';
		$button_classes .= (isset($this->addon->settings->button_block) && $this->addon->settings->button_block) ? ' ' . $this->addon->settings->button_block : '';
		//Pixeden Icons
		$peicon_name = (isset($this->addon->settings->peicon_name) && $this->addon->settings->peicon_name) ? $this->addon->settings->peicon_name : '';
		$button_icon = (isset($this->addon->settings->button_icon) && $this->addon->settings->button_icon) ? $this->addon->settings->button_icon : '';
		$button_icon_position = (isset($this->addon->settings->button_icon_position) && $this->addon->settings->button_icon_position) ? $this->addon->settings->button_icon_position: 'left';
		$button_position = (isset($this->addon->settings->button_position) && $this->addon->settings->button_position) ? $this->addon->settings->button_position : '';
		$button_attribs = (isset($this->addon->settings->button_target) && $this->addon->settings->button_target) ? ' target="' . $this->addon->settings->button_target . '"' : '';
		$button_attribs .= (isset($this->addon->settings->button_url) && $this->addon->settings->button_url) ? ' href="' . $this->addon->settings->button_url . '"' : '';
		
		$title_margin_bottom !='' ? $button_top_margin = ' style="margin-top:' . ( (int) $title_margin_bottom - 10 ) . 'px;margin-bottom:' . ( (int) $title_margin_bottom - 10 ) . 'px;"' : $button_top_margin = ' style="margin-top:15px;margin-bottom:10px;"';
		

		if($button_icon_position == 'left') {	
			if ($peicon_name != '') {
				$button_text = ($peicon_name) ? '<i class="pe ' . $peicon_name . ' left"></i> ' . $button_text : $button_text;
			}else{
				$button_text = ($button_icon) ? '<i class="fa ' . $button_icon . ' left"></i> ' . $button_text : $button_text;
			}
		} else {
			if ($peicon_name != '') {
				$button_text = ($peicon_name) ? $button_text . ' <i class="pe ' . $peicon_name . ' right"></i>' : $button_text;
			}else{
				$button_text = ($button_icon) ? $button_text . ' <i class="fa ' . $button_icon . ' right"></i>' : $button_text;
			}
		}
        
		$button_output = '';
		
		if ($button_text != '') {
			$button_output = '<a'.$button_top_margin . $button_attribs . ' id="btn-'. $this->addon->id .'" class="sppb-btn' . $button_classes . '">' . $button_text . '</a>';
		}

		if($image_alignment=='left') {
			$content_class = ' col-xs-offset-0 col-sm-offset-6';
		} else {
			$content_class = '';
		}

		if($image && $text) {

			$output = '<div class="sppb-addon sppb-addon-image-content aligment-'. $image_alignment .' clearfix ' . $class . '">';
			
			// Image
			if(strpos($image, 'http://') !== false || strpos($image, 'https://') !== false){
				/* Lazyload for images with absolute URL */
				if($has_lazyload) {
					$output .= '<div style="background-image: url(data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==);background-size:'.$image_background_size.';background-repeat:no-repeat;" class="lazyload sppb-image-holder" data-bg="'. $image .'" data-expand="-5">';
				} else {
					$output .= '<div style="background-image: url(' . $image . ');background-size:'.$image_background_size.';background-repeat:no-repeat;" class="sppb-image-holder">';
				}
			} else {
				/* Lazyload for images for relative URL (local image) */
				if($has_lazyload) {
					$output .= '<div style="background-image: url(data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==);background-size:'.$image_background_size.';background-repeat:no-repeat;" class="lazyload sppb-image-holder" data-bg="'. JUri::root() . $image .'" data-expand="-5">';
				} else {
					$output .= '<div style="background-image: url(' . JURI::base(true) . '/' . $image . ');background-size:'.$image_background_size.';background-repeat:no-repeat;" class="sppb-image-holder">';
				}
			}
			$output .= '</div>';

			// Content
			$output .= '<div class="container-fluid">';
			$output .= '<div class="row-fluid">';

			// Important to have Bootstrap's col-sm- instead of SPPB's sppb-col-sm-, to fix issues with SPPB 3.x

			$output .= '<div class="col-xs-12 col-sm-6'. $content_class .'">';
			$output .= '<div class="sppb-content-holder">';
			$output .= ($title) ? '<'.$heading_selector.' class="sppb-image-content-title sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
			$output .= ($text) ? '<p class="sppb-image-content-text">' . $text . '</p>' : '';

			$output .= $button_output;

			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			$output .= '</div>';

			return $output;
		}

		return;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$css = '';

		$padding = (isset($this->addon->settings->content_padding)) ? SppagebuilderHelperSite::getPaddingMargin($this->addon->settings->content_padding, 'padding') : '';
		$padding_sm = (isset($this->addon->settings->content_padding_sm)) ? SppagebuilderHelperSite::getPaddingMargin($this->addon->settings->content_padding_sm, 'padding') : '';
		$padding_xs = (isset($this->addon->settings->content_padding_xs)) ? SppagebuilderHelperSite::getPaddingMargin($this->addon->settings->content_padding_xs, 'padding') : '';

		
		$css .= (!empty($padding)) ? $addon_id .' .sppb-addon-image-content .sppb-content-holder{'.$padding.'}' : '';
		$css .= (!empty($padding_sm)) ? '@media (min-width: 768px) and (max-width: 991px) {'.$addon_id.' .sppb-addon-image-content .sppb-content-holder{'.$padding_sm.'}}' : '';
		$css .= (!empty($padding_xs)) ? '@media (max-width: 767px) {'.$addon_id.' .sppb-addon-image-content .sppb-content-holder{'.$padding_xs.'}}' : '';

		$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $this->addon->settings, 'id' => 'btn-' . $this->addon->id));

		return $css;
	}

public static function getTemplate() {
		$output = '
		<#
			var content_class = "";
			if(data.image_alignment == "left") {
				content_class = " sppb-col-sm-offset-6";
			}

			var button_text = data.button_text;

			if(data.button_icon_position == "left") {
				if (data.peicon_name != "") {
					button_text = (data.peicon_name) ? \' <i class="pe \' + data.peicon_name + \' left"></i>\' + data.button_text : data.button_text;
				}else{
					button_text = (data.button_icon) ? \' <i class="fa \' + data.button_icon + \' left"></i>\' + data.button_text : data.button_text;
				}
			} else {
				if (data.peicon_name != "") {
					button_text = (data.peicon_name) ? data.button_text + \' <i class="pe \' + data.peicon_name + \' right"></i>\' : data.button_text;
				}else{
					button_text = (data.button_icon) ? data.button_text + \' <i class="fa \' + data.button_icon + \' right"></i>\' : data.button_text;
				}
			}

			var button_classes = "";

			if(data.button_size){
				button_classes = button_classes + " sppb-btn-" + data.button_size;
			}

			if(data.button_type){
				button_classes = button_classes + " sppb-btn-" + data.button_type;
			}

			if(data.button_shape){
				button_classes = button_classes + " sppb-btn-" + data.button_shape;
			} else {
				button_classes = button_classes + " sppb-btn-rounded";
			}

			if(data.button_appearance){
				button_classes = button_classes + " sppb-btn-" + data.button_appearance;
			}

			if(data.button_block){
				button_classes = button_classes + " " + data.button_block;
			}

			var button_fontstyle = data.button_fontstyle || "";

			var padding = "";
			var padding_sm = "";
			var padding_xs = "";
			if(data.content_padding){
				if(_.isObject(data.content_padding)){
					if(data.content_padding.md.trim() !== ""){
						padding = data.content_padding.md.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.content_padding.sm.trim() !== ""){
						padding_sm = data.content_padding.sm.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.content_padding.xs.trim() !== ""){
						padding_xs = data.content_padding.xs.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}
				}

			}
		#>
		<style type="text/css">
			#sppb-addon-{{ data.id }} .sppb-image-holder{
				<# if(data.image.indexOf("https://") == -1 && data.image.indexOf("https://") == -1){ #>
					background-image: url({{ pagebuilder_base + data.image }});
				<# } else { #>
					background-image: url({{ data.image }});
				<# } #>
			}
			#sppb-addon-{{ data.id }} .sppb-addon-image-content .sppb-content-holder{
				padding: {{ padding }};
			}
			#sppb-addon-{{ data.id }} #btn-{{ data.id }}.sppb-btn-{{ data.button_type }}{
				letter-spacing: {{ data.button_letterspace }};
				<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.md !== "undefined"){ #>
					margin-top: {{ data.button_margin_top.md }}px;
				<# } #>
				<# if(_.isArray(button_fontstyle)) { #>
					<# if(button_fontstyle.indexOf("underline") !== -1){ #>
						text-decoration: underline;
					<# } #>
					<# if(button_fontstyle.indexOf("uppercase") !== -1){ #>
						text-transform: uppercase;
					<# } #>
					<# if(button_fontstyle.indexOf("italic") !== -1){ #>
						font-style: italic;
					<# } #>
					<# if(button_fontstyle.indexOf("lighter") !== -1){ #>
						font-weight: lighter;
					<# } else if(button_fontstyle.indexOf("normal") !== -1){#>
						font-weight: normal;
					<# } else if(button_fontstyle.indexOf("bold") !== -1){#>
						font-weight: bold;
					<# } else if(button_fontstyle.indexOf("bolder") !== -1){#>
						font-weight: bolder;
					<# } #>
				<# } #>
			}

			<# if(data.button_type == "custom"){ #>
				#sppb-addon-{{ data.id }} #btn-{{ data.id }}.sppb-btn-custom{
					color: {{ data.button_color }};
					<# if(data.button_appearance == "outline"){ #>
						border-color: {{ data.button_background_color }}
					<# } else if(data.button_appearance == "3d"){ #>
						border-bottom-color: {{ data.button_background_color_hover }};
						background-color: {{ data.button_background_color }};
					<# } else if(data.button_appearance == "gradient"){ #>
						border: none;
						<# if(typeof data.button_background_gradient.type !== "undefined" && data.button_background_gradient.type == "radial"){ #>
							background-image: radial-gradient(at {{ data.button_background_gradient.radialPos || "center center"}}, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
						<# } else { #>
							background-image: linear-gradient({{ data.button_background_gradient.deg || 0}}deg, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
						<# } #>
					<# } else { #>
						background-color: {{ data.button_background_color }};
					<# } #>
				}

				#sppb-addon-{{ data.id }} #btn-{{ data.id }}.sppb-btn-custom:hover{
					color: {{ data.button_color_hover }};
					background-color: {{ data.button_background_color_hover }};
					<# if(data.appearance == "outline"){ #>
						border-color: {{ data.button_background_color_hover }};
					<# } else if(data.button_appearance == "gradient"){ #>
						<# if(typeof data.button_background_gradient_hover.type !== "undefined" && data.button_background_gradient_hover.type == "radial"){ #>
							background-image: radial-gradient(at {{ data.button_background_gradient_hover.radialPos || "center center"}}, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
						<# } else { #>
							background-image: linear-gradient({{ data.button_background_gradient_hover.deg || 0}}deg, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
						<# } #>
					<# } #>
				}
			<# } #>

			@media (min-width: 768px) and (max-width: 991px) {
				#sppb-addon-{{ data.id }} .sppb-addon-image-content .sppb-content-holder{
					padding: {{ padding_sm }};
				}
				#sppb-addon-{{ data.id }} #btn-{{ data.id }}.sppb-btn-{{ data.button_type }}{
					<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.sm !== "undefined"){ #>
						margin-top: {{ data.button_margin_top.sm }}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#sppb-addon-{{ data.id }} .sppb-addon-image-content .sppb-content-holder{
					padding: {{ padding_xs }};
				}
				#sppb-addon-{{ data.id }} #btn-{{ data.id }}.sppb-btn-{{ data.button_type }}{
					<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.xs !== "undefined"){ #>
						margin-top: {{ data.button_margin_top.xs }}px;
					<# } #>
				}
			}
		</style>
		
		
		<div class="sppb-addon sppb-addon-image-content aligment-{{ data.image_alignment }} clearfix {{ data.class }}">
			<div class="sppb-image-holder"></div>
			<div class="container-fluid">
				<div class="row-fluid">
					<# if(data.image_alignment == "left") { #>
						<div class="col-sm-6"></div>
					<# } #>
					
					<div class="col-sm-6 {{ content_class }}">
						<div class="sppb-content-holder">
                            <# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="sppb-image-content-title sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
                            <# if(data.text){ #><p id="addon-text-{{data.id}}" class="sppb-image-content-text sp-editable-content" data-id={{data.id}} data-fieldName="text">{{{ data.text }}}</p><# } #>
						    <# if(button_text){ #>
                                <a href=\'{{ data.button_url }}\' target="{{ data.button_target }}" id="btn-{{ data.id }}" class="sppb-btn {{ button_classes }}">{{{ button_text }}}</a>
                            <# } #>
						</div>
					</div>
				</div>
			</div>
		</div>
		';

		return $output;
	}

}
