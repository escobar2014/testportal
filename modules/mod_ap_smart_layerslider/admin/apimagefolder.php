<?php
/**
 * @package 	apimagefolder.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2018 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

if (isset($_REQUEST['apaction'])){
	if (!defined('_JEXEC')) {
    define('_JEXEC', 1);}
	
	$path = dirname(dirname(dirname(dirname(__FILE__))));
    if (!defined('JPATH_BASE'))
    	define('JPATH_BASE', $path);
   
    require_once JPATH_BASE . '/includes/defines.php';
    require_once JPATH_BASE . '/includes/framework.php';
    
    // Mark afterLoad in the profiler.
	JDEBUG ? $_PROFILER->mark('afterLoad') : null;
	
	// Instantiate the application.
	$app = JFactory::getApplication('site');

	// Initialise the application.
	$app->initialise();

	$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : false;
	
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	jimport('joomla.application.module.helper');
	
	class ApImageFolderAction{
		var $moduleName = '';
		
		function __construct(){
			$this->moduleName = basename(dirname(__DIR__));
		}
		
		private function basePath(){
			if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI'])) {
				// PHP-CGI on Apache with "cgi.fix_pathinfo = 0"
				// We shouldn't have user-supplied PATH_INFO in PHP_SELF in this case
				// because PHP will not work with PATH_INFO at all.
				$script_name = $_SERVER['PHP_SELF'];
			} else {
				// Others
				$script_name = $_SERVER['SCRIPT_NAME'];
			}

			return rtrim(dirname(dirname(dirname(dirname($script_name)))), '/\\');
		}
		
		private function getModule($mid){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params');
			$query->from('#__modules AS m');
			$query->where('m.id = '.$mid);
			$db->setQuery($query);
			$module = $db->loadObject ();
			return $module;
		}
		
		function getListImage(){
			$input = JFactory::getApplication()->input;
			$folder = $input->getString('folder');
			$mid = $input->getInt('mid');
			$fieldname = $input->getString('fieldname');
			$imageList = array();
			$success = false;
			$path = JPath::clean(JPATH_ROOT . '/' . $folder);
			
			$module = $this->getModule($mid);
			$params = new JRegistry();
			$paramString = isset($module->params) ? $module->params : '';
			$params->loadString($paramString);
			$imagesCurr = json_decode($params->get($fieldname.'.images'),true);
			$folderCurr = $params->get($fieldname.'.folder');
			if (JFolder::exists($path)) {
				$files = JFolder::files($path);
				$i = 0;
				foreach ($files as $file) {
					if (is_file($path.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html'){
						$ext = JFile::getExt($file);
						switch ($ext) {
							// Image
							case 'jpg':
							case 'png':
							case 'gif':
							case 'xcf':
							case 'odg':
							case 'bmp':
							case 'jpeg':
							case 'JPG':
							case 'PNG':
							case 'GIF':
							case 'ico':
								$image = $this->basePath().'/'.$folder . '/' . $file;
								$tmp = array();
								$nameArr = explode('.',$file);
								$name = $nameArr[0];
								$tmp['image'] = $file;
								$tmp['title'] = '';
								$tmp['caption'] = '';
								$tmp['description'] = '';
								$tmp['imagesrc'] = $image;
								$imageList[$name] = $tmp;
								break;
						}
					}
				$i++;
				}
			}
			$html = '';
			if (count($imageList)){
				$success = true;
				$imgArr = array();
				$flag = false;
				if (($folderCurr == $folder) && is_array($imagesCurr)){
					$flag = true;
					foreach ($imagesCurr as $k=>$v){
						$v['key'] = $k;
						if (isset($v['position'])){
							$imgArr[$v['position']] = $v;
						}
					}
				
				}
				ksort($imgArr);
				$i=0;
				//var_dump($imageList);
				foreach ($imgArr as $k=>$img){
					if (JFile::exists(JPATH_ROOT.'/'.$folder.'/'.$img['image'])){
						
						$nameArr = explode('.',$img['image']);
						//$key = 
						$html .= '<div class="ap-img brick small">
							<div class="brick-image">
							<img data-image="'.$img['image'].'" data-name="'.$nameArr[0].'" data-description="'.(isset($img['description']) ?  htmlspecialchars($img['description']) :'' ).'" data-title="'.(isset($img['title']) ? $img['title'] :'' ).'" data-caption="'.(isset($img['caption']) ? $img['caption'] :'' ).'" data-imagesrc="'.$img['imagesrc'].'" src="'.$img['imagesrc'].'">
							</div>
							<div class="ap-img-btn">
								<a class="edit" href="javascript:void(0)" onclick="apModal('.$i.')"><i class="fa fa-pencil-square-o"></i>Edit</a><a class="delete" href="javascript:void(0)" onclick="apDelete(\''.$img['image'].'\',this)"><i class="fa fa-times-circle"></i>Delete</a>
							</div>
						  </div>';
						if (isset($imageList[$img['key']]))
							unset($imageList[$img['key']]);
					}
					$i++;
				}
				foreach ($imageList as $k=>$img){
					if (JFile::exists(JPATH_ROOT.'/'.$folder.'/'.$img['image'])){
						$nameArr = explode('.',$img['image']);
						$html .= '<div class="ap-img brick small">
							<div class="brick-image">
							<img data-image="'.$img['image'].'" data-name="'.$nameArr[0].'" data-description="'.($flag && isset($imagesCurr[$k]['description']) ? htmlspecialchars($imagesCurr[$k]['description']) :'' ).'" data-title="'.($flag && isset($imagesCurr[$k]['title']) ? $imagesCurr[$k]['title'] :'' ).'" data-caption="'.($flag && isset($imagesCurr[$k]['caption']) ? $imagesCurr[$k]['caption'] :'' ).'"  data-imagesrc="'.$img['imagesrc'].'" src="'.$img['imagesrc'].'">
							</div>		
							<div class="ap-img-btn">
								<a class="edit" href="javascript:void(0)" onclick="apModal('.$i.')"><i class="fa fa-pencil-square-o"></i>Edit</a><a class="delete" href="javascript:void(0)" onclick="apDelete(\''.$img['image'].'\',this)"><i class="fa fa-times-circle" title="Delete"></i>Delete</a>
							</div>
						  </div>';
					}
					$i++;
				}
			}
			$return = array('imageHtml'=>$html,'success'=>$success);
			echo json_encode($return);
		}
		
		function deleteImage(){
			$input = JFactory::getApplication()->input;
			$success = false;
			$folder = $input->getString('folder');
			$image = $input->getString('image');
			$fullPath = JPATH_ROOT.'/'.$folder.'/'.$image;
			if (JFile::exists($fullPath)){
				if (JFile::delete($fullPath))$success = true;
			}
			$return = array('success'=>$success);
			echo json_encode($return);
		}
	}
	
	if ($task){
		$apAction = new ApImageFolderAction();	
		$apAction->$task();
	}
	
	
	exit();
}
jimport('joomla.filesystem.folder');


class JFormFieldApImageFolder extends JFormField {
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'ApImageFolder';
	
	/**
	 * The image config
	 */
	private $config = '';
	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   JForm  $form  The form to attach to the form field object.
	 *
	 * @since   11.1
	 */
	public function __construct($form = null){
		parent::__construct($form);
	}
	/**
	 * Method add script to document.
	 */
	private function init(){
			
		$params = new JRegistry();
		$params->loadObject($this->form->getValue('params'));
		$this->config = $params->get($this->fieldname.'.images');
		$uri = str_replace("\\","/", str_replace(JPATH_SITE, JURI::root(true), dirname(__FILE__) ));
		
		$doc = JFactory::getDocument();
	
		// Gridly
		$doc->addScript($uri.'/js/jquery.gridly.packed.js');
		$doc->addStyleSheet($uri.'/css/jquery.gridly.css');
		
		// Uploader
		$doc->addStyleSheet($uri.'/apuploader/upload/css/jquery.fileupload-ui.css');
		$doc->addScript($uri.'/apuploader/upload/js/vendor/jquery.ui.widget.js');
		$doc->addScript($uri.'/apuploader/upload/js/jquery.iframe-transport.js');
		$doc->addScript($uri.'/apuploader/upload/js/jquery.fileupload.js');
		
		$doc->addScriptDeclaration('
		jQuery(document).ready(function(){ 
			jQuery(".hasTooltip").tooltip();
		});
		');
			
		$doc->addScriptDeclaration('
		var AP_IMAGE_FOLDER_ACTION = "'.$uri.'/apimagefolder.php?apaction=folders";
		var AP_IMAGE_ID = "'.JFactory::getApplication()->input->getInt('id').'";
		var AP_IMAGE_FIELDNAME = "'.$this->fieldname.'";
		');
		
		$doc->addScriptDeclaration('
		jQuery(document).ready(function(){ 
			apListImages();
			var form = document.adminForm;
			if(!form){
				return false;
			}
			var onsubmit = form.onsubmit;
			form.onsubmit = function(e){
				apUpdateImages();
				if(jQuery.isFunction(onsubmit)){
					onsubmit();
				}
			};
		});
		function apListImages(){
			var folder = jQuery("#'.$this->id.'").val();
			AP_PATH = folder;
			if(folder == ""){
				alert("Folder path required");
				return;
			}
			jQuery("#apListImage #apSort").html("<div id=\"loader\"><img src=\"'.str_replace("\\","/", str_replace(JPATH_SITE, JURI::root(true), dirname(dirname(__FILE__)) )).'/admin/images/loader.gif\" width=\"42\" height=\"42\" /></div>");
			
			jQuery.post(AP_IMAGE_FOLDER_ACTION,{
					task:"getListImage",
					folder:folder,
					mid:AP_IMAGE_ID,
					fieldname:AP_IMAGE_FIELDNAME
				},function(res){
					if(res.success){
						jQuery("#apSort").html(res.imageHtml);
						jQuery(".hasTooltip").tooltip();
						return jQuery("#apSort").gridly({selector:".ap-img", "responsive": true, base: 30, gutter: 21,  columns:10});
					}else{
						jQuery("#apListImage #apSort").html("<div class=\"no-item-image\"><div class=\"no-image\"><div class=\"no-image-text\">NO IMAGES IN FOLDER <i class=\"fa fa-folder-open\"></i></div></div></div>");
						return ;	
					}
				},"json");
			
		};

		function apUpdateImages(){
			var images = jQuery("#apListImage").find("img");
			var config = {};
			images.each (function(index,element){
				var $this = jQuery(this),
					name = $this.data("name"),
					position = $this.closest(".ap-img").data("position"),
					item = {};
				$this.data("position",position);
				for (var d in $this.data()) {
					item[d] = $this.data(d);
				};
				if (Object.keys(item).length) config[name] = item;
			});
			jQuery("#'.$this->fieldname.'_images'.'").val(JSON.stringify(config));
		}
		function apDelete(image,element){
			if(confirm("'.JText::_("AP_DELETE_CONFIRMATION").'")){
				var folder = jQuery("#'. $this->id.'").val();
				jQuery.post(AP_IMAGE_FOLDER_ACTION,
				{
					task:"deleteImage"
					,folder:folder
					,image:image
				},function(res){
					if(res.success){
						jQuery(element).closest(".brick").remove();
						return jQuery("#apSort").gridly({selector:".ap-img", "responsive": true, "action": "layout"});
					}
				},"json");
			}
		}
		function apModal(id){
			var images = jQuery("#apListImage").find("img");
			var image = images.get(id);
			jQuery("#apTitle").val(jQuery(image).data("title"));
			jQuery("#apCaption").val(jQuery(image).data("caption"));
			jQuery("#apDescription").val(jQuery(image).data("description"));
			jQuery("#apModal").data("imageId",id);
			jQuery("#apModal").modal("show");
			
		}
		function apUpdateImgData(){
			var imageId = jQuery("#apModal").data("imageId");
			var images = jQuery("#apListImage").find("img");
			var title = jQuery("#apTitle").val(),
				caption = jQuery("#apCaption").val(),
				description = jQuery("#apDescription").val();
			var image = images.get(imageId);
			
			jQuery(image).data("caption",caption).data("title",title).data("description",description);
			
			jQuery("#apModal").find("input,textarea").each(function(){
				jQuery(this).val("");
			});
			jQuery("#apModal").modal("hide");
		}
		
		');
	}
	//Empty Label
    protected function getLabel(){return;}
	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getInput() {
		
		$modulePath = JURI::root().'/modules/'.basename(dirname(__DIR__));		
		$this->init();

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
		$attr .= $this->required ? ' required="required" aria-required="true"' : '';

		// Initialize JavaScript field attributes.
		$attr .= ' onchange="apListImages();"' ;

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a regular list.
		$html[]	= '<div class="control-label-clone"><label class="span12">'.JText::_("APSL_PATH_TO_FOLDER_LABEL").'</label></div>';
		$html[] = JHtml::_('select.genericlist', $options, $this->name.'[folder]', trim($attr), 'value', 'text', $this->value, $this->id);
		$html[] = '<span class="add-on" data-trigger="hover" data-toggle="popover" data-placement="right" data-content="'.JText::_($this->element['data-content']).'" title="'.JText::_($this->element['title']).'">'.JText::_($this->element['append']).'</span>';
		$html[]	= '<div id="apListImage"><div id="apSort" class="gridly"></div></div>';
		$html[]	= '<input id="'.$this->fieldname.'_images'.'" name="'.$this->name.'[images]" type="hidden" value="" />';
		$html[] = $this->createModal();

		$html[] = '<input type="button" id="apGetImages" value="' . JText::_("Get images") . '" style="display: none;" /><br /><div id="apSort" class="gridly"></div><div id=\'img-element-data-form\' style=\'display: none;\'></div>';
		$html[] = '<p class="upload_images">'.JText::_("UPLOAD_IMAGES_LABEL").'</p>';
		$html[] = '<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="fileinput-button btn-block">
				
				<img class="upload_folder" src="'.$modulePath.'/admin/apuploader/upload/img/open_folder-upload.png" />
								
				<span class="select-files">Drag and drop files here or click to select</span>
				<!-- The file input field used as target for the file upload widget -->
				<input id="fileupload" type="file" name="files[]" multiple>
			</span>
			<!-- The global progress bar -->
			<div id="progress" class="progress progress-success progress-striped active">
				<div class="bar"></div>
			</div>
			<!-- The container for the uploaded files -->
			<div id="files" class="files"></div>
			<script type="text/javascript">
				jQuery(document).ready(function() {	
					jQuery("#fileupload").fileupload({						
						url: location.href + "&apuploader=images&path="+AP_PATH+"&command=uploadImages",
						dataType: "json",
						done: function (e, data) {
							apListImages();
							jQuery("#progress .bar").css("width", 0 + "%").hide().fadeIn(200);	
						},
						progressall: function (e, data) {
							var progress = parseInt(data.loaded / data.total * 100, 10);	
							jQuery("#progress .bar").css("width", progress + "%");
						}	
					});
					jQuery("#fileupload").bind("fileuploadchange", function (e, data) { 
						jQuery("#fileupload").fileupload({
							url: location.href + "&apuploader=images&path="+AP_PATH+"&command=uploadImages"});
					});
				});
		</script>';
		
		return implode($html);
	}
	
	private function createModal(){
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration('
		jQuery(document).ready(function(){ 
			jQuery(".pophelper").popover({trigger:"hover"});
		});
		');
		$html = '
			<div id="apModal" class="modal hide fade">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">'.JText::_("EDIT_SLIDE").'</h3>
				</div>
				<div class="modal-body">
					<div class="control-group">
						<label class="control-label-clone" for="apTitle"><div class="pophelper" for="apDescription" data-content="'.JText::_("AP_TITLE_POPUP").'" data-placement="right">'.JText::_("AP_TITLE").' <i class="fa fa-edit" data-placement="right"></i></div></label>
						<div class="controls">
							<input type="text" id="apTitle" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label-clone" for="apCaption"><div class="pophelper" for="apDescription" data-content="'.JText::_("AP_CAPTION_POPUP").'" data-placement="right">'.JText::_("AP_CAPTION").' <i class="fa fa-edit tip"></i></div></label>
						<div class="controls">
							<input type="text" id="apCaption" />
						</div>
					</div>
					<div class="description-group">
						<div class="control-group">
							<label class="control-label-clone" for="apDescription" data-content="'.JText::_("AP_DESCRIPTION_POPUP").'" data-placement="top">
							<div class="pophelper" for="apDescription" data-content="'.JText::_("AP_DESCRIPTION_POPUP").'" data-placement="right">'.JText::_("AP_DESCRIPTION").' <i class="fa fa-edit"></i></div>
							<hr/>			
							<p class="helper">'.JText::_("AP_MODAL_EXAMPLE_TXT").'</p>
							</label>
							<div class="controls">
								<textarea id="apDescription" cols="80" rows="18"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-custom" onclick="apUpdateImgData()" type="button" >'.JText::_("AP_OK").'<i class="fa fa-check"></i></button>
				</div>
			</div>
		';
		return $html;
	}
	
	/**
	 * Method to get the field options. 
	 *
	 * @return  array  The field option objects.
	 *
	 */
	protected function getOptions(){
		$options = array();

		// Initialize some field attributes.
		$filter = (string) $this->element['filter'];
		$exclude = (string) $this->element['exclude'];

		// Get the path in which to search for file options.
		$path = (string) $this->element['directory'];
		if (!is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Get a list of folders in the search path with the given filter.
		//$folders = JFolder::folders($path, $filter);
		$listFolers = self::listFolderTree($path,$filter,100);

		// Build the options list from the list of folders.
		if (is_array($listFolers)){
			$children = array();
			foreach ($listFolers as $k => $folder) {
					if ($exclude)
					{
						if (preg_match(chr(1) . $exclude . chr(1), $folder))
						{
							continue;
						}
					}
					$folder = (object) $folder;
					$folder->title = $folder->name;
					$folder->parent_id = $folder->parent;
					$pt = $folder->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $folder);
					$children[$pt] = $list;
				}
		
			$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
			$options = array();
			foreach ($list as $item) {
				$item->treename = JString::str_ireplace('&#160;', '- ', JString::str_ireplace('&#160;&#160;', '&#160;', $item->treename));
				$options[] = JHTML::_('select.option',str_replace(DIRECTORY_SEPARATOR,'/',trim($item->relname,DIRECTORY_SEPARATOR)), ' ' . $item->treename);
				
			}
		}
		return $options;
	}
	
	/**
	 * Lists folder in format suitable for tree display.
	 *
	 * @param   string   $path      The path of the folder to read.
	 * @param   string   $filter    A filter for folder names.
	 * @param   integer  $maxLevel  The maximum number of levels to recursively read, defaults to three.
	 * @param   integer  $level     The current level, optional.
	 * @param   integer  $parent    Unique identifier of the parent folder, if any.
	 *
	 * @return  array  Folders in the given folder.
	 *
	 */
	public static function listFolderTree($path, $filter, $maxLevel = 100, $level = 1, $parent = 1){
		$dirs = array();
		
		if ($level == 1){
			$fullName = JPath::clean($path);
			$dirs[] = array('id' => 1, 'parent' => 0, 'name' => basename($path), 'fullname' => $fullName,
					'relname' => str_replace(JPATH_ROOT, '', $fullName));
			$GLOBALS['_ap_folder_tree_index'] = 1;
		}
		if ($level < $maxLevel){
			$folders =JFolder::folders($path, $filter);
			
			// First path, index foldernames
			foreach ($folders as $name)
			{
				$id = ++$GLOBALS['_ap_folder_tree_index'];
				$fullName = JPath::clean($path . '/' . $name);
				$dirs[] = array('id' => $id, 'parent' => $parent, 'name' => $name, 'fullname' => $fullName,
					'relname' => str_replace(JPATH_ROOT, '', $fullName));
				$dirs2 = self::listFolderTree($fullName, $filter, $maxLevel, $level + 1, $id);
				$dirs = array_merge($dirs, $dirs2);
			}
		}
		return $dirs;
	}
	// Final
	public function renderField($options = array()) {
		return '<div class="control-group grid">'
		. '<div class="controls">' . $this->getInput() . '</div>'
		. '</div>';
 	}
}
