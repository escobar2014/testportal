<?php
/**
 * @package 	k2category.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.fields.list');

class JFormFieldK2Category extends JFormFieldList {

	/**
	 * The form field type.
	 * 
	 * @var string
	 */
	public $type = 'K2Category';

	/**
	 * Constuctor
	 * 
	 * @param array $form
	 */
	public function __construct($form = array()) {
		parent::__construct($form);
	}

	/**
	 * Custom Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getInput() {
		return parent::getInput();
	}

	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @see JFormFieldCategory::getOptions()
	 *
	 * @return  array    The field option objects.
	 * 
	 */
 
	protected function getOptions() {

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('c.*')
				->from('#__k2_categories AS c')
				->where('trash = 0')
				->order('parent')
				->order('ordering');
		$db->setQuery($query);
		$doc = JFactory::getDocument();	
	
		
		// if K2 is installed
		if (JFile::exists(JPATH_SITE.'/components/com_k2/k2.php')) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('div.control-group.grid .controls #apListImage').hide().fadeIn(200);
			
			 var disablefields = jQuery('div.control-group:has([id="jform_params_count"]), div.control-group:has([id="jform_params_sort_order_field"]), div.control-group:has([id="jform_params_sort_order"])').find('input, div, label');
			jQuery('div.control-group .controls fieldset label[for^="jform_params_display_form').on('click', function () {
				 jQuery(disablefields).hide().fadeIn(300); 	
			});
			jQuery('div.control-group .controls fieldset label[for="jform_params_display_form2"]').on('click', function () {
				jQuery(disablefields).hide();
				jQuery('div.control-group:has(#apListImage) .controls #apListImage').hide().fadeIn(300);
				jQuery('div.control-group:has(#apListImage) .controls #apListImage #apSort').hide().fadeIn(100);
			});	
		});
		</script>
		<?php	

		try {
			$rows = $db->loadObjectList();
			$children = array();
			if (count($rows)) {
				foreach ($rows as $k => $v) {
					$v->title = $v->name;
					$v->parent_id = $v->parent;
					$pt = $v->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $v);
					$children[$pt] = $list;
				}

				$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
				$options = array();
				foreach ($list as $item) {
					$item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
					$options[] = JHTML::_('select.option', $item->id, ' ' . $item->treename);
				}
				$options = array_merge(parent::getOptions(), $options);

				return $options;
			}
			return array();
		} catch (Exception $e) {
			$e->getMessage();
		}
		return array();

		// if K2 not installed
		} else {
		?>
		<script type="text/javascript">
		    jQuery(document).ready(function(){
				jQuery('div.control-group:has([id="<?php echo $this->id; ?>"]) .control-label, div.control-group:has([id="<?php echo $this->id; ?>"]) .controls').hide();
				jQuery('div.control-group:has([id="<?php echo $this->id; ?>"])').prepend('<p class="error"><?php echo JText::_('APSL_K2_CATEGORY_ERROR');?></p>');
				jQuery('div.control-group:has([id="<?php echo $this->id; ?>"]) p.error').hide();
				jQuery('div.control-group:has(#apListImage) .controls #apListImage').hide().fadeIn(200);
	
	        var disablefields = jQuery('div.control-group:has([id="jform_params_count"]), div.control-group:has([id="jform_params_sort_order_field"]), div.control-group:has([id="jform_params_sort_order"])').find('input, div, label');
			jQuery(disablefields).tooltip('hide');
			
			 jQuery('div.control-group .controls fieldset#jform_params_display_form label').filter(':eq(0)').on('click', function () {
				jQuery(disablefields).removeAttr('disabled').removeClass('disabled').hide().fadeIn(300).tooltip();
				jQuery('div.control-group:has([id="jform_params_count"]) .controls .input-append .add-on').show(); 	
			 });	

			jQuery('div.control-group .controls fieldset#jform_params_display_form label').filter(':eq(1)').on('click', function () {
			   jQuery(disablefields).attr('disabled', true).addClass('disabled').tooltip('destroy');
			   jQuery('div.control-group:has(select[id="<?php echo $this->id; ?>"]) p.error').hide().fadeIn(300);
			   jQuery('div.control-group:has([id="jform_params_count"]) .controls .input-append .add-on').hide();
			});
			
			jQuery('div.control-group .controls fieldset#jform_params_display_form label').filter(':eq(2)').on('click', function () {
				jQuery(disablefields).removeAttr('disabled').removeClass('disabled').hide().fadeIn(300); 
			    jQuery('div.control-group:has(#apListImage) .controls #apListImage').hide().fadeIn(200);
				jQuery('div.control-group:has(#apListImage) .controls #apListImage #apSort').hide().fadeIn(100);
				
			});
				
			});
		</script>
		<?php	
		}
	}	
}