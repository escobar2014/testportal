<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$doc = JFactory::getDocument();

$show_readmore_btn = '';
$article_style = '';
$sppb = '';
$overlay_color = '';
$zoom = '';
$grayscale = '';
$blur = '';
$overlay_hover_effects = '';
$post_formats_bottom_margin = '';
	
if ($params->get('loadmore_button') == 'default') {
	$loadmore_start_color = '#777';
	$sppb = 'sppb-';
} else {
	$loadmore_start_color = '#fff';
}
if ($params->get('readmore_button') == 'default') {
	$sppb_readmore = 'sppb-';
} else {
	$sppb_readmore = '';
}

$cols_color = ($params->get('cols_color') != '') ? 'background-color:'. $params->get('cols_color') .';' : '';
$cols_spacing = $params->get('cols_spacing', '15');

if ($cols_spacing != 0) {
	$cols_spacing_neg_margin = ' -'. $cols_spacing .'px';
	$cols_spacing_margin = $cols_spacing .'px';
} else {
	$cols_spacing_neg_margin = '';
	$cols_spacing_margin = '0';
}

if ($params->get('inner_spacing') != 0) {
	$inner_spacing = $params->get('inner_spacing') .'px';
} else {
	$inner_spacing = '0';
}

// LTR or RTL
$rtl_enable = ($params->get('rtl_enable', 0) == 1 && $params->get('columns') != 1) ? 'originLeft:false,' : '';

// Equal Heights for Columns
($params->get('equal_heights') == 1 && $params->get('columns') != 1) ? $equal_heights_js = '$(".match-height").matchHeight();' : $equal_heights_js = '';

if ($params->get('show_readmore') != 0) {
	$show_readmore_btn = '#ajax_posts_'. $module->id .' .ajax-post .inner .readmore.pull-left > a {margin:10px 10px 0 0;}#ajax_posts_'. $module->id .' .ajax-post .inner .readmore.pull-right > a {margin:10px 0 0 10px;}';
}

if ($params->get('show_title') != 0) {
	$title_size = $params->get('title_size');
} else {
	if ($params->get('article_style') == 3) {
		$title_size = $params->get('title_size');
	 } else {
		$title_size = '22';
	}
}

if ($params->get('show_introtext') != 0) {
	$introtext_size = $params->get('introtext_size');
} else {
	$introtext_size = '15';
}

if ($params->get('article_style', 1) != 2) {
	if ($params->get('intro_format', 1) == 1) {
		$article_style = '#ajax_posts_'. $module->id .' .ajax-post .inner > div.article_style {margin:-' . $inner_spacing .' -' . $inner_spacing .' 0}';
		
		$post_formats_bottom_margin = '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .inner [class^="entry-"] {margin-bottom:'. $inner_spacing .';}';
		
	} else {
		$article_style = '#ajax_posts_'. $module->id .' .ajax-post .inner > div.article_style {margin:-' . $inner_spacing .'}';
	}
} 

//  Overlay Effects
$effect = $params->get('overlay_effects'); 

if(is_array($effect) && count($effect)) {
	if(in_array('zoom', $effect)) {
	  $zoom = 'transform: translateZ(0) scale(1.1);-webkit-transform: translateZ(0) scale(1.1);';
	} 

	if(in_array('grayscale', $effect)) {
	   $grayscale = ' grayscale(100%)';
	} else {
	   $grayscale = ' grayscale(0%)';
	}

	if(in_array('blur', $effect)) {
	  $blur = ' blur(0.2em)';
	} 
}

if ($params->get('article_style', 1) == 3) {
	$overlay_hover_effects = '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .overlay a:hover img.post-img {'. $zoom .'-webkit-filter:'.$blur . $grayscale.';filter:'.$blur . $grayscale.';}';
	if ($params->get('overlay_color') != '') {
		$overlay_color = '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .overlay a:hover:after {background:'. $params->get('overlay_color') .'}';
	}
}
// Add styles
$style = ''
		. '#ajax_loadmore_'. $module->id .' .btn_text{color:' . $params->get('loadmore_color') .';}'
		. '#ajax_loadmore_'. $module->id .' .spinner > div{background:' . $params->get('loadmore_color', $loadmore_start_color) .';}'
		. '#ajax_posts_'. $module->id .' .ajax-posts{margin:0' . $cols_spacing_neg_margin .'}'
		. '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .inner{'.$cols_color.'padding:'. $inner_spacing .';margin:' . $cols_spacing_margin .';}'
		. $article_style . $overlay_hover_effects . $overlay_color
		. '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .overlay .intro-image .caption-content,#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .inner .aga_heading{font-size:' . $title_size .'px;line-height:1.4;}'
		. '#ajax_posts_'. $module->id .' .ajax-posts .ajax-post .inner .item-intro {font-size:' . $introtext_size .'px;line-height:1.6;}'
		. '#ajax_posts_'. $module->id .' .ajax-post .inner .no-intro-img{margin-top:0;}'
		. $post_formats_bottom_margin
		. $show_readmore_btn;		 
$doc->addStyleDeclaration($style);
	
if (count($total) == 0) { ?>
<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <p class="centered"><?php echo JText::_('MOD_AJAX_INTRO_ARTICLES_ALERT') ?></p>
</div>
<?php } else { ?>
<div id="ajax_posts_<?php echo $module->id; ?>" class="ajax_posts <?php echo $moduleclass_sfx; ?> clearfix">
    <div id="masonry_items_<?php echo $module->id; ?>" class="ajax-posts masonry_items row-fluid clearfix">
        <?php require JModuleHelper::getLayoutPath('mod_ajax_intro_articles', 'default_ajax');?>  
    </div>
    <input type="hidden" name="count_<?php echo $module->id; ?>" value="<?php echo $params->get('count', 3); ?>"/>
    <?php if (count($total) != 0 && (count($total) > $limit)) { ?>
        <div id="timeline_<?php echo $module->id; ?>" class="loader_footer container-fluid readmore clearfix">
        <button id="ajax_loadmore_<?php echo $module->id; ?>" class="load-more-ajax btn <?php echo $sppb; ?>btn-<?php echo $params->get('loadmore_button', 'default'); ?> clearfix">
            <div class="spinner" style="display:none;">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
            <span class="btn_text"><?php echo $params->get('loadmore_btn_text', JText::_('MOD_AJAX_INTRO_ARTICLES_AJAX_LOADER')); ?></span>
        </button>
        </div>
    <?php } ?>
</div><?php 
	// Add JS and minify
	$js = 'jQuery(function($){
		var $container=$("#masonry_items_'.$module->id.'");
		var $start='.$limit.';
		var $limit='.$ajaxlimit.';
		$container.imagesLoaded(function(){
			$($container).masonry({'.$rtl_enable.'itemSelector:\'.masonry_item\'});
		}); 
		$(document).on(\'click\',\'#ajax_loadmore_'.$module->id.'\',function(e){ 
			e.preventDefault();
			var value=$("input[name=count_'.$module->id.']").val(),
			request={
			\'option\':\'com_ajax\',
			\'module\':\'ajax_intro_articles\',
			\'cmd\':\'load\',
			\'data\':\''.base64_encode($module->title).'\',
			\'format\':\'raw\',
			\'start\':$start,
			\'limit\':$limit,
			\'moduleID\':'.$module->id.',
			\'Itemid\':\''.JFactory::getApplication()->input->get('Itemid').'\'
			};
			$.ajax({
				type:\'GET\',
				data:request,
				beforeSend:function(response){
					var loadmore=$("#ajax_loadmore_'.$module->id.'");
					var $loadmore_width=$(loadmore).width();
					loadmore.find(".spinner").css({"width":$loadmore_width + \'px\',"margin":"0 0.01em"}).show();
					loadmore.find(".btn_text").hide();
				},
				success:function (response){
					$start+=$limit;
					var loadmore=$("#ajax_loadmore_'.$module->id.'");
					var $container=$("#ajax_posts_'.$module->id.' > #masonry_items_'.$module->id.'");
					var $elems=$(response);
					$elems.appendTo($container).addClass("'.$params->get('loadmore_effect', 'appear-in').'");
					$("#masonry_items_'.$module->id.'").imagesLoaded(function(){
						$("#masonry_items_'.$module->id.'").masonry({
						  '.$rtl_enable.'
						  transitionDuration:0,
						  itemSelector:\'.masonry_item\'
						})
						.masonry(\'appended\',$elems);
						'. $equal_heights_js .'
						return false;
					});			
					$("input[name=count_'.$module->id.']").val($("#ajax_posts_'.$module->id.' .ajax-post").size());
					loadmore.find(".spinner").hide();
					loadmore.find(".btn_text").show();
					$(\'[data-toggle="tooltip"]\').tooltip();
					if($("input[name=count_'.$module->id.']").val() == '. count($total) .'){
						loadmore.hide();
						$("#ajax_posts_'.$module->id.' > .loader_footer").addClass("done");
					} 
				},
				error:function(response){
					var data=\'\',
					obj=$.parseJSON(response.responseText);
					for(key in obj){
						data=data + \' \' + obj[key] + \'<br/>\';
					}
				}
			});
			return false;
		});
	});'; 
	$js = preg_replace(array('/([\s])\1+/', '/[\n\t]+/m'), '', $js); // Remove whitespace
	$doc->addScriptdeclaration($js);
} ?>