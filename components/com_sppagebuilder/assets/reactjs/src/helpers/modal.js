import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LinkWithTooltip from './tooltip';
import { Modal, ModalManager } from './index'
import deepEqual from 'deep-equal'
import { connect } from 'react-redux'
import deepcopy from 'deepcopy'

class SpPageBuilderModal extends Component {

  constructor(props) {
    super(props);

    if(this.props.sectionType == 'list'){
      var addonsList = [];
      for(var key in addonsJSON){
        addonsList.push(addonsJSON[key]);
      }
      var newAddonsList = deepcopy(addonsList);
    }else{
      var newAddonsList = [];
    }

    this.state = {
      type: this.props.sectionType,
      rowIndex: this.props.rowIndex,
      colIndex: this.props.colIndex,
      addons: newAddonsList,
      categories: addonCats,
      activeCat: 'All'
    };
  }

  componentDidMount() {

    if(this.props.sectionType == 'list'){
      if( typeof this.props.innerColIndex !=='undefined' ) {
        this.setState({
          addonIndex: this.props.addonIndex,
          innerColIndex: this.props.innerColIndex
        });
      } else {
        this.setState({
          addonIndex: ''
        });
      }
      return;
    }

    var ajaxData = {
      type:       this.props.sectionType,
      rowIndex:   this.props.rowIndex,
    };

    if ( typeof this.props.settings !== 'undefined' ) {
      ajaxData.settings = this.props.settings;
    };

    if ( typeof this.props.colIndex !== 'undefined' ) {
      ajaxData.colIndex = this.props.colIndex;
    };

    if ( typeof this.props.addonIndex !== 'undefined' ) {
      ajaxData.addonIndex = this.props.addonIndex;
    };

    if ( typeof this.props.addonName !== 'undefined' ) {
      ajaxData.addonName = this.props.addonName;
    };

    if ( typeof this.props.innerColIndex !== 'undefined' ) {
      ajaxData.innerColIndex = this.props.innerColIndex;
    };

    if ( typeof this.props.addonInnerIndex !== 'undefined' ) {
      ajaxData.addonInnerIndex = this.props.addonInnerIndex;
    };

    this.loadSettingsFromServer(ajaxData);
  }

  loadSettingsFromServer(ajaxData){

    jQuery.ajax({
      type: 'POST',
      url: 'index.php?option=com_sppagebuilder&view=ajax&format=json&callback=setting',
      dataType: 'json',
      data: ajaxData,
      cache: false,
      success: function(response) {

        if ( response.status ) {

          if (ajaxData.type === 'row' || ajaxData.type === 'column' || ajaxData.type === 'inner_row') {
            this.setState({
              type: response.type,
              rowIndex: response.rowIndex,
              colIndex: response.columnIndex,
              addonIndex: response.addonIndex,
              data: response.data,
            });
          }

          if (ajaxData.type === 'addon') {
            this.setState({
              type: response.type,
              rowIndex: response.rowIndex,
              colIndex: response.columnIndex,
              addonIndex: response.addonIndex,
              data: response.data,
              addonName: ajaxData.addonName
            });
          }

          if (ajaxData.type === 'inner_column') {
            this.setState({
              type: response.type,
              rowIndex: response.rowIndex,
              colIndex: response.columnIndex,
              addonIndex: response.addonIndex,
              data: response.data,
              innerColIndex: ajaxData.innerColIndex
            });
          }

          if (ajaxData.type === 'inner_addon') {
            this.setState({
              type: response.type,
              rowIndex: response.rowIndex,
              colIndex: response.columnIndex,
              addonIndex: response.addonIndex,
              data: response.data,
              addonName: ajaxData.addonName,
              innerColIndex: ajaxData.innerColIndex,
              addonInnerIndex: ajaxData.addonInnerIndex
            });
          }
        }

        jQuery('#sp-pagebuilder-modal-actions').removeClass('hidden');

      }.bind(this)
    });
  }

  saveSettings( e ) {
    e.preventDefault();

    var $ = jQuery,
    formNode = ReactDOM.findDOMNode(this.refs.settingForm),
    formData = {};

    $(formNode).find('.sp-pagebuilder-parent-input-field').each(function(){
      var $that = $(this);

      if ($that.hasClass('sp-pagebuilder-grouped-wrap')) {
        var group = [],
        field_name = $that.data('field_name');

        $that.find('.sp-pagebuilder-grouped-item').each(function(i) {
          var subField = {};
          $(this).find('.sp-pagebuilder-form-group').each(function() {

            var $element = $(this).find('.sp-pagebuilder-addon-input');

            if ($element.length < 1) {
              $element = $(this).find('.wf-editor, .mce_editable');
              if ($element.length > 0) {
                if (typeof WFEditor !== 'undefined') {
                  var fieldId = $element.attr('id');
                  var val = WFEditor.getContent(fieldId);
                } else {
                  var val = $element.val();
                }
              }
            } else {
              var val = $element.val();
            }

            var list    = $element.attr('name');
            if (list != undefined) {
              var list    = list.split('['),
                  name    = list[2].replace(']','');
            }

            if(list != undefined && name != undefined) {
              subField[name] = val;
            }
          });
          group[i] = subField;
        });

        formData[field_name] = group;
      } else {
        var $element = $that.find('.sp-pagebuilder-addon-input');
        if ($element.length < 1) {
          $element = $that.find('.wf-editor,.mce_editable');
          if ($element.length > 0) {
            if (typeof WFEditor !== 'undefined') {
              var fieldId = $element.attr('id');
              var val = WFEditor.getContent(fieldId);
            } else {
              var val = $element.val();
            }
          }
        } else {
          var val = $element.val();
        }
        var name = $element.attr('name');

        if(name != undefined) {
          formData[name] = val;
        }
      }
    });

    var options = {
      type: this.state.type,
      index: this.state.rowIndex,
      settings: {
        formData: formData
      }
    };

    if ( this.state.type !== 'row' ) {
      options.settings.colIndex = this.state.colIndex;
    };

    if ( this.state.type !== 'row' || this.state.type !== 'column' ) {
      options.settings.addonIndex = this.state.addonIndex;
    };

    if ( this.state.type === 'inner_column' || this.state.type === 'inner_addon' ) {
      options.settings.innerColIndex  = this.state.innerColIndex;
    };

    if ( this.state.type === 'addon' || this.state.type === 'inner_addon' ) {
      options.settings.addonName = this.state.addonName;
    };

    if (this.state.type === 'inner_addon') {
      options.settings.addonInnerIndex  = this.state.addonInnerIndex;
    };

    ModalManager.close();

    if (this.state.type === 'addon' || this.state.type === 'inner_addon') {
      if(this.props.settings != undefined) {
        if(!deepEqual(this.props.settings,formData)){
          let addonObj ={
            id: this.props.addonId,
            settings: formData,
            name: options.settings.addonName
          };

          var addonCallback = this._callbackAjaxContent(addonObj);
          options.settings.htmlContent = addonCallback.content;
          options.settings.assets = addonCallback.assets;
          this.props.saveSetting( options );
        }
      } else {
        var newAddonId = (new Date).getTime();
        let addonObj ={
          id: newAddonId,
          settings: formData,
          name: options.settings.addonName
        };

        var addonCallback = this._callbackAjaxContent(addonObj);

        options.settings.addonId = newAddonId;
        options.settings.htmlContent = addonCallback.content;
        options.settings.assets = addonCallback.assets;
        this.props.saveSetting( options );
      }
    }
    else
    {
      if(!deepEqual(this.props.settings,formData)){
        this.props.saveSetting( options );
      }
    }
  }

  _callbackAjaxContent(addon){
    let content = '';
    let assets = '';
    let callbackData = {
      addon: addon,
    };

    jQuery.ajax({
      type: 'POST',
      url: 'index.php?option=com_sppagebuilder&view=ajax&format=json&callback=addon',
      dataType: 'json',
      data: callbackData,
      cache: false,
      async: false,
      success: function(response) {
        if ( response.status ) {
          content = response.html;
          assets = response.assets;
        }
      }
    });

    return {content: content, assets: assets};
  }

  addonSettingsNew( addonName ) {

    addonName = addonName.replace(/sp_/g, '');

    var ajaxData = {
      rowIndex:   this.state.rowIndex,
      colIndex:   this.state.colIndex,
      addonName:  addonName
    };

    if (typeof this.state.addonIndex !== 'undefined') {
      ajaxData.addonIndex = this.state.addonIndex;
    };

    if (typeof this.state.innerColIndex !== 'undefined') {
      ajaxData.innerColIndex = this.state.innerColIndex;
      ajaxData.type = 'inner_addon';
    }else{
      ajaxData.type = 'addon';
    }

    if (typeof this.state.addonInnerIndex !== 'undefined') {
      ajaxData.addonInnerIndex = this.state.addonInnerIndex;
    };

    this.loadSettingsFromServer( ajaxData );
  }

  // Parent Child
  parentChildRelation() {
    // Normal
    jQuery('.sp-pagebuilder-parent-input-field:not(.sp-pagebuilder-grouped-wrap) *[data-depends]*').each(function() {
      if(jQuery(this).attr('data-depends') != undefined) {
        var depends = jQuery.parseJSON(jQuery(this).attr('data-depends'));

        var condition = [];
        for (var i = 0; i < depends.length; i++) {
          var operator = depends[i][1];
          var operand1 = jQuery('[name="'+ depends[i][0] +'"]').val();
          var operand2 = depends[i][2];
          if(operator == '=') {
            if(operand1 == operand2) {
              condition[i] = 'true';
            } else {
              condition[i] = 'false';
            }
          } else if(operator == '!=') {
            if(operand1 != operand2) {
              condition[i] = 'true';
            } else {
              condition[i] = 'false';
            }
          }
        }

        if(jQuery.inArray('false', condition) > -1) {
          jQuery(this).parent().hide();
        } else {
          jQuery(this).parent().fadeIn(300);
        }
      }
    });

    // Repeatable
    jQuery('.sp-pagebuilder-repeatable-input-field *[data-depends]*').each(function() {
      if(jQuery(this).attr('data-depends') != undefined) {
        var $this = jQuery(this);
        var depends = jQuery.parseJSON($this.attr('data-depends'));
        var name = $this.find('.sp-pagebuilder-addon-input').attr('name').split('[');

        if(name.length > 1) {
          var condition_repeatable = [];
          for (var i = 0; i < depends.length; i++) {
            var operator = depends[i][1];
            var operand1 = jQuery('[name="' + name[0] + '[' + name[1] + '[' + depends[i][0] + ']"]').val();
            var operand2 = depends[i][2];
            if(operator == '=') {
              if(operand1 == operand2) {
                condition_repeatable[i] = 'true';
              } else {
                condition_repeatable[i] = 'false';
              }
            } else if(operator == '!=') {
              if(operand1 != operand2) {
                condition_repeatable[i] = 'true';
              } else {
                condition_repeatable[i] = 'false';
              }
            }
          }

          if(jQuery.inArray('false', condition_repeatable) > -1) {
            jQuery(this).parent().hide();
          } else {
            jQuery(this).parent().fadeIn(300);
          }
        }
      }
    });
  }

  settingsColorPicker(){
    if (this.state.type == 'list') {
      return;
    }

    jQuery('.minicolors').minicolors({
      control: 'hue',
      format: 'rgb',
      position: 'bottom',
      opacity: true,
      theme: 'bootstrap'
    });

    tinymce.init({
      force_br_newlines : true,
      force_p_newlines : false,
      forced_root_block : '',
      toolbar_items_size: "small",
      invalid_elements : "script,applet",

      content_css : "administrator/components/com_sppagebuilder/assets/css/tinymce.css",

      theme: 'modern',
      setup: function (editor) {
        editor.on('change', function () {
          editor.save();
        });
      },
      plugins: [
        "advlist autolink lists link charmap preview image",
        "searchreplace code fullscreen",
        "media contextmenu paste"
      ],
      relative_urls : true,
      document_base_url : pagebuilder_base,
      image_class_list: [
        {title: 'None', value: ''},
        {title: 'Left', value: 'pull-left'},
        {title: 'Right', value: 'pull-right'}
      ],

      toolbar: "styleselect | bold italic fontsizeselect | alignleft aligncenter alignright alignjustify | blockquote | bullist numlist | link image fullscreen"
    });

    // Media
    jQuery('.sp-pagebuilder-media-input').each(function() {
      var self = jQuery(this),
      media_id = 'media-' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
      self.removeAttr('id').attr('id', media_id);
    });

    jQuery('.sp-pagebuilder-grouped-wrap select').each(function() {
      var select_id = 'sp_pagebuilder_chosen_' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
      jQuery(this).removeAttr('id').attr('id', select_id);
    });

    // Chosen
    jQuery('.sp-pagebuilder-modal').find('select').chosen({
      "disable_search_threshold":10,
      allow_single_deselect:true
    });

    // Parent Child
    var that = this;
    that.parentChildRelation();
    jQuery(document).on('change', '.sp-pagebuilder-addon-input', function(event) {
      that.parentChildRelation();
    });

    jQuery('.wf-editor, .mce_editable').each(function() {
      var self = jQuery(this),
      editor_id = 'sp-pagebuilder-editor-' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
      self.removeAttr('id').attr('id', editor_id);
      if (typeof WFEditor == 'undefined'){
        tinymce.execCommand('mceAddEditor', true, editor_id);
      }
      if (typeof WFEditor !== 'undefined') {
        WFEditor.create(editor_id);
      }
    });
  }

  componentDidUpdate(){
    this.settingsColorPicker();
    this.addonShortable();
  }

  addonShortable(){
    jQuery( ".sp-pagebuilder-grouped" ).sortable({
      placeholder: "ui-state-highlight",
      handle: '.sp-pagebuilder-move-repeatable',
      forcePlaceholderSize: true,
      axis: 'y',
      opacity: 0.8,
      tolerance: 'pointer',
      start: function(event, ui){
        var $richEditor = jQuery('.sp-pagebuilder-modal-content').find('.wf-editor, .mce_editable');
        $richEditor.each(function() {
          jQuery(this).prev('.wf-editor-header').empty();
          tinymce.execCommand('mceRemoveEditor', false, jQuery(this).attr('id'));
        });
      },
      stop: function(event, ui){
        var $richEditor = jQuery('.sp-pagebuilder-modal-content').find('.wf-editor, .mce_editable');

        if ($richEditor.length) {
          $richEditor.each(function() {
            var editor_id = 'sp-pagebuilder-editor-' + Math.floor(Math.random() * (1e6 - 1 + 1) + 1);
            jQuery(this).removeAttr('id').attr('id', editor_id);

            if (typeof WFEditor == 'undefined') {
              tinymce.execCommand('mceAddEditor', true, editor_id);
            }

            if (typeof WFEditor !== 'undefined') {
              WFEditor.create(editor_id);
            }
          });
        }

        jQuery(this).find('.sp-pagebuilder-grouped-item').each(function(index){
          var newIndex = index + 1;
          jQuery(this).find('.sp-pagebuilder-repeatable-item-title').text('Item '+ newIndex );
        })
      }
    });
  }

  filterCategoryClickHandle(cat){
    const { addons } = this.state;

    var filteredAddons = addons.map(function(addon){
      if (addon.category == cat || cat == 'All') {
        addon.visibility = true;
      }else{
        addon.visibility = false;
      }
      return addon;
    });

    this.setState({
      activeCat: cat,
      addons: filteredAddons
    });
  }

  // Seacrh Filter Handle
  searchChangeHandle()
  {
    const { addons, activeCat } = this.state;
    const item = this.refs.searchItem.value;
    const regex = new RegExp('.*?' + item + '.*?', 'i');

    var resultAddons = addons.map(function(addon){
      if ( regex.test(addon.title)) {
        addon.visibility = true;
      } else {
        addon.visibility = false;
      }
      return addon;
    });

    this.setState({
      activeCat: 'All',
      addons:resultAddons
    });
  }

  __renderCategories(){
    return(
      <ul>
        {this.state.categories.map((category,index)=>
          <li
            className={this.state.activeCat == category.name?'sp-pagebuilder-addon-category-item active':'sp-pagebuilder-addon-category-item '}
            key={index}
            data-category={category.name}
            onClick={(e)=>{
              this.filterCategoryClickHandle(category.name);
            }}>
            <a href="javascript:void(0)"><span>{category.name}</span><span className="sp-pagebuilder-addons-count">{category.count}</span></a>
          </li>
        )}
      </ul>
    )
  }

  render() {
    let customClass = '';
    let modalTitle = '';

    if(this.state.type !== 'list') {
      customClass = 'sp-pagebuilder-modal-small';
      if (this.state.type == 'addon' || this.state.type == 'inner_addon') {
        var addon_name = this.state.addonName;
        if (addon_name != undefined) {
          modalTitle = Joomla.JText._("COM_SPPAGEBUILDER_ADDON") + ': ' + addonsJSON[addon_name].title;
        }
      }
      if (this.state.type == 'row' || this.state.type == 'inner_row') {
        modalTitle = Joomla.JText._("COM_SPPAGEBUILDER_ROW_OPTIONS");
      }
      if (this.state.type == 'column' || this.state.type == 'inner_column') {
        modalTitle = Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_OPTIONS");
      }
    } else {
      modalTitle = Joomla.JText._("COM_SPPAGEBUILDER_ADDONS_LIST");
    }

    return(
      <Modal
        onRequestClose = {this.props.onRequestClose}
        openTimeoutMS = {0}
        customClass = { customClass }
        title = { modalTitle }
        closeTimeoutMS ={0} >
        {
          this.state.type !== 'list'
          ?
          <div>
            <form ref="settingForm" className="form settings-form" onSubmit={ this.saveSettings.bind(this) }>
              <div dangerouslySetInnerHTML={ { __html: this.state.data } } />
              <div id="sp-pagebuilder-modal-actions" className="sp-pagebuilder-modal-actions hidden">
                <button type="submit" className="sp-pagebuilder-btn sp-pagebuilder-btn-success"><i className="fa fa-check-square-o"></i> Apply</button>
                <a href="#" className="sp-pagebuilder-btn sp-pagebuilder-btn-default" onClick={ e => {
                    e.preventDefault();
                    ModalManager.close();
                  }}>
                  <i className="fa fa-times-circle"></i> Cancel
                  </a>
                </div>
              </form>
            </div>
            :
            <div className="sp-pagebuilder-modal-list-addons clearfix">
              <div className="sp-pagebuilder-modal-sidebar">
                <div className="sp-pagebuilder-modal-sidebar-inner">
                  <div>
                    <div className="sp-pagebuilder-brand">
                      <img src={pagebuilder_base + 'administrator/components/com_sppagebuilder/assets/img/logo-white.svg'} alt="SP Page Builder" />
                    </div>
                    {this.__renderCategories()}
                  </div>
                </div>
              </div>

              <div className="sp-pagebuilder-modal-header clearfix">
                <div className="sp-pagebuilder-addons-search">
                  <i className="fa fa-search"></i>
                  <input
                    type="text"
                    className="sp-pagebuilder-form-control"
                    ref="searchItem"
                    id="search-addon"
                    placeholder="Search Addon"
                    onChange={ this.searchChangeHandle.bind(this)}
                    />
                </div>

                <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_MODAL_CLOSE")} position="left" id="sp-pagebuilder-close-modal">
                  <a href="#" className="sp-pagebuilder-close-modal" onClick={ e => {
                      e.preventDefault();
                      ModalManager.close()
                    }}>
                    <i className="pbfont pbfont-close"></i>
                  </a>
                </LinkWithTooltip>
              </div>

              <div className="sp-pagebuilder-modal-body">
                <div className="sp-pagebuilder-modal-body-inner">
                  <div className="sp-pagebuilder-addons-list clearfix">
                    <ul>
                      {this.state.addons.map( ( addon,index )=>
                        addon.visibility &&
                        <li key={index} onClick={ this.addonSettingsNew.bind( this, addon.addon_name ) }>
                          <span>
                            <LinkWithTooltip tooltip={addon.desc} position="top" id={"addon_tooltip_" + addon.addon_name}>
                              <span>
                                <img src={addon.icon} alt={addon.title} />
                                <span>{addon.title}</span>
                              </span>
                            </LinkWithTooltip>
                          </span>
                        </li>
                      )}
                    </ul>
                  </div>
                </div>
              </div>

            </div>
          }
        </Modal>
      )
    }
  }

  export default SpPageBuilderModal;
