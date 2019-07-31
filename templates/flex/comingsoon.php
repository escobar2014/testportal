<?php
/**
 * Flex @package Helix3 Framework
 * Template Name - Flex
 * @author Aplikko http://www.aplikko.com
 * @copyright Copyright (c) 2018 Aplikko
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted access');

$doc = JFactory::getDocument();
$app = JFactory::getApplication();

//Load Helix
$helix3_path = JPATH_PLUGINS.'/system/helix3/core/helix3.php';

if (file_exists($helix3_path)) {
    require_once($helix3_path);
    $this->helix3 = Helix3::getInstance();
} else {
    die('Please install and activate helix plugin');
}

$comingsoon_title = $this->params->get('comingsoon_title');
if( $comingsoon_title ) {
	$doc->setTitle( $comingsoon_title . ' | ' . $app->get('sitename') );
}

$comingsoon_date = explode('-', $this->params->get("comingsoon_date"));
$custom_logo_class = '';

//Load jQuery
JHtml::_('jquery.framework');
?>
<!DOCTYPE html>
<html class="sp-comingsoon" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if($favicon = $this->helix3->getParam('favicon')) {
        $doc->addFavicon( JURI::base(true) . '/' .  $favicon);
    } else {
        $doc->addFavicon( $this->helix3->getTemplateUri() . '/images/favicon.ico' );
    }
    ?>
    <jdoc:include type="head" />
    <?php
    $this->helix3->addCSS('bootstrap.min.css, font-awesome.min.css')
        ->lessInit()->setLessVariables(array(
            'preset'=>$this->helix3->Preset(),
            'bg_color'=> $this->helix3->PresetParam('_bg'),
            'text_color'=> $this->helix3->PresetParam('_text'),
            'major_color'=> $this->helix3->PresetParam('_major')
            ))
        ->addLess('master', 'template')
        ->addLess('presets',  'presets/'.$this->helix3->Preset())
    	->addJS('bootstrap.min.js, main.js, jquery.countdown.min.js');
		
		//Custom CSS
		if ($custom_css = $this->helix3->getParam('custom_css')) {
			$doc->addStyledeclaration($custom_css);
		}
		
		//Custom JS
		if ($custom_js = $this->helix3->getParam('custom_js')) {
			$doc->addScriptdeclaration($custom_js);
		}
		
//Custom background image
if($comingsoon_bg_image = $this->helix3->getParam('comingsoon_bg_image')) {
	
	$comingsoon_bg = 'background-color: transparent!important;';
    $comingsoon_bg .= 'background-image: url(' . JURI::base(true ) . '/' . $comingsoon_bg_image . ');';
    $comingsoon_bg .= 'background-repeat: no-repeat;';
    $comingsoon_bg .= 'background-size: cover;';
    $comingsoon_bg .= 'background-attachment: fixed;';
    $comingsoon_bg .= 'background-position: 50% 50%;';
    $comingsoon_bg = '.sp-comingsoon body {' . $comingsoon_bg . '}.sp-comingsoon body a.logo{background: rgba(0,0,0,0.6);}.sp-comingsoon body a.logo + .fa-clock-o{ opacity:0.57;}.sp-comingsoon body a.logo:hover + .fa-clock-o{opacity: 0.85}'; 

    $doc->addStyledeclaration( $comingsoon_bg );
}

//Body Font
$webfonts = array();

if( $this->params->get('enable_body_font') ) {
    $webfonts['body'] = $this->params->get('body_font');
}

//Heading1 Font
if( $this->params->get('enable_h1_font') ) {
    $webfonts['h1'] = $this->params->get('h1_font');
}

$this->helix3->addGoogleFont($webfonts);

$comingsoon_bg_image != '' ? $comingsoon_bg_image_class = ' class="with-bckg-img text-shadow"' : $comingsoon_bg_image_class = '';

?>
</head>
<body<?php echo $comingsoon_bg_image_class; ?>>
           <?php if( $this->helix3->getParam('comingsoon_logo') ) { ?>
           		<a class="logo" href="<?php echo $this->baseurl; ?>/"><div class="backhome hidden-xs hidden-sm"><i class="fa fa-angle-left"></i> <?php echo JText::_('HELIX_GO_BACK'); ?></div>
				<img style="max-height:150px;" class="sp-default-logo<?php echo $custom_logo_class ?>" src="<?php echo $this->helix3->getParam('comingsoon_logo') ?>" alt="<?php echo $app->get('sitename'); ?>">
                </a>
			<?php } else { ?>
				<?php if( $this->helix3->getParam('logo_image') ) { ?>
                    <a class="logo" href="<?php echo $this->baseurl; ?>/"><div class="backhome hidden-xs hidden-sm"><i class="fa fa-angle-left"></i> <?php echo JText::_('HELIX_GO_BACK'); ?></div>
                    <img style="max-height:150px;" class="sp-default-logo<?php echo $custom_logo_class ?>" src="<?php echo $this->helix3->getParam('logo_image') ?>" alt="<?php echo $app->get('sitename'); ?>">
                    </a>
                <?php } else { ?>
                    <a class="logo" href="<?php echo $this->baseurl; ?>/"><div class="backhome hidden-xs hidden-sm"><i class="fa fa-angle-left"></i> <?php echo JText::_('HELIX_GO_BACK'); ?></div><img style="width:150px;height:50%;" class="sp-default-logo<?php echo $custom_logo_class ?>" src="<?php echo $this->helix3->getTemplateUri() ?>/images/presets/<?php echo $this->helix3->Preset() ?>/logo@2x.png" alt="<?php echo $app->get('sitename'); ?>"></a>          
                <?php } ?>         
			<?php } ?>
            <div class="fa fa-clock-o"></div>
<div class="sp-comingsoon-wrap">	
	<div class="container">
		<div class="text-center">
			<div id="sp-comingsoon">
                
				<?php if( $comingsoon_title ) { ?>
					<h1 class="sp-comingsoon-title">
						<?php echo $comingsoon_title; ?>
					</h1>
				<?php } ?>

				<?php if( $this->params->get('comingsoon_content') ) { ?>
					<div class="sp-comingsoon-content">
						<?php echo $this->params->get('comingsoon_content'); ?>
					</div>
				<?php } ?>

				<div id="sp-comingsoon-countdown" class="sp-comingsoon-countdown">
					
				</div>

				<?php if($this->countModules('comingsoon')) { ?>
				<div class="sp-position-comingsoon">
					<jdoc:include type="modules" name="comingsoon" style="sp_xhtml" />
				</div>
				<?php } ?>

				<?php
				//Social Icons
				$facebook 	= $this->helix3->getParam('facebook');
				$twitter  	= $this->helix3->getParam('twitter');
				$googleplus = $this->helix3->getParam('googleplus');
				$instagram  = $this->helix3->getParam('instagram');
				$pinterest 	= $this->helix3->getParam('pinterest');
				$youtube 	= $this->helix3->getParam('youtube');
				$linkedin 	= $this->helix3->getParam('linkedin');
				$dribbble 	= $this->helix3->getParam('dribbble');
				$behance 	= $this->helix3->getParam('behance');
				$skype 		= $this->helix3->getParam('skype');
				$whatsapp 	= $this->helix3->getParam('whatsapp');
				$flickr 		= $this->helix3->getParam('flickr');
				$vk 			= $this->helix3->getParam('vk');

				if( $this->helix3->getParam('show_social_icons') && ( $facebook || $twitter || $googleplus || $instagram || $pinterest || $youtube || $linkedin || $dribbble || $behance || $skype || $whatsapp || $flickr || $vk ) ) {
					$html  = '<ul class="social-icons">';

					if( $facebook ) {
						$html .= '<li><a target="_blank" href="'. $facebook .'"><i class="fa fa-facebook"></i></a></li>';
					}
					if( $twitter ) {
						$html .= '<li><a target="_blank" href="'. $twitter .'"><i class="fa fa-twitter"></i></a></li>';
					}
					if( $googleplus ) {
						$html .= '<li><a target="_blank" href="'. $googleplus .'"><i class="fa fa-google-plus"></i></a></li>';
					}
					if( $instagram ) {
						$html .= '<li ><a target="_blank" href="'. $instagram .'"><i class="fa fa-instagram"></i></a></li>';
					}
					if( $pinterest ) {
						$html .= '<li><a target="_blank" href="'. $pinterest .'"><i class="fa fa-pinterest"></i></a></li>';
					}
					if( $youtube ) {
						$html .= '<li><a target="_blank" href="'. $youtube .'"><i class="fa fa-youtube"></i></a></li>';
					}
					if( $linkedin ) {
						$html .= '<li><a target="_blank" href="'. $linkedin .'"><i class="fa fa-linkedin"></i></a></li>';
					}
					if( $dribbble ) {
						$html .= '<li><a target="_blank" href="'. $dribbble .'"><i class="fa fa-dribbble"></i></a></li>';
					}
					if( $behance ) {
						$html .= '<li><a target="_blank" href="'. $behance .'"><i class="fa fa-behance"></i></a></li>';
					}
					if( $flickr ) {
						$html .= '<li><a target="_blank" href="'. $flickr .'"><i class="fa fa-flickr"></i></a></li>';
					}
					if( $vk ) {
						$html .= '<li><a target="_blank" href="'. $vk .'"><i class="fa fa-vk"></i></a></li>';
					}
					if( $skype ) {
						$html .= '<li><a href="skype:'. $skype .'?chat"><i class="fa fa-skype"></i></a></li>';
					}
					if( $whatsapp ) {
						$html .= '<li><a href="whatsapp://send?abid='. $whatsapp .'&text=Hi"><i class="fa fa-whatsapp"></i></a></li>';
					}

					$html .= '<ul>';

					echo $html;
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php 
// Add JS and minify
	$countdown_js = 'jQuery(function($){
		$(\'#sp-comingsoon-countdown\').countdown(\''.trim($comingsoon_date[2]).'/'.trim($comingsoon_date[1]).'/'.trim($comingsoon_date[0]).'\', function(event) {$(this).html(event.strftime(\'<div class="days"><span class="number">%-D</span><span class="string">%!D:'.JText::_("HELIX_DAY").','.JText::_("HELIX_DAYS").';</span></div><div class="hours"><span class="number">%H</span><span class="string">%!H:'.JText::_("HELIX_HOUR").','.JText::_("HELIX_HOURS").';</span></div><div class="minutes"><span class="number">%M</span><span class="string">%!M:'.JText::_("HELIX_MINUTE").','.JText::_("HELIX_MINUTES").';</span></div><div class="seconds"><span class="number">%S</span><span class="string">%!S:'.JText::_("HELIX_SECOND").','.JText::_("HELIX_SECONDS").';</span></div>\'));
		});
	});'; 
	$countdown_js = preg_replace(array('/([\s])\1+/', '/[\n\t]+/m'), '', $countdown_js); // Remove whitespace
	$doc->addScriptdeclaration($countdown_js);	
?>
</body>
</html>