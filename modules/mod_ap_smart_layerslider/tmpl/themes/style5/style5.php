<?php
/**
 * AP Smart LayerSlider for Joomla 3.x
 * @author		Aplikko
 * @email		contact@aplikko.com
 * @website		http://aplikko.com
 * @copyright	Copyright (C) 2015 Aplikko.com. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
**/

// no direct access
defined('_JEXEC') or die;

//Path to module's style css
$doc->addStylesheet($baseUri.'tmpl/themes/style'.$theme.'/style'.$theme.'.css');

$countList = count($lists);

//if there is no item ?>
<?php if (isset($lists) && count($lists) == 0) : ?>

<?php //Alert messages ?>
<div class="ap_alert row-fluid">
    <div align="center" class="span12">
        <div class="alert alert-block fade in">
          <button type="button" class="close" data-dismiss="alert" style="font-size:24px;">&times;</button>
            <div id="message" style="text-align:center;margin:0 auto;padding:12px 0 0;line-height:24px;">
                <h4 style="vertical-align:middle;font-size:150%;line-height:30px;margin-left:10px;">
                <span class="label label-important" style="font-size:14px;margin:2px 10px 0;padding:5px 10px;line-height:110%;vertical-align:top">
                <i style="margin-right:8px;" class="fa fa-info-circle"></i>Important!</span>
                <?php if (($params->get('display_form') == 'k2') && count($lists) == 0) : ?> 
                No K2 Articles
                <?php elseif (($params->get('display_form') == 'joomla_content') && count($lists) == 0) : ?> 
                No Joomla Articles
                <?php else : ?> 
                No images in this folder
                <?php endif; ?> 
                </h4><br/>
                <p>
                <?php if (($params->get('display_form') == 'k2') && count($lists) == 0) : ?> 
                Probably <b>K2</b> is not installed or K2 articles are not published. You can download K2 form <b><a style="color:#B94A48;" href="http://getk2.org/index.php" target="_blank">here <i class="fa fa-download"></i></a></b> and install it.
                <?php elseif (($params->get('display_form') == 'joomla_content') && count($lists) == 0) : ?> 
                No <b>Joomla articles</b> are published in this category.
                <?php else : ?> 
                Empty folder. Make sure you put some images in <b>this</b> folder in "AP Smart LayerSlider" module admin.
                <?php endif; ?>  
                </p>
            </div>
        </div>
    </div>  
</div>
<?php else : ?> 
<div id="ap-smart-layerslider-<?php echo $ext_id; ?>" class="slider-pro style5 <?php echo $moduleclass_sfx; ?>">
    <!-- Slides -->
    <div class="sp-slides row-fluid">    
        <?php foreach ($lists as $list) { ?>
            <div class="sp-slide">
				<?php if (!empty($list->image)) { ?>   
                    <?php if ($params->get('mainimage_mode') == 'none') { ?>  
                      <img class="sp-image crop" src="modules/mod_ap_smart_layerslider/assets/images/blank.gif" data-src="<?php echo JURI::base().$list->image; ?>" />
                    <?php } else { ?> 
                     <img class="sp-image crop" src="modules/mod_ap_smart_layerslider/assets/images/blank.gif" data-src="<?php echo $list->image; ?>" />
                    <?php } ?> 
                <?php } ?>              
                <!-- Description (layers) -->
                <div class="ap-layer">
                    <?php echo $list->description; ?>
                </div>
                <?php if ($display_caption == 1) { ?>
                   <!-- Captions -->
                   <?php if ($params->get('display_form') == 'folder_image') { ?>
                     <div class="sp-caption"><?php echo $list->caption; ?></div>
                   <?php } else { ?> 
                     <div class="sp-caption">
					 <?php echo modApSmartLayersliderHelper::trimChar($list->description, $params->get('description_max_chars', 70)); ?>
                     </div>
                  <?php } ?> 
                <?php } ?>    
             </div>
		  <?php } /* end foreach */ ?>  
     </div><?php /* Slides */ ?> 
    
    <?php if ($show_thumbnails == 1) { ?>  
    <!-- Thumbnails -->
    <div class="sp-thumbnails">
	   <?php foreach ($lists as $list) { ?>
			<div class="sp-thumbnail">  
                <div class="sp-thumbnail-image-container">
                 <?php if (isset($lists) && count($lists) > 0) { ?>
                    <?php if ($params->get('display_form') == 'folder_image') { ?>
                            <?php /* Folder image */ ?>
                            <img class="sp-thumbnail-image crop" src="<?php echo $list->image; ?>" />            
                    <?php } else { ?>        
                        <?php if ($params->get('mainimage_mode') == 'none') { ?>
                            <?php /* Joomla or K2 Category */ ?>
                            <?php if (!empty($list->image)) { ?>
                                <img class="sp-thumbnail-image crop" src="<?php echo $list->thumb; ?>" />
                             <?php } else { ?>
                                 <div class="sp-thumbnail-image empty">no image</div>
                            <?php } ?>   
                        <?php } else { ?>
                            <?php /* Joomla or K2 Category */ ?>
                            <?php if (!empty($list->image)) { ?>
                                <img class="sp-thumbnail-image crop" src="<?php echo $list->thumb; ?>" />  
                             <?php } else { ?>
                                 <div class="sp-thumbnail-image empty">no image</div>
                            <?php } ?>
                        <?php } ?>   
                    <?php } ?>
                <?php } ?> 
                </div>
                
                <div class="sp-thumbnail-text">
                   <h4 class="sp-thumbnail-title"><?php echo $list->title; ?></h4> 
                    <?php if ($show_thumbnail_description == 1) { ?>
                    <div class="sp-thumbnail-description">
					<?php echo modApSmartLayersliderHelper::trimChar($list->description, $params->get('thumbnail_description_max_chars', 50)); ?>
                    </div>
                    <?php } ?>  
                </div>
    		</div>
		<?php } /* end foreach */ ?>  
     </div><?php /* sp-thumbnails */ ?>
     <?php } ?>  
            	     
</div><?php /* End ap-smart-layerslider div */ ?>
<?php endif; ?>	

<?php if ($show_thumbnails == 1) { ?> 
<style type="text/css">
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-top-thumbnails .sp-thumbnail-container,
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-bottom-thumbnails .sp-thumbnail-container {
	height:<?php echo $thumbnailHeight + 18; ?>px!important;
}
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-thumbnail-image-container {
	width:<?php echo $thumbnailHeight + 20; ?>px;
	height:<?php echo $thumbnailHeight; ?>px;
}
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-thumbnail-text {
	width:<?php echo $thumbnailWidth - $thumbnailHeight - 22; ?>px;
	height:<?php echo $thumbnailHeight; ?>px;
}
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-left-thumbnails.sp-has-pointer .sp-thumbnail-text,
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-right-thumbnails.sp-has-pointer .sp-thumbnail-text {
	width:<?php echo $thumbnailWidth - $thumbnailHeight - 38; ?>px;
}
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-top-thumbnails.sp-has-pointer .sp-selected-thumbnail:before,
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-bottom-thumbnails.sp-has-pointer .sp-selected-thumbnail:before {
	width:<?php echo $thumbnailWidth - 2; ?>px;
}	
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-thumbnail-image-container img.crop{
	margin:0 0 0 -<?php echo ($thumbnailHeight + 20) / 5; ?>px;
}
#ap-smart-layerslider-<?php echo $ext_id; ?> div.empty{
	width:<?php echo $thumbnailHeight + 20; ?>px;
	height:<?php echo $thumbnailHeight; ?>px;
	line-height:<?php echo $thumbnailHeight; ?>px;
}
<?php if ($thumbnailsPosition == 'right') { ?>
#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-full-screen-button {right:<?php echo $thumbnailWidth - 4; ?>px;}
<?php } ?>
@media (max-width: 480px) {
	#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-thumbnails-container,
	#ap-smart-layerslider-<?php echo $ext_id; ?> .sp-thumbnails .sp-thumbnail .sp-thumbnail-text {
		width: 120px;
		height:<?php echo $thumbnailHeight; ?>px;
	}
}
</style>
<?php } ?>
<script type="text/javascript">
;(function($){
	$(document).ready(function() {	
	$('#ap-smart-layerslider-<?php echo $ext_id; ?>').sliderPro({
<?php if ($show_thumbnails == "1" && $thumbnailsPosition == 'right' || $thumbnailsPosition == 'left') { ?>
	width: <?php echo $image_width - $thumbnailWidth; ?>,
	height: <?php echo $image_height - ($image_width - $thumbnailWidth) * $thumbnailWidth / $image_width; ?>,
<?php } else { ?>
	width: <?php echo $image_width; ?>,
	height: <?php echo $image_height; ?>,
<?php } ?>
	<?php echo $forceSize ? "forceSize:'".$forceSize."',\n" : "";?>
	<?php echo $visibleSize ? "visibleSize:'".$visibleSize."',\n" : "";?>
	slideDistance: <?php echo $slideDistance; ?>,
	<?php echo $responsive == 1 ? "responsive:true,\n" : "responsive:false,\n";?>
	<?php echo $imageScaleMode ? "imageScaleMode:'".$imageScaleMode."',\n" : "";?>
	<?php echo $autoHeight == 1 ? "autoHeight:true,\n" : "";?>
	<?php echo $autoScaleLayers == 1 ? "autoScaleLayers:true,\n" : "autoScaleLayers:false,\n";?>
	<?php echo $waitForLayers == 1 ? "waitForLayers:true,\n" : "waitForLayers:false,\n";?>	
	<?php echo $orientation ? "orientation:'".$orientation."',\n" : "";?>
	<?php echo $loop == 1 ? "loop:true,\n" : "loop:false,\n";?>	
	<?php echo $shuffle == 1 ? "shuffle:true,\n" : "";?>
	<?php echo $fullScreen == 1 ? "fullScreen:true,\n" : "";?>
	<?php /* Fade */ ?>
	<?php echo $fadeEffect == 1 ? "fade:true,\n" : "";?>
	<?php echo $fadeOutPreviousSlide == 1 ? "fadeOutPreviousSlide:true,\n" : "fadeOutPreviousSlide:false,\n";?>
	<?php echo $fadeEffect == 1 ? "fadeDuration:".$fadeDuration.",\n" : "";?>
	<?php /* Autoplay */ ?>
	<?php echo $autoplay == 1 ? "autoplay:true,\n" : "autoplay:false,\n";?>
	<?php echo $autoplay == 1 ? "autoplayDelay:".$autoplayDelay.",\n" : "";?>
	<?php echo $autoplay == 1 ? "autoplayOnHover:'".$autoplayOnHover."',\n" : "";?>
	<?php /* Video settings */ ?>
	<?php echo $reachVideoAction ? "reachVideoAction:'".$reachVideoAction."',\n" : "";?>
	<?php echo $leaveVideoAction ? "leaveVideoAction:'".$leaveVideoAction."',\n" : "";?>
	<?php echo $playVideoAction ? "playVideoAction:'".$playVideoAction."',\n" : "";?>
	<?php echo $pauseVideoAction ? "pauseVideoAction:'".$pauseVideoAction."',\n" : "";?>
	<?php echo $endVideoAction ? "endVideoAction:'".$endVideoAction."',\n" : "";?>
	<?php /* Thumbnails */ ?>
	<?php echo $show_thumbnails == 1 ? "thumbnailWidth:".$thumbnailWidth.",\n" : "";?>
	<?php echo $show_thumbnails == 1 ? "thumbnailHeight:".$thumbnailHeight.",\n" : "";?>
	<?php echo $show_thumbnails == 1 ? "thumbnailsPosition:'".$thumbnailsPosition."',\n" : "";?>
	<?php echo $show_thumbnails == 1 && $thumbnailPointer == 1 ? "thumbnailPointer:true,\n" : "";?>
	<?php echo $show_thumbnails == 1 && $thumbnailArrows == 1 ? "thumbnailArrows:true,\n" : "";?>
	<?php /* Arrows and Buttons */ ?>		
	<?php echo $show_arrows == 1 ? "arrows:true,\n" : "arrows:false,\n";?>
	<?php echo $show_buttons == 1 ? "buttons:true,\n" : "buttons:false,\n";?>
	breakpoints: {
		979: {
			thumbnailsPosition: 'bottom'
		},
		480: {
			thumbnailsPosition: 'bottom',
			thumbnailWidth: 120,
			thumbnailHeight: 50
		}
	}
});
	$("#ap-smart-layerslider-<?php echo $ext_id; ?> .ap-layer").not(".sp-layer").contents().filter(function(){return this.nodeType == 3;}).remove();
	$("#ap-smart-layerslider-<?php echo $ext_id; ?> .ap-layer").children().not(".sp-layer").remove();
  });<?php /* end doc ready */ ?>
})(jQuery);
</script>