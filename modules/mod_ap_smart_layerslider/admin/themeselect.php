<?php
/**
 * @package 	themeselect.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

class JFormFieldThemeselect extends JFormField {
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Themeselect';
	
	protected $active_sub_fields = '';

	/**
	 * List of all sub-fields
	 * 
	 * @var		string
	 */
	protected $sub_fields_list = array();

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	/**
	* Method to get the label for a field input.
	* @return  string  The form field label.
	*/
	protected function getLabel() {
    $html = array();
           
    $label = $this->element['label'];
	$theme = $this->element['name'];
	$label = $this->translateLabel ? JText::_($label) : $label;     
    $class = $this->element['class']; 
	$class = $this->translateLabel ? JText::_($class) : $class;
	
	$html[] = '<label class="'.$theme.' hasTooltip" title="'.JText::_($this->element['description']).'">'
				. $label
				. '<span class="fa fa-check-square"></span>'
				. '</label>';	

    return implode('',$html);
	}

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	
	protected function getInput(){

		$doc = JFactory::getDocument();
		
		$html = array();
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : ' class="radio"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option) {

			$theme = htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8');
			$thumbpath = JURI::root(true).'/modules/'.basename(dirname(__DIR__)).'/admin/images/themes/'.$theme.'.png';

			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. $theme . '"' . $checked . $class . $onclick . $disabled . '/>';
			$html[] = '<label for="' . $this->id . $i . '" class="'.$class.'">'
				. '<div class="select hasTooltip" title="'.ucfirst($this->element['name']).': '.JText::_('Style').' '.JText::_(ucfirst($theme)).'"><img src="'.$thumbpath.'" /><p class="desc">'.JText::_('Style').' <span class="nmbr">'.$theme.'</span></p>'
				. '</div>'
				. '</label>';
		}

		// End the radio field output.
		$html[] = '</fieldset>';
		?>
        
		<script type="text/javascript">
		
			var ap_subfield_<?php echo $this->element['name']; ?> = "<?php echo implode(',', $this->sub_fields_list); ?>";
			
			// Select (radios)
			jQuery(document).ready(function(){
				jQuery("input[id^='<?php echo $this->id; ?>']").css({"visibility":"hidden","display":"none"});//hide default radios
				var checkeditem = jQuery("input[id^='<?php echo $this->id; ?>']:checked").next().children();
				checkeditem.addClass("highlight");
				jQuery(".select").click(function(){
				jQuery(".select").removeClass("highlight");
				jQuery(".marker").fadeOut(300, function() { jQuery(this).remove(); });
				jQuery(this).toggleClass("highlight").fadeIn(300);
				});
				
				ap_HideOptions(ap_subfield_<?php echo $this->element['name']; ?>);
				ap_ShowOptions('<?php echo $this->active_sub_fields; ?>');  
				
			});

		</script>
		<?php	
		return implode($html);
	}
			
	/**
	 * Method to get the field options for radio buttons.
	 * @return  array  The field option objects.
	 * @since   11.1
	 */
	protected function getOptions() {

		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', (string) $option['value'], trim((string) $option), 'value', 'text', ((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Get sub_fields.
			$sub_fields = str_replace("\n", '', trim($option['sub_fields']));
			if (!empty($sub_fields)) {
				$this->sub_fields_list = array_merge($this->sub_fields_list, array((string) $option['value'] => $sub_fields));
			}

			// Check if it's selected
			if ($option["value"] == $this->value) {
				$this->active_sub_fields = $sub_fields;
			}

			// Set some JavaScript option attributes.
			$onclick = !empty($option['onclick']) ? (string) $option['onclick'] : '';
			$tmp->class .= $this->element['name']; // Add class to sub fileds if not empty

			// Add default onclick
			$onclick .= 'ap_HideOptions(ap_subfield_' . $this->element['name'] . ');';
			$onclick .= "ap_ShowOptions('$sub_fields');";
			

			$tmp->onclick = $onclick;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
		
	public function renderField($options = array()) {
	return '<div class="'.$this->element['name'].'">'
		. '<div class="control-label span12">' . $this->getLabel() . '</div>'
		. '<div class="controls">' . $this->getInput() . '</div>'
		. '</div>';
 	}
}
