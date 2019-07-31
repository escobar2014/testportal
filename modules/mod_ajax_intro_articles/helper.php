<?php
/**
*	@package	Ajax Intro Articles
*	@copyright	Copyright (C) 2018 Aplikko. All rights reserved.
*	@license	GNU/GPL version 2, or later
*	@website:	http://www.aplikko.com
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

/* Helper */
abstract class ModAjaxIntroArticlesHelper
{

    public static function getAjax() {
        $input = JFactory::getApplication()->input;
        if ($input->get('cmd') == 'load') {
            $module = JModuleHelper::getModule('ajax_intro_articles', base64_decode($input->get('data')));
            $params = new JRegistry();
			
            $params->loadString($module->params);
			$list = ModAjaxIntroArticlesHelper::getList($params, $input->get('start'), $input->get('limit'));
            ob_start();
            require JModuleHelper::getLayoutPath('mod_ajax_intro_articles', $params->get('layout', 'default') . '_ajax');
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
        return false;
    }
    
	public static function getList($params, $start, $limit) {
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', $start);
		$model->setState('list.limit', $limit);
		$model->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));

		// User filter
		$userId = JFactory::getUser()->get('id');

		switch ($params->get('user_id'))
		{
			case 'by_me' :
				$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me' :
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;

			case '0' :
				break;

			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1' :
				$model->setState('filter.featured', 'only');
				break;
			case '0' :
				$model->setState('filter.featured', 'hide');
				break;
			default :
				$model->setState('filter.featured', 'show');
				break;
		}

		// Set ordering
		$order_map = array(
			'm_dsc' => 'a.modified DESC, a.created',
			'mc_dsc' => 'CASE WHEN (a.modified = ' . $db->quote($db->getNullDate()) . ') THEN a.created ELSE a.modified END',
			'c_dsc' => 'a.created',
			'p_dsc' => 'a.publish_up',
			'random' => 'RAND()',
		);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
		$dir = 'DESC';

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);

		$items = $model->getItems();
		
		foreach ($items as $item)
		{
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$item->link = JRoute::_('index.php?option=com_users&view=login');
			}
		}
		//$total = count($items);
		
		return $items;
	}

	public static function getTotal($params) {

		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app       = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('filter.published', 1);

		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));

		// User filter
		$userId = JFactory::getUser()->get('id');

		switch ($params->get('user_id'))
		{
			case 'by_me' :
				$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me' :
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;

			case '0' :
				break;

			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1' :
				$model->setState('filter.featured', 'only');
				break;
			case '0' :
				$model->setState('filter.featured', 'hide');
				break;
			default :
				$model->setState('filter.featured', 'show');
				break;
		}

		// Set ordering
		$order_map = array(
			'm_dsc' => 'a.modified DESC, a.created',
			'mc_dsc' => 'CASE WHEN (a.modified = ' . $db->quote($db->getNullDate()) . ') THEN a.created ELSE a.modified END',
			'c_dsc' => 'a.created',
			'p_dsc' => 'a.publish_up',
			'random' => 'RAND()',
		);
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
		$dir      = 'DESC';

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);

		$totals = $model->getItems();
		

		foreach ($totals as $item)
		{
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$item->link = JRoute::_('index.php?option=com_users&view=login');
			}
		}
		
		return $totals;
	}
	
	
	// Limit Characters and Words
	
	public static function cut_text($introtext){
    
    $postfix = '...';
    
    switch($limit_type){
      
      // CHARS
      case "chars":
        //strip HTML tags  
    		if($strip_tags){ $text = strip_tags($text);	}
        
        if(strlen($text) > $limit) {
          $text = substr($text, 0, strrpos(substr($text, 0, $limit), ' ')) . $postfix;
        } 
        break;
      
      // WORDS
      case "words":
  			$container = explode(' ', strip_tags($text));
  		
  			if(count($container) > $limit){

          //strip HTML tags  
      		if($strip_tags){ $text = strip_tags($text);	}
          
      		$container = explode(" ", $text);
     
      		//if text is longer than limit, return full text
          if (count($container) < $limit) {
            return $text;
          }
      
          //rebuild text by limit
      		$text = implode(" ", array_slice($container, 0, $limit));
          
          //add a postfix
          $text .= $postfix;
          // check and close unclosed html tags
          $text = self::closetags($text);
  			}
        else {
          //strip HTML tags  
    		if($strip_tags){ $text = strip_tags($text); }
        }
        break;

    }
       
    return $text;
	}
	
	public static function _cleanIntrotext($introtext)
    {
		// Load module's params
		$module = JModuleHelper::getModule('ajax_intro_articles');
		$params = new JRegistry($module->params);
		
        $introtext = str_replace('<p>', ' ', $introtext);
        $introtext = str_replace('</p>', ' ', $introtext);
        // Strip Tags, but allow some: 
		if ($params->get('show_introtext') == 1 && $params->get('strip_tags') == 1) {
			$introtext = strip_tags($introtext, '<a><em><strong><i><span>');
			$introtext = strip_tags($introtext, '<p>');
		} 
        $introtext = trim($introtext);

        return $introtext;
    }
	
}
