<?php
/**
 * @package 	apradio.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('radio');

/**
 * Create Radio List Button. With the ability to show/hide sub-options.
 * Example xml:
 * <field
 * 	name="mod_ap_show_hide"
 * 	type="apradio"
 * 	default="1"
 * 	<option value="1" sub_fields="mod_yes_field_1,mod_yes_field_2">JYES</option>
 * 	<option value="0" sub_fields="m">JNO</option>
 * </field>
 */
class JFormFieldApradio extends JFormFieldRadio {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Apradio';

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
		
		$doc = JFactory::getDocument();
        // css and js already loaded from apmod
		

		$html = parent::getInput();
		$this->onload_script();

		return $html;
	}

	/**
	 * Method to get the script onload
	 * 
	 * @return blank
	 */


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
			if ($option['value'] == $this->value) {
				$this->active_sub_fields = $sub_fields;
			}

			// Set some JavaScript option attributes.
			$onclick = !empty($option['onclick']) ? (string) $option['onclick'] : '';
			$tmp->class .= $this->element['name']; // Add class to sub fileds if not empty

			// Add default onclick
			$onclick .= 'ap_HideOptions(ap_subfield_' . $this->element['name'] . ');';
			$onclick .= 'ap_ShowOptions('.$sub_fields.');';

			$tmp->onclick = $onclick;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
		private function onload_script() {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			var ap_subfield_<?php echo $this->element['name']; ?> = "<?php echo implode(',', $this->sub_fields_list); ?>";
		        jQuery(window).load(function(){ 
				ap_HideOptions(ap_subfield_<?php echo $this->element['name']; ?>);
				ap_ShowOptions('<?php echo $this->active_sub_fields; ?>');  
			});
});
		</script>
		<?php
		return;
	}

}