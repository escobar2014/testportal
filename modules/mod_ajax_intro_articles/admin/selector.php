<?php
/**
 * @package 	themeselect.php
 * @author		Aplikko
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Form Field class for the Joomla Platform.
 * Provides radio button inputs
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/command.radio.html#command.radio
 * @since       11.1
 */
class JFormFieldSelector extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Selector';

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	
	protected function getInput(){

		$doc = JFactory::getDocument();
	
		$moduleName = basename(dirname(__DIR__));
		$doc->addStylesheet(JURI::root(true).'/modules/'.$moduleName.'/admin/css/admin_style.css');
		$doc->addStylesheet('//netdna.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');
		
		$doc->addStylesheet('https://fonts.googleapis.com/css?family=Muli');
		$doc->addStylesheet('https://fonts.googleapis.com/css?family=Nunito+Sans');
	
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
			$icon = !empty($option->icon) ? ' <i class="' . $option->icon . '"></i>' : '';
			$btn = !empty($option->btn) ? ' '. $option->btn : '';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
	
			$onclick    = !empty($option->onclick) ? 'onclick="' . $option->onclick . '"' : '';
			$onchange   = !empty($option->onchange) ? 'onchange="' . $option->onchange . '"' : '';


			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>';
			$html[] = '<label for="' . $this->id . $i . '"'.$class.'>'
				. '<div class="selector selector-'.$this->id.' btn'.$btn.'">'. $icon . JText::_($option->text) .'</div>'
				. '</label>';
		}
		// End the radio field output.
		$html[] = '</fieldset>';
		?>
        
		<script type="text/javascript">
			// Select (radios)
			jQuery(document).ready(function(){
				jQuery("fieldset#<?php echo $this->id; ?> input[id^='<?php echo $this->id; ?>']").hide();//hide default radios
				var checkeditem = jQuery("fieldset#<?php echo $this->id; ?> input[id^='<?php echo $this->id; ?>']:checked").next().children();
				checkeditem.addClass("highlight");
				jQuery("fieldset#<?php echo $this->id; ?> .selector-<?php echo $this->id; ?>").click(function(){
				jQuery("fieldset#<?php echo $this->id; ?> .selector-<?php echo $this->id; ?>").removeClass("highlight");	
				jQuery(this).toggleClass("highlight").show();
				});
			});
		</script>
		<?php	
		
		return implode($html);
	}
	
	protected function getOptions() {
		$options = array();
		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);
			
			// Include Icons in Options
			$tmp->btn = (string) $option['btn'];
			
			// Include Icons in Options
			$tmp->icon = (string) $option['icon'];

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
	
	public function renderField($options = array()) {
		$datashowon = ' data-showon=\'' . json_encode(JFormHelper::parseShowOnConditions($this->showon, $this->formControl, $this->group)) . '\'';
	return '<div class="control-group '.$this->element['name'].'"'.$datashowon.'>'
		. '<div class="control-label selector-label">' . $this->getLabel() . '</div>'
		. '<div class="controls">' . $this->getInput() . '</div>'
		. '</div>';
 	}
}
