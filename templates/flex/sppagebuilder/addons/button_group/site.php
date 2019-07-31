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

class SppagebuilderAddonButton_group extends SppagebuilderAddons{

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? ' ' . $this->addon->settings->class : '';
		$class .= (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? ' ' . $this->addon->settings->alignment : '';

		$output  = '<div class="sppb-addon sppb-addon-button-group' . $class . '">';
		$output .= '<div class="sppb-addon-content">';

		if(isset($this->addon->settings->sp_button_group_item) && count((array) $this->addon->settings->sp_button_group_item)){

			foreach ($this->addon->settings->sp_button_group_item as $key => $value) {
				if($value->title || $value->icon) {
					$class  = (isset($value->type) && $value->type) ? ' sppb-btn-' . $value->type : '';
					$class .= (isset($value->size) && $value->size) ? ' sppb-btn-' . $value->size : '';
					$class .= (isset($value->block) && $value->block) ? ' ' . $value->block : '';
					$class .= (isset($value->shape) && $value->shape) ? ' sppb-btn-' . $value->shape: ' sppb-btn-rounded';
					$class .= (isset($value->appearance) && $value->appearance) ? ' sppb-btn-' . $value->appearance : '';
					$attribs = (isset($value->target) && $value->target) ? ' target="' . $value->target . '"': '';
					$attribs .= (isset($value->url) && $value->url) ? ' href="' . $value->url . '"': '';
					$attribs .= ' id="btn-' . ($this->addon->id + $key) . '"';
					$text = (isset($value->title) && $value->title) ? $value->title: '';
					//Pixeden Icons
					$peicon_name = (isset($value->peicon_name) && $value->peicon_name) ? $value->peicon_name: '';
					$icon = (isset($value->icon) && $value->icon) ? $value->icon: '';
					$icon_position = (isset($value->icon_position) && $value->icon_position) ? $value->icon_position: 'left';

					if($icon_position == 'left') {
						if ($peicon_name) {
							$text = ($peicon_name) ? '<i class="pe ' . $peicon_name . '"></i> ' . $text : $text;
						}else{
							$text = ($icon) ? '<i class="fa ' . $icon . '"></i> ' . $text : $text;
						}	
					} else {
						if ($peicon_name) {
							$text = ($peicon_name) ? $text . ' <i style="margin-left:5px;margin-right:-1px;" class="pe ' . $peicon_name . '"></i>' : $text;
						}else{
							$text = ($icon) ? $text . ' <i style="margin-left:5px;margin-right:-1px;" class="fa ' . $icon . '"></i>' : $text;
						}
					}

					$output  .= '<a' . $attribs . ' class="sppb-btn ' . $class . '">' . $text . '</a>';
				}
			}
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;

	}

	public function css() {

		$addon_id = '#sppb-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$margin = (isset($this->addon->settings->margin) && $this->addon->settings->margin) ? $this->addon->settings->margin : '';
		$margin_sm = ((isset($this->addon->settings->margin_sm)) && $this->addon->settings->margin_sm) ? $this->addon->settings->margin_sm : '';
		$margin_xs = ((isset($this->addon->settings->margin_xs)) && $this->addon->settings->margin_xs) ? $this->addon->settings->margin_xs : '';

		$css = '';
		if($margin) {
			$css .= $addon_id . ' .sppb-addon-content {';
			$css .= 'margin: -' . (int) $margin . 'px;';
			$css .= '}';

			$css .= $addon_id . ' .sppb-addon-content .sppb-btn {';
			$css .= 'margin: ' . (int) $margin . 'px;';
			$css .= '}';
		}

		if($margin_sm){
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
				$css .= $addon_id . ' .sppb-addon-content {';
					$css .= 'margin: -' . (int) $margin_sm . 'px;';
				$css .= '}';

				$css .= $addon_id . ' .sppb-addon-content .sppb-btn {';
					$css .= 'margin: ' . (int) $margin_sm . 'px;';
				$css .= '}';
			$css .= '}';
		}

		if($margin_xs){
			$css .= '@media (max-width: 767px) {';
				$css .= $addon_id . ' .sppb-addon-content {';
					$css .= 'margin: -' . (int) $margin_xs . 'px;';
				$css .= '}';

				$css .= $addon_id . ' .sppb-addon-content .sppb-btn {';
					$css .= 'margin: ' . (int) $margin_xs . 'px;';
				$css .= '}';
			$css .= '}';
		}

		// Buttons style
		if(isset($this->addon->settings->sp_button_group_item) && count((array) $this->addon->settings->sp_button_group_item)){
			foreach ($this->addon->settings->sp_button_group_item as $key => $value) {
				if($value->title) {
					$css_path = new JLayoutFile('addon.css.button', $layout_path);

					$options = new stdClass;
					$options->button_type = (isset($value->type) && $value->type) ? $value->type : '';
					$options->button_appearance = (isset($value->appearance) && $value->appearance) ? $value->appearance : '';
					$options->button_color = (isset($value->color) && $value->color) ? $value->color : '';
					$options->button_color_hover = (isset($value->color_hover) && $value->color_hover) ? $value->color_hover : '';
					$options->button_background_color = (isset($value->background_color) && $value->background_color) ? $value->background_color : '';
					$options->button_background_color_hover = (isset($value->background_color_hover) && $value->background_color_hover) ? $value->background_color_hover : '';
					$options->button_padding = (isset($value->button_padding) && $value->button_padding) ? $value->button_padding : '';
					$options->button_padding_sm = (isset($value->button_padding_sm) && $value->button_padding_sm) ? $value->button_padding_sm : '';
					$options->button_padding_xs = (isset($value->button_padding_xs) && $value->button_padding_xs) ? $value->button_padding_xs : '';
					$options->button_font_family = (isset($value->font_family) && $value->font_family) ? $value->font_family : '';
					$options->button_font_family_selector = (isset($value->font_family_selector) && $value->font_family_selector) ? $value->font_family_selector : '';
					$options->button_fontstyle = (isset($value->fontstyle) && $value->fontstyle) ? $value->fontstyle : '';
					$options->button_font_style = (isset($value->font_style) && $value->font_style) ? $value->font_style : '';
					$options->button_letterspace = (isset($value->letterspace) && $value->letterspace) ? $value->letterspace : '';
					$options->button_background_gradient = (isset($value->background_gradient) && $value->background_gradient) ? $value->background_gradient : new stdClass();
					$options->button_background_gradient_hover = (isset($value->background_gradient_hover) && $value->background_gradient_hover) ? $value->background_gradient_hover : new stdClass();

					$selector_css = new JLayoutFile('addon.css.selector', $layout_path);
					$css .= $selector_css->render(
					  array(
					    'options'=>$value,
					    'addon_id'=>$addon_id,
					    'selector'=>'#btn-' . ($this->addon->id + $key)
					  )
					);

					$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . ($this->addon->id + $key) ));
				}
			}
		}

		return $css;
	}
}
