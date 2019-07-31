<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$tplParams 		= JFactory::getApplication()->getTemplate(true)->params;
$params  		= $displayData->params;
$attribs 		= json_decode($displayData->attribs);
$images 			= json_decode($displayData->images);
$imgsize 		= $tplParams->get('blog_list_image', 'default');
$intro_image 	= '';
// Include lazy load params
$has_lazyload = $tplParams->get('lazyload', 1);

if(isset($attribs->spfeatured_image) && $attribs->spfeatured_image != '') {

	if($imgsize == 'default') {
		$intro_image = $attribs->spfeatured_image;
	} else {
		$intro_image = $attribs->spfeatured_image;
		$basename = basename($intro_image);
		$list_image = JPATH_ROOT . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
		if(file_exists($list_image)) {
			$intro_image = JURI::root(true) . '/' . dirname($intro_image) . '/' . JFile::stripExt($basename) . '_'. $imgsize .'.' . JFile::getExt($basename);
		}
	}
} elseif(isset($images->image_intro) && !empty($images->image_intro)) {
	$intro_image = $images->image_intro;
}
?>
<?php if(!empty($intro_image) || (isset($images->image_intro) && !empty($images->image_intro))) { ?>
<div class="entry-image intro-image">
	<?php if ($images->image_intro_caption) : echo '<div class="img-caption-overlay">'. htmlspecialchars($images->image_intro_caption) .'</div>'; endif; ?>
	<?php if ($params->get('link_titles') && $params->get('access-view')) { ?>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language)); ?>">
	<?php } ?>
	<?php 
		if(strpos($intro_image, 'http://') !== false || strpos($intro_image, 'https://') !== false){
			if($has_lazyload) { ?>
				<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo $intro_image; ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>">
			<?php } else { ?>
			<img src="<?php echo htmlspecialchars($intro_image); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" itemprop="thumbnailUrl">
			<?php } 
			} else { 
			if($has_lazyload) { ?>
				<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo JUri::root() . $intro_image; ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>">
			<?php } else { ?>
			<img src="<?php echo htmlspecialchars($intro_image); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>" itemprop="thumbnailUrl">
			<?php }
		} ?>
	<?php if ($params->get('link_titles') && $params->get('access-view')) { ?>
		</a>
	<?php } ?>
</div>
<?php } ?>
