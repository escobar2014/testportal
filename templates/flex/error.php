<?php
/**
 * Flex @package Helix3 Framework
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2017 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('restricted access');

$doc = JFactory::getDocument();
$params = JFactory::getApplication()->getTemplate('true')->params;

//Load Helix
$helix3_path = JPATH_PLUGINS.'/system/helix3/core/helix3.php';

if (file_exists($helix3_path)) {
    require_once($helix3_path);
    $this->helix3 = Helix3::getInstance();
} else {
    die('Please install and activate helix plugin');
}


//Favicon
if($favicon = $params->get('favicon')) {
    $doc->addFavicon( JURI::base(true) . '/' .  $favicon);
} else {
    $doc->addFavicon( $this->baseurl . '/templates/' . $this->template . '/images/favicon.ico' );
}

//Stylesheets
$doc->addStylesheet( $this->baseurl . '/templates/' . $this->template . '/css/bootstrap.min.css' );
$doc->addStylesheet( $this->baseurl . '/templates/' . $this->template . '/css/font-awesome.min.css' );
$doc->addStylesheet( $this->baseurl . '/templates/' . $this->template . '/css/template.css' );

$webfonts = array();
if( $params->get('enable_body_font') ) {
    $webfonts['body'] = $params->get('body_font');
}
//Heading1 Font
if( $params->get('enable_h1_font') ) {
    $webfonts['h1'] = $params->get('h1_font');
}
//Heading3 Font
if( $params->get('enable_h3_font') ) {
    $webfonts['h3'] = $params->get('h3_font');
}
$this->helix3->addGoogleFont($webfonts);

//Custom background image
if($error_bg_image = $this->helix3->getParam('error_bg_image')) {
	
	$error_bg = 'background-color: transparent;';
    $error_bg .= 'background-image: url(' . JURI::base(true ) . '/' . $error_bg_image . ');';
    $error_bg .= 'background-repeat: no-repeat;';
    $error_bg .= 'background-size: cover;';
    $error_bg .= 'background-attachment: fixed;';
    $error_bg .= 'background-position: 50% 50%;';
    $error_bg = '.error-page body .container {' . $error_bg . '}'; 

    $doc->addStyledeclaration( $error_bg );
}

$error_bg_image != '' ? $error_bg_image_class = ' with-bckg-img' : $error_bg_image_class = '';

$doc->setTitle($this->error->getCode() . ' - '.$this->title);
$header_contents = '';
if(!class_exists('JDocumentRendererHead')) {
  $head = JPATH_LIBRARIES . '/joomla/document/html/renderer/head.php';
  if(file_exists($head)) {
    require_once($head);
  }
}
$header_renderer = new JDocumentRendererHead($doc);
$header_contents = $header_renderer->render(null);

?>
<!DOCTYPE html>
<html class="error-page" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<?php echo $header_contents; ?>
	</head>
	<body>
		<div class="error-page-inner<?php echo $error_bg_image_class; ?>">
            <div class="container">
            	<?php if ($error_bg_image) { ?>
				<p><i class="pe-7s-compass"></i></p>
                <?php } else { ?>
                <p><i class="fa fa-exclamation-triangle"></i></p>
                <?php } ?>
                <h1 class="error-code"> <?php echo $this->error->getCode(); ?></h1>
                <h3 class="error-code-message"><?php echo JText::_('HELIX_404'); ?></h3>
                <p class="error-message"><?php echo JText::_('HELIX_404_MESSAGE'); ?></p>
                <a class="btn btn-error btn-lg" href="<?php echo $this->baseurl; ?>/"><i class="fa fa-angle-left"></i> <?php echo JText::_('HELIX_GO_BACK'); ?></a>
                <?php echo $doc->getBuffer('modules', '404', array('style' => 'sp_xhtml')); ?>
            </div>
		</div>
	</body>
</html>