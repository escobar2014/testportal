function ap_HideOptions(a){if((/^\s*$/).test(a)){return}fields=a.split(',');for(var i=0;i<fields.length;i++){ap_HideOption(fields[i])}}function ap_ShowOptions(a){if((/^\s*$/).test(a)){return}fields=a.split(',');for(var i=0;i<fields.length;i++){if((/^\s*$/).test(fields[i])){continue}ap_ShowOption(fields[i])}}function ap_ShowOptionsByControl(a,b){if((/^\s*$/).test(a)){return}if($('jform_params_'+a)==null){return}var c=$('jform_params_'+a).get("value");var d=b[c];if((/^\s*$/).test(d)){return}fields=d.split(',');for(var i=0;i<fields.length;i++){if((/^\s*$/).test(fields[i])){continue}ap_ShowOption(fields[i])}}function ap_ShowOption(a){var b=$('jform_params_'+a);if(b==null){b=$('jform_params_'+a+'-lbl')}if(b==null){return}var c=b.getParent('div.control-group');if(c==null){c=b.getParent('li')}if(c!==null&&c.hasClass('hide')){c.removeClass('hide')}}function ap_HideOption(a){var b=$('jform_params_'+a);if(b==null){b=$('jform_params_'+a+'-lbl')}if(b==null){return}var c=b.getParent('div.control-group');if(c==null){c=b.getParent('li')}if(c!==null&&!c.hasClass('hide')){c.addClass('hide')}}function ap_TogglerDisabledParams(a,b){if((/^\s*$/).test(a)){return}if($('jform_params_'+a)==null){return}}

// Parent/Child options
jQuery(function($){
    "use strict";

    $(document).ready(function(){

        var childParentEngine = function(){
            var classes = new Array();
            $("fieldset.parent, select.parent").each(function(){
              var eleclass = $(this).attr('class').split(/\s/g);
              var $key = $.inArray("parent", eleclass);
              if( $key!=-1 ){
                classes.push( eleclass[$key+1] ); 
              }
            });

            $("fieldset.parent, select.parent").each(function(){

              var parent = $(this);
              var eleclass = $(this).attr('class').split(/\s/g);
              var childClassName = '.child';
              var conditionClassName = '';
              var i;

              for (i=0;i<eleclass.length;i++) {
                if( $.inArray(eleclass[i], classes) < 0 ) {
                  continue;
                } else {

                  var elecls =  '.' + eleclass[i]; 

                  $(childClassName+elecls).parents('.control-group').hide();
                  if( $(parent).prop('type')=='fieldset' ){
                    var selected = $(parent).find('input[type=radio]:checked');
                    var radios = $(parent).find('input[type=radio]');
                    var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                    var childitem =  $.trim(childClassName+elecls+activeItems);
                    setTimeout(function(){
                      $(childitem).parents('.control-group').show();
                    }, 100);

                    $(radios).on("click", function(event){
                      $(childClassName+elecls).parents('.control-group').hide();
                      $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('.control-group').fadeIn(350);
                    });

                  } else if( $(parent).prop('type')=='select-one' ) {
                    var element = $(parent);
                    var selected = $(parent).find('option:selected');
                    var option = $(parent).find('option');
                    var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                    var childitem =  $.trim(childClassName+elecls+activeItems);
                    setTimeout(function(){
                      $(childitem).parents('.control-group').show();
                    }, 100);

                    $(element).on("change", function(event){
                      $(childClassName+elecls).parents('.control-group').hide();
                      $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('.control-group').fadeIn(350);
                    });

                  }
                }
              }
            });
        }//end childParentEngine
        childParentEngine();
	
    });
				
});