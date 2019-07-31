<?php
/**
 * @package 	images.php
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

class images {
	/**
	 * Function to handle upload images.
	 * Call by Ajax.
	 *
	 */
	public function uploadImages() {
		// Get the uploaded file information
		$files = JFactory::getApplication()->input->files->get('files', null, 'array');
		$responses = array();
		if (is_array($files))
		{
			$data = array();
			foreach ($files as $index => $file)
			{
				// If there is uploaded file, process it
				if (is_array($file) && isset($file['name']) && !empty($file['name']))
				{
					if ($this->_uploadFile($file))
					{
						$data['file_name'] = $file['name'];
						$data['size'] = $file['size'];
						$data['ext'] = $file['ext'];
						$data['temp_upload'] = 1;
						$data['changelogs'] = '';
					} 
				}
			}
		}
		$type = JRequest::getString('type', '') ? JRequest::getString('type', '') : (isset($params->type) ? $params->type : '');
		$folder = JRequest::getString('path', '');

		//return $this->loadImages(new stdClass());
		return $data;
	}

	/**
	 * Function to delete uploaded files.
	 * Call by AJAX.
	 */
	public function delete() {
		// Build the appropriate paths
		$folder = JRequest::getString('path', '');
		$base_path = JPATH_SITE . "/" . $folder;
		$file_name  = JRequest::getString('file_name', '');

		$data = array();

		if(empty($file_name)) {
			return $data['success'] = false;
		}

		// Move uploaded file
		jimport('joomla.filesystem.file');
		if(JFile::exists($base_path.'/'.$file_name))
		{
			JFile::delete($base_path.'/'.$file_name);
		}

		return $data['success'] = true;
	}

	/**
	 * Works out an installation package from a HTTP upload
	 *
	 * @return package definition or false on failure
	 */
	protected function _uploadFile(&$file) {
		// Check if there was a problem uploading the file.
		if ($file['error'])
		{
			return false;
		}
		if ($file['size'] < 1)
		{
			$file['error'] = JText::_("Upload Error");
			return false;
		}


		// Check extensions:
		$file['ext'] = substr($file['name'], strrpos($file['name'], '.')+1);
		$allowed_exts = array("bmp","gif","jpg","png","jpeg");
		if(!in_array($file['ext'], $allowed_exts)) {
			$file['error'] = JText::_("Extension not allowed");
			return false;
		}

		// Build the appropriate paths
		$folder = JRequest::getString('path', '');
		$base_path = JPATH_SITE . "/" . $folder;
		$file_dest	= $base_path.'/'.$file['name'];
		$file_src	= $file['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($file_src, $file_dest);

		// Unpack the downloaded package file
		if($uploaded) {
			return true;
		}

		$file['error'] = JText::_("Upload Error");
		return false;
	}


	protected function generate_response($content) {
		echo json_encode($content);
    }

	/**
	 * Load images from folder and match them
	 *
	 */
	public function loadImages(&$params) {
		$type = JRequest::getString('type', '') ? JRequest::getString('type', '') : (isset($params->type) ? $params->type : '');
		$folder = JRequest::getString('path', '');
		$images = $this->getListImages($folder, $type);

		return $images;
	}

}