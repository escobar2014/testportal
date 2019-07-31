<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params = $displayData->params;
$tplParams = JFactory::getApplication()->getTemplate(true)->params; // Template's params
$attribs = json_decode($displayData->attribs);
$images = json_decode($displayData->images);
// Include lazy load params
$has_lazyload = $tplParams->get('lazyload', 1);
$full_image 	= '';

if(isset($attribs->spfeatured_image) && $attribs->spfeatured_image != '') {
	$full_image = $attribs->spfeatured_image;
} elseif(isset($images->image_fulltext) && !empty($images->image_fulltext)) {
	$full_image = $images->image_fulltext;
}
?>
<?php if(!empty($full_image) || (isset($images->image_fulltext) && !empty($images->image_fulltext))) { ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
	<div class="entry-image full-image">
		<?php if ($images->image_fulltext_caption) : echo '<div class="img-caption-overlay">'. htmlspecialchars($images->image_fulltext_caption) .'</div>'; endif; ?>
		<?php if(strpos($full_image, 'http://') !== false || strpos($full_image, 'https://') !== false){
			if($has_lazyload) { ?>
				<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo htmlspecialchars($full_image); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>">
			<?php } else { ?>
			<img src="<?php echo htmlspecialchars($full_image); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" itemprop="image">
			<?php } 
			} else { 
			if($has_lazyload) { ?>
				<img class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?php echo JUri::root() . htmlspecialchars($full_image); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>">
			<?php } else { ?>
			<img src="<?php echo htmlspecialchars($full_image); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" itemprop="image">
			<?php }
		} ?>
     </div>
<?php } ?>
