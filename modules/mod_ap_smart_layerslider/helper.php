<?php
/**
 * AP Smart LayerSlider Module
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2019 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

class modApSmartLayersliderHelper {

	public static function getList($params) {
		$data = array();
		$display_form = strtolower($params->get('display_form', 'joomla_content'));
		if ($display_form == 'joomla_content') {
			if ($params->get('enable_cache')) {
				$cache = JFactory::getCache();
				$cache->setCaching(true);
				$cache->setLifeTime($params->get('cache_time', 30) * 60);
				$rows = $cache->get(array((new self()), 'getListArticles'), array($params));
			} else {
				$data = self::getListArticles($params);
			}
		} else if ($display_form == 'k2') {
			if ($params->get('enable_cache')) {
				$cache = JFactory::getCache();
				$cache->setCaching(true);
				$cache->setLifeTime($params->get('cache_time', 30) * 60);
				$rows = $cache->get(array((new self()), 'getK2Items'), array($params));
			} else {
				$data = self::getK2Items($params);
			}
		} else if ($display_form == 'folder_image') {
			$data = self::getImageFolder($params);
		}
		return $data;
	}
	/**
	 * Method get list image of folder
	 * 
	 * @param object $params
	 * 
	 * @return array images
	 */
	public static function getImageFolder($params) {
		$list = array();
		$images = new stdClass();
		if ($params->get('path_folder.images')){
			$images = json_decode($params->get('path_folder.images'));
		}
		$folder = $params->get('path_folder.folder');
		
		foreach ($images as $image){
			$image->description = $image->description;
			$image->image = self::renderImage($image->title, $image->caption,$folder.'/'.$image->image, $params, $params->get('image_width', 400), $params->get('image_height', 300));
		    $image->thumb = self::renderImage($image->title, $image->caption,$folder.'/'.$image->image, $params, $params->get('thumbnailWidth', 90), $params->get('thumbnailHeight', 70));
			$list[$image->position] = $image;
		}
		ksort($list);
		return $list;

	}
	
	/**
	 * Method get list k2 items follow setting configuration.
	 *
	 * @param JParameter $param
	 * @return array
	 */
	public static function getK2Items($params) {
		if (class_exists('K2Model')) {
			if (file_exists(JPATH_SITE . '/components/com_k2/helpers/route.php')) {
				require_once (JPATH_SITE . '/components/com_k2/helpers/route.php');
			}
			jimport('joomla.image.image');
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			$app = JFactory::getApplication();

			$user = JFactory::getUser();
			$db = JFactory::getDbo();

			$jnow = JFactory::getDate();
			$now = $jnow->toSql();
			$nullDate = $db->getNullDate();

			$query = $db->getQuery(true);

			$cid = $params->get('k2catid', null);


			$query->select('i.*,CASE WHEN i.modified = 0 THEN i.created ELSE i.modified END as lastChanged,c.alias AS categoryalias')
					->from('#__k2_items AS i')
					->leftJoin('#__k2_categories AS c ON c.id = i.catid')
					->where('i.published = 1 AND i.trash = 0 AND c.published = 1 AND c.trash = 0 ')
					->where("i.access IN(" . implode(',', $user->getAuthorisedViewLevels()) . ") AND c.access IN(" . implode(',', $user->getAuthorisedViewLevels()) . ")");

			if ($app->getLanguageFilter()) {
				$languageTag = JFactory::getLanguage()->getTag();
				$query->where("c.language IN (" . $db->Quote($languageTag) . ", " . $db->Quote('*') . ") AND i.language IN (" . $db->Quote($languageTag) . ", " . $db->Quote('*') . ")");
			}

			if (!is_null($cid)) {
				$itemListModel = K2Model::getInstance('Itemlist', 'K2Model');
				if (!is_array($cid)) {
					$categories = $itemListModel->getCategoryTree($cid);
				} else {
					$categories = $itemListModel->getCategoryTree($cid);
				}
				$query->where('c.id IN (' . implode(',', $categories) . ')');
			}
			
			$model =  K2Model::getInstance('Item', 'K2Model');
			
			if($params->get('feature',0)){
				$query->where('i.featured = 1');
			}
			
			$query->where("( i.publish_up = " . $db->Quote($nullDate) . " OR i.publish_up <= " . $db->Quote($now) . ")")
					->where("(i.publish_down = " . $db->Quote($nullDate) . " OR i.publish_down >= " . $db->Quote($now) . " )");
			
			$ordering = $params->get('sort_order_field', 'id');

			switch ($ordering)
			{

				case 'date' :
					$orderby = 'i.created ASC';
					break;

				case 'rdate' :
					$orderby = 'i.created DESC';
					break;

				case 'alpha' :
					$orderby = 'i.title';
					break;

				case 'ralpha' :
					$orderby = 'i.title DESC';
					break;

				case 'order' :
					$orderby = 'i.ordering ASC';
					break;

				case 'rorder' :
					$orderby = 'i.ordering DESC';
					break;

				case 'hits' :
					$orderby = 'i.hits DESC';
					break;

				case 'rand' :
					$orderby = 'RAND()';
					break;
					
				case 'modified' :
					$orderby = 'lastChanged DESC';
					break;

				case 'publish_up' :
					$orderby = 'i.publish_up DESC';
					break;
					
				case 'id':
				default :
					$orderby = 'i.id DESC';
				break;
			}
			$query->order($orderby);
			$db->setQuery($query, 0, $params->get('count', 5));

			$items = $db->loadObjectList();
	
			foreach ($items as $item) {
				$item->image = '';
				$item->caption = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id . ':' . urlencode($item->alias), $item->catid . ':' . urlencode($item->categoryalias))));
				
				if ($params->get('show_image', 1)) {
					if (JFile::exists(JPATH_SITE . '/media/k2/items/src/' . md5("Image" . $item->id) . '.jpg')) {
						
						$image = 'media/k2/items/src/' . md5("Image" . $item->id) . '.jpg';
						
						$item->image = self::renderImage($item->title, $item->caption, $image, $params, $params->get('image_width'), $params->get('image_height'));
						$item->thumb = self::renderImage($item->title, $item->caption, $image, $params, $params->get('thumbnailWidth'), $params->get('thumbnailHeight'));
					} else {
						$item->image = '';
					}
				}
			$item->rtitle = self::trimChar($item->title,$params->get('title_max_char',-1));
			$item->description = self::trimChar($item->introtext, $params->get('description_max_chars', 70)); 
		    $item->description = $item->introtext; // (equal)
			$item->num_comments = $model->countItemComments($item->id);
			$item->num_votes = $model->getVotesNum($item->id);
			$item->votingPercentage = $model->getVotesPercentage($item->id);

				// Strip/Allow Tags for K2
				  if ($params->get('strip_tags') != 0) {
					$item->description = strip_tags($item->introtext, '<a><p><h1><h2><h3><h4><h5><h6><img><em><span><div><i><button><b><br><hr><strong><video><source><track><audio>');
				  }
				}
			return $items;			
		}
		return array();
	}

	/**
	 * Method get list articles
	 * @param array $params
	 * 
	 * @return array $items
	 */
	public static function getListArticles($params) {

		$dispatcher = JEventDispatcher::getInstance();

		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		
		//Feature filter
		if($params->get('feature',0)){
			$model->setState('filter.featured','only');
		}
		
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		$ordering = $params->get('sort_order_field', 'created');
		//$dir = $params->get('sort_order', 'DESC');
		switch ($ordering)
		{

			case 'date' :
				$orderby = 'a.created';
				$dir = 'ASC';
				break;

			case 'rdate' :
				$orderby = 'a.created';
				$dir = 'DESC';
				break;

			case 'alpha' :
				$orderby = 'a.title';
				$dir = 'ASC';
				break;

			case 'ralpha' :
				$orderby = 'a.title';
				$dir = 'DESC';
				break;

			case 'order' :
				$orderby = 'a.ordering';
				$dir = 'ASC';
				break;

			case 'rorder' :
				$orderby = 'a.ordering';
				$dir = 'DESC';
				break;

			case 'hits' :
				$orderby = 'a.hits';
				$dir = 'DESC';
				break;

			case 'rand' :
				$orderby = 'RAND()';
				$dir = '';
				break;
				
			case 'modified' :
				$orderby = 'modified';
				$dir = 'DESC';
				break;

			case 'publish_up' :
				$orderby = 'a.publish_up';
				$dir = 'DESC';
				break;
				
			case 'id':
			default :
				$orderby = 'a.id';
				$dir = 'DESC';
			break;
		}
			
		$model->setState('list.ordering',$orderby);
		$model->setState('list.direction', $dir);

		$items = $model->getItems();

		foreach ($items as $item) {
			$item->text = $item->introtext;
			$item->num_comments = '';
			$item->num_votes = '';
			$item->votingPercentage = '';
			$item->introtext = $item->text;
			$item->slug = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$item->caption = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			} else {
				$item->caption = JRoute::_('index.php?option=com_users&view=login');
			}
			$item->image = '';
			if ($params->get('show_image', 1)) {
				$image = self::parseImages($item, $params);
				if ($image) {
					$item->image = self::renderImage($item->title, $item->caption, $image, $params, $params->get('image_width'), $params->get('image_height'));
				   $item->thumb = self::renderImage($item->title, $item->caption, $image, $params, $params->get('thumbnailWidth'), $params->get('thumbnailHeight'));
				} else {
					$item->image = '';
				}
			}
			$item->rtitle = self::trimChar($item->title,$params->get('title_max_char',-1));
			$item->description = self::trimChar($item->introtext, $params->get('description_max_chars', 70)); 
			
		    $item->description = $item->introtext; // (equal)
			
			// Strip/Allow Tags for Joomla articles
				  if ($params->get('strip_tags') != 0) {
					$item->description = strip_tags($item->introtext, '<a><p><h1><h2><h3><h4><h5><h6><img><em><span><div><i><button><b><br><hr><strong><video><source><track><audio>');
				  }
				}
			return $items;
	}

	/**
	 * parser a image in the content.
	 * @param object $row object content
	 * @param object $params
	 * @return string image
	 */
	public static function parseImages($row, $params, $context = 'joomla_content') {

		//check if there is image intro or image fulltext  
		$images = "";
		if (isset($row->images)) {
			$images = json_decode($row->images);
		}
		if ((isset($images->image_fulltext) and !empty($images->image_fulltext)) || (isset($images->image_intro) and !empty($images->image_intro))) {
			$image = (isset($images->image_intro) and !empty($images->image_intro)) ? $images->image_intro : ((isset($images->image_fulltext) and !empty($images->image_fulltext)) ? $images->image_fulltext : "");
			return $image;
		} else {
			$text = $row->introtext;
			$regex = "/\<img.+?src\s*=\s*[\"|\']([^\"]*)[\"|\'][^\>]*\>/";
			preg_match($regex, $text, $matches);
			$images = (count($matches)) ? $matches : array();
			if (count($images)) {
				return $images[1];
			}
		}
		return false;
	}





	/**
	 * Render image before display it
	 * 
	 * @param string $title
	 * @param string $caption
	 * @param string $image
	 * @param object $params
	 * @param int $width
	 * @param int $height
	 * @param string $attrs
	 * @param string $returnURL
	 * 
	 * @return string image
	 */
	public static function renderImage($title, $caption, $image, $params, $width = 0, $height = 0, $attrs = '', $returnURL = false, $class = null) {
		if ($image) {
			$title = strip_tags($title);
			$mainimageMode = $params->get('mainimage_mode', 'crop');
			$thumbs_mode = $params->get('thumbs_mode', 'crop');
			$aspect = $params->get('use_ratio', '1');
			$thumbaspect = $params->get('thumbratio', '1');
			$crop = $mainimageMode == 'crop' ? true : false;
			$thumbcrop = $thumbs_mode == 'crop' ? true : false;
			$imageHelper = ApImageHelper::getInstance();

		if ($mainimageMode != 'none' && $imageHelper->sourceExited($image)) {
				$imageURL = $imageHelper->resize($image, $width, $height, $crop, $thumbcrop, $aspect);
				if ($returnURL) {

					return $imageURL;
				}
				if ($imageURL != $image && $imageURL) {
					// Render Image mode = Crop / Resize
					$image = ''.$imageURL.'" alt="'.$title.'';
				} else {
					$image = ''.$image.'" alt="'.$title.'';
				}
			} else {
				if ($returnURL) {
					return $image.'" alt="'.$title.'';
				}
				// Render Image mode = 'No' (original image)
					return $image.'" alt="'.$title.'';	
			}
		} else {
			$image = '';
		}
		// clean up globals
		return $image;
	}

	/**
	 * Method trim string with max specify
	 * @param string $string
	 * @param int $maxChar
	 * 
	 * @return string
	 */
	public static function trimChar($string, $maxChar = 50) {

		if ($maxChar == '-1')
			return strip_tags($string);

		if ($maxChar == 0)
			return '';

		if (strlen($string) > $maxChar)
			return JString::substr(strip_tags($string), 0, $maxChar) . ' ';

		return $string;
	}

}

if (!class_exists('ApImageHelper')) {
	if (!defined('DS'))
		define('DS', DIRECTORY_SEPARATOR);

	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');
	
	
	class ApImageHelper {



		/**
		 * Identifier of the cache path.
		 *
		 * @access private
		 * @param string $_cachePath
		 */
		var $_cachePath;

		/**
		 * Identifier of the path of source.
		 *
		 * @access private
		 * @param string $_imageBase
		 */
		var $_imageBase;

		/**
		 * Identifier of the image's extensions
		 *
		 * @access public
		 * @param array $types
		 */
		var $types = array();

		/**
		 * Identifier of the quantity of mainimage image.
		 *
		 * @access public
		 * @param string $_quality
		 */
		var $_quality = 90;

		/**
		 * Identifier of the url of folder cache.
		 *
		 * @access public
		 * @param string $_cacheURL
		 */
		var $_cacheURL;
		/**
		 * constructor
		 */
		 
		function __construct() {
			$filefolder = substr(md5(basename(dirname(__FILE__))),1,10);
			$this->types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
			$this->_imageBase = JPATH_SITE . DS . '/images' . DS;
			//$this->_cachePath = JPATH_CACHE . DS .md5(basename(dirname(__FILE__))). DS;
			//$this->_cacheURL = JURI::base().'cache/'.md5(basename(dirname(__FILE__))).'/';	
			$this->_cachePath = JPATH_CACHE . DS .$filefolder. DS;
			$this->_cacheURL = JURI::base().'cache/'.$filefolder.'/';		
		}

		/**
		 * get a instance of NooImageHelper object.
		 *
		 * This method must be invoked as:
		 * <pre>  $NooImageHelper = &NooImageHelper::getInstace();</pre>
		 *
		 * @static.
		 * @access public,
		 */
		public static function &getInstance() {
			static $instance = null;
			if (!$instance) {
				$instance = new ApImageHelper();
			}
			return $instance;
		}

		/**
		 * crop or resize image
		 *
		 *
		 * @param string $image path of source.
		 * @param integer $width width of mainimage
		 * @param integer $height height of mainimage
		 * @param boolean $aspect whether to render mainimage base on the ratio
		 * @param boolean $crop whether to use crop image to render mainimage.
		 * @access public,
		 */
		function resize($image, $width, $height, $crop = true, $aspect = true) {
			// get image information


			if (!$width || !$height)
				return '';

			$image = str_replace(JURI::base(), '', $image);
		

			$imagSource = JPATH_SITE . DS . str_replace('/', DS, $image);

			if (!file_exists($imagSource) || !is_file($imagSource)) {
				return '';
			}
			$filetime = filemtime($imagSource);
			$size = getimagesize($imagSource);
			// if it's not a image.
			if (!$size) {
				return '';
			}

			// case 1: render image base on the ratio of source.
			$x_ratio = $width / $size[0];
			$y_ratio = $height / $size[1];

			// set dst, src
			$dst = new stdClass();
			$src = new stdClass();
			$src->y = $src->x = 0;
			$dst->y = $dst->x = 0;

			if ($width > $size[0])
				$width = $size[0];
			if ($height > $size[1])
				$height = $size[1];

			if ($crop) { // processing crop image
				$dst->w = $width;
				$dst->h = $height;
				if (($size[0] <= $width) && ($size[1] <= $height)) {
					$src->w = $width;
					$src->h = $height;
				} else {
					if ($x_ratio < $y_ratio) {
						$src->w = ceil($width / $y_ratio);
						$src->h = $size[1];
					} else {
						$src->w = $size[0];
						$src->h = ceil($height / $x_ratio);
					}
				}
				$src->x = floor(($size[0] - $src->w) / 2);
				$src->y = floor(($size[1] - $src->h) / 2);
			} else { // processing resize image.
				$src->w = $size[0];
				$src->h = $size[1];
				if ($aspect) { // using ratio
					if (($size[0] <= $width) && ($size[1] <= $height)) {
						$dst->w = $size[0];
						$dst->h = $size[1];
					} else if (($size[0] <= $width) && ($size[1] <= $height)) {
						$dst->w = $size[0];
						$dst->h = $size[1];
					} else if (($x_ratio * $size[1]) < $height) {
						$dst->h = ceil($x_ratio * $size[1]);
						$dst->w = $width;
					} else {
						$dst->w = ceil($y_ratio * $size[0]);
						$dst->h = $height;
					}
				} else { // resize image without the ratio of source.
					$dst->w = $width;
					$dst->h = $height;
				}
			}
			//			
			$ext = substr(strrchr($image, '.'), 1);
			$filemd5 = substr(md5($ext),1,10);
			$mainimage = substr($image, 0, strpos($image, '.')) . "-" . $filemd5 . "_{$width}x{$height}." . $ext;
			$imageCache = $this->_cachePath . str_replace('/', DS, $mainimage);

			if (file_exists($imageCache)) {
				$filetimecache = filemtime($imageCache);
				if ($filetime < $filetimecache) {
					$smallImg = getimagesize($imageCache);
					if (($smallImg[0] == $dst->w && $smallImg[1] == $dst->h)) {
						return $this->_cacheURL . $mainimage;
					}
				}
			}

			if (!file_exists($this->_cachePath) && !JFolder::create($this->_cachePath)) {
				return '';
			}

			if (!$this->makeDir($image)) {
				return '';
			}

			// resize image
			$this->_resizeImage($imagSource, $src, $dst, $size, $imageCache);

			return $this->_cacheURL . $mainimage;
		}

		/**
		 * check the folder is existed, if not make a directory and set permission is 755
		 *
		 *
		 * @param array $path
		 * @access public,
		 * @return boolean.
		 */
		function makeDir($path) {
			$folders = explode('/', ($path));
			$tmppath = $this->_cachePath;
			for ($i = 0; $i < count($folders) - 1; $i++) {
				if (!file_exists($tmppath . $folders[$i]) && !JFolder::create($tmppath . $folders[$i], 0755)) {
					return false;
				}
				$tmppath = $tmppath . $folders[$i] . DS;
			}
			return true;
		}

		/**
		 * process render image
		 *
		 * @param string $imageSource is path of the image source.
		 * @param stdClass $src the setting of image source
		 * @param stdClass $dst the setting of image dts
		 * @param string $imageCache path of image cache ( it's mainimage).
		 * @access public,
		 */
		function _resizeImage($imageSource, $src, $dst, $size, $imageCache) {
			// create image from source.
			$extension = $this->types[$size[2]];
			$image = call_user_func("imagecreatefrom" . $extension, $imageSource);

			if (function_exists("imagecreatetruecolor") && ($newimage = imagecreatetruecolor($dst->w, $dst->h))) {

				if ($extension == 'gif' || $extension == 'png') {
					imagealphablending($newimage, false);
					imagesavealpha($newimage, true);
					$transparent = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
					imagefilledrectangle($newimage, 0, 0, $dst->w, $dst->h, $transparent);
				}

				imagecopyresampled($newimage, $image, $dst->x, $dst->y, $src->x, $src->y, $dst->w, $dst->h, $src->w, $src->h);
			} else {
				$newimage = imagecreate($src->w, $src->h);
				imagecopyresized($newimage, $image, $dst->x, $dst->y, $src->x, $src->y, $dst->w, $dst->h, $size[0], $size[1]);
			}

			switch ($extension) {
				case 'jpeg':
					call_user_func('image' . $extension, $newimage, $imageCache, $this->_quality);
					break;
				default:
					call_user_func('image' . $extension, $newimage, $imageCache);
					break;
			}
			// free memory
			imagedestroy($image);
			imagedestroy($newimage);
		}

		/**
		 * set quality image will render.
		 */
		function setQuality($number = 9) {
			$this->_quality = $number;
		}

		/**
		 * check the image is a captioned image from other server.
		 *
		 *
		 * @param string the url of image.
		 * @access public,
		 * @return array if it' captioned image, return false if not
		 */
		function isLinkedImage($imageURL) {
			$parser = parse_url($imageURL);
			return strpos(JURI::base(), $parser['host']) ? false : $parser;
		}

		/**
		 * check the file is a image type ?
		 *
		 * @param string $ext
		 * @return boolean.
		 */
		function isImage($ext = '') {
			return in_array($ext, $this->types);
		}

		/**
		 * check the image source is existed ?
		 *
		 * @param string $imageSource the path of image source.
		 * @access public,
		 * @return boolean,
		 */
		function sourceExited($imageSource) {

			if ($imageSource == '' || $imageSource == '..' || $imageSource == '.') {
				return false;
			}
			$imageSource = str_replace(JURI::base(), '', $imageSource);
			$imageSource = rawurldecode($imageSource);
			return (file_exists(JPATH_SITE . '/' . $imageSource));
		}
		
		/**
		 * check the image source is existed ?
		 *
		 * @param string $imageSource the path of image source.
		 * @access public,
		 * @return boolean,
		 */
		function parseImage($row) {
			//check to see if there is an  intro image or fulltext image  first
			$images = "";
			if (isset($row->images)) {
				$images = json_decode($row->images);
			}
			if ((isset($images->image_fulltext) and !empty($images->image_fulltext)) || (isset($images->image_intro) and !empty($images->image_intro))) {
				$image = (isset($images->image_intro) and !empty($images->image_intro)) ? $images->image_intro : ((isset($images->image_fulltext) and !empty($images->image_fulltext)) ? $images->image_fulltext : "");
			} else {
				$regex = '/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/';
				$text = '';
				$text .= (isset($row->fulltext)) ? $row->fulltext : '';
				$text .= (isset($row->introtext)) ? $row->introtext : '';
				preg_match($regex, $text, $matches);
				$images = (count($matches)) ? $matches : array();
				$image = count($images) > 1 ? $images[1] : '';
			}
			return $image;
		}

	}

}

