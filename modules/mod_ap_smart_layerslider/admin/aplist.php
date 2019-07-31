<?php
/**
 * @package 	aplist.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');
/**
 * Create List. With the ability to show/hide sub-options.
 * Example xml:
 * <field
 * 	name="mod_ap_show_hide"
 * 	type="aplist"
 * 	default="1"
 * 	<option value="1" sub_fields="mod_yes_field_1,mod_yes_field_2">JYES</option>
 * 	<option value="0" sub_fields="">JNO</option>
 * </field>
 */
class JFormFieldAplist extends JFormFieldList {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Aplist';

	/**
	 * Active sub-fields.
	 * 
	 * @var		string
	 */
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
	protected function getInput() {

		JHTML::script('modules/mod_ap_portfolio/admin/js/apoptions.js');
		
	
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$on_change = ' onchange="';
		// Add new script
		$on_change .= ' ap_HideOptions(ap_subfield_' . $this->element['name'] . ');';
		$on_change .= "ap_ShowOptionsByControl('" . $this->element['name'] . "', ap_subfield_" . $this->element['name'] . "_data);";
		
		$on_change .= $this->element['onchange'] ? (string) $this->element['onchange'] : '';

		$on_change .= '"';

		
		$attr .= $on_change;

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		$this->onload_script();

		return implode($html);
	}

	/**
	 * Method to get the script onload
	 * 
	 * @return blank
	 */
	private function onload_script() {
		?>
		<script type="text/javascript">
			var ap_subfield_<?php echo $this->element['name']; ?> = "<?php echo implode(',', $this->sub_fields_list); ?>";
			var ap_subfield_<?php echo $this->element['name']; ?>_data = new Array();			
		<?php foreach ($this->sub_fields_list as $key => $value): ?>
					ap_subfield_<?php echo $this->element['name']; ?>_data["<?php echo $key; ?>"] = "<?php echo $value; ?>";
		<?php endforeach; ?>
				jQuery(window).load(function(){ 
					ap_HideOptions(ap_subfield_<?php echo $this->element['name']; ?>);
					ap_ShowOptions('<?php echo $this->active_sub_fields; ?>');
				});	

		</script>
		<?php
		return;
	}

	/**
	 * Override getOptions Method to get sub fields list.
	 *
	 * @return  array  The field option objects.
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
			$tmp = JHtml::_('select.option', (string) $option['value'], JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', ((string) $option['disabled'] == 'true')
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
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}

}