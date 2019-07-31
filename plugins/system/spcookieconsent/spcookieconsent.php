<?php
/**
 * @package SP Cookie Consent
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2018 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

defined ('_JEXEC') or die();

class PlgSystemSpcookieconsent extends JPlugin
{

    protected $autoloadLanguage = true;
	protected $app;
	
	function onBeforeRender() {
	
		if ($this->app->isAdmin())
        {
			return;
		}

		$cookie = $this->app->input->cookie;
		if($cookie->get('spcookie_status') != 'ok')
		{
			$bg_color = $this->params->get('bg_color');
			$text_color = $this->params->get('text_color');
			$link_color = $this->params->get('link_color');
			$button_bg_color = $this->params->get('button_bg_color');
			$button_text_color = $this->params->get('button_text_color');
			$css = '#sp-cookie-consent {background-color: '. $bg_color .'; color: '. $text_color .'; }';
			$css .= '#sp-cookie-consent a, #sp-cookie-consent a:hover, #sp-cookie-consent a:focus, #sp-cookie-consent a:active {color: '. $link_color .'; }';
			$css .= '#sp-cookie-consent .sp-cookie-allow {background-color: '. $button_bg_color .'; color: ' . $button_text_color . ';}';
			$css .= '#sp-cookie-consent .sp-cookie-allow:hover, #sp-cookie-consent .sp-cookie-allow:active, #sp-cookie-consent .sp-cookie-allow:focus {color: ' . $button_text_color . ';}';
			$doc = JFactory::getDocument();
			JHtml::_('jquery.framework');
			$doc->addScript(JURI::root(true) . '/plugins/system/spcookieconsent/assets/js/script.js');
			$doc->addStyleDeclaration($css);
			$doc->addStylesheet(JURI::root(true) . '/plugins/system/spcookieconsent/assets/css/style.css');
		}
	}

    function onAfterRender() {
	
		if ($this->app->isAdmin())
        {
			return;
		}

		$cookie = $this->app->input->cookie;

		if($cookie->get('spcookie_status') != 'ok')
		{
			$cookie_content = $this->params->get('cookie_content');
			$button_text = $this->params->get('button_text');
			$position = $this->params->get('display_position');

			$html = '<div id="sp-cookie-consent" class="position-' . $position . '"><div>';
			$html .= '<div class="sp-cookie-consent-content">' . nl2br($cookie_content) . '</div>';
			$html .= '<div class="sp-cookie-consent-action"><a class="sp-cookie-close sp-cookie-allow" href="#">' . $button_text . '</a></div>';
			$html .= '</div></div>';

			$body = JResponse::getBody();
			$body = str_replace('</body>', $html . '</body>', $body);

			JResponse::setBody( $body );
		}
	}
}
