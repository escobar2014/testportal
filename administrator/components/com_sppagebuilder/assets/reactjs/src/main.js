import React from 'react';
import ReactDOM from 'react-dom';
import { createStore } from 'redux';
import { connect } from 'react-redux';
import { Provider } from 'react-redux';
import { Modal,ModalManager} from './helpers/index'

import pageBuilderCont from './reducers/Index.js';
import Pagebuilder from './components/Pagebuilder.js';
import PageTools from './components/PageTools.js';

let store = createStore(pageBuilderCont);

const render = () => {
  ReactDOM.render(
    <Provider store={store}>
        <Pagebuilder />
    </Provider>,
    document.getElementById('container')
  );
};
render();

const render_tools = () => {
  ReactDOM.render(
    <Provider store={store}>
        <PageTools />
    </Provider>,
    document.getElementById('sp-pagebuilder-page-tools')
  );
};
render_tools();

jQuery(document).ready(function($) {

  $(document).on('click', '.settings-form input:checkbox', function(){
    var $this = $(this),
        currentVal = $(this).val();
    if (currentVal == 0) {
      $this.val(1)
    }else{
      $this.val(0);
    }
  });

  // Generate Repeatbale Group
  $.fn.generateGroup = function( count, clone  ){
    return this.each(function(){
      $(this).find('input,select').each(function(){
        var $that = $(this);
        $that.attr('name', this.name.replace(/\d/, count ));
        if(!clone){
          $that.val('');
        }
      });
    });
  }

  // Remove unique ids
  $.fn.removeUniqueId = function(){
    $('.sp-pagebuilder-grouped-wrap').find('select').chosen('destroy');

    var $richEditor = $('.sp-pagebuilder-modal-content').find('.wf-editor, .mce_editable');
    $richEditor.each(function() {
      $(this).prev('.wf-editor-header').empty();
      tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
    });

    $('.sp-pagebuilder-media-input').each(function() {
      $(this).removeAttr('id');
    });
  }

  // Add unique ids
  $.fn.addUniqueId = function(){
    // Editor

    var $richEditor = $('.sp-pagebuilder-modal-content').find('.wf-editor, .mce_editable');

    if ($richEditor.length) {
      $richEditor.each(function() {
        var editor_id = 'sp-pagebuilder-editor-' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
        $(this).removeAttr('id').attr('id', editor_id);

        if (typeof WFEditor == 'undefined') {
          tinymce.execCommand('mceAddEditor', true, editor_id);
        }

        if (typeof WFEditor !== 'undefined') {
          WFEditor.create(editor_id);
        }
      });
    }

    // Media
    $('.sp-pagebuilder-media-input').each(function() {
      var media_id = 'media-' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
      $(this).removeAttr('id').attr('id', media_id);
    });

    $('.sp-pagebuilder-grouped-wrap select').each(function() {
      var select_id = 'sp_pagebuilder_chosen_' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
      $(this).removeAttr('id').attr('id', select_id);
    });

    $('.sp-pagebuilder-grouped-wrap').find('select').chosen({
      "disable_search_threshold":10,
      allow_single_deselect:true
    });
  }

  $.fn.repeatableItemLabel = function(){
      this.find('.sp-pagebuilder-grouped-item').each(function(index){
        var newIndex = index + 1;
        $(this).find('.sp-pagebuilder-repeatable-item-title').text('Item '+ newIndex );
      });
  }

  // Collapse Repeatbale
  $(document).on('click', '.sp-pagebuilder-repeatable-item-title', function(event) {
    event.preventDefault();
    var $items = jQuery(this).closest('.sp-pagebuilder-grouped').find('.sp-pagebuilder-grouped-item');
    if($(this).parent().hasClass('sp-pagebuilder-grouped-item-opened')) {
      $(this).parent().removeClass('sp-pagebuilder-grouped-item-opened');
      $(this).parent().next().slideToggle();
    } else {
      $items.find('.sp-pagebuilder-repeatable-toggler').removeClass('sp-pagebuilder-grouped-item-opened');
      $items.find('.sp-pagebuilder-grouped-item-collapse').slideUp();
      $(this).parent().addClass('sp-pagebuilder-grouped-item-opened');
      $(this).parent().next().slideDown();
    }
  });

  $(document).on('click','.sp-pagebuilder-clone-grouped-item',function(event){
      event.preventDefault();
      $().removeUniqueId();

      var $that     = $(this),
          $gparent  = $that.closest('.sp-pagebuilder-grouped'),
          $parent   = $that.closest('.sp-pagebuilder-grouped-item'),
          count     = $gparent.data('field_no'),
          key       = count + 1,
          $clone    = $parent.clone();

      $clone.generateGroup(key, true);
      $gparent.data('field_no',key);
      $clone.insertAfter($parent);
      $gparent.repeatableItemLabel();
      $().addUniqueId();
  });

  // add grouped item in addon
  $(document).on('click', '.sp-pagebuilder-add-grouped-item', function(event) {
    event.preventDefault();

    $().removeUniqueId();
    var $parent = $(this).next('.sp-pagebuilder-grouped'),
        count   = $parent.data('field_no'),
        key     = count + 1;

    var $form 		= $parent.children('.sp-pagebuilder-grouped-item').first(),
        $cloned 	= $form.clone();

    $cloned.generateGroup(key, false);
    $parent.append( $($cloned) )
              .data('field_no',key);

    $parent.repeatableItemLabel();
    $().addUniqueId();
  });

  // remove grouped item from addon
  $(document).on('click','.sp-pagebuilder-remove-grouped-item',function(event){
    event.preventDefault();

    var $gparent = $(this).closest('.sp-pagebuilder-grouped'),
        itemLenght = $gparent.find('.sp-pagebuilder-grouped-item').length;
    if (itemLenght <= 1) {
      return;
    }

    $(this).closest('.sp-pagebuilder-grouped-item').remove();
    $gparent.repeatableItemLabel();
  });
});
