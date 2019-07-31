import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux'
import { ModalManager} from '../helpers/index';
import PageListModal from '../helpers/PageListModal';
import { addRow, importPage } from '../actions/index'
import { ActionCreators } from 'redux-undo';

class PageTools extends Component{

  uploadPage(){
    var file = jQuery('#upload-file').prop('files')[0];

    if (typeof file === 'undefined') {
      return;
    }

    var fileName = file.name,
        fileType = fileName.slice(-5),
        fileTypeLower = fileType.toLowerCase();

    if (fileTypeLower === '.json') {
      var formData = new FormData();
      formData.append('page',file );
      jQuery.ajax({
        type: 'POST',
        url: 'index.php?option=com_sppagebuilder&view=ajax&format=json&callback=upload-page',
        dataType: 'json',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if ( response.status ) {
            this.props.importPage(JSON.parse(response.data));
            jQuery('#upload-file').val('');
          } else {
            console.log(response.data);
          }
        }.bind(this)
      });
    }
  }

  exportLayout(e){
    e.preventDefault();

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("id", "pageexport");
    form.setAttribute("action", "index.php?option=com_sppagebuilder&task=export");
    form.setAttribute("target", "_blank");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", 'hidden');
    hiddenField.setAttribute("name", 'template');
    hiddenField.setAttribute("value", JSON.stringify(this.props.state.pageBuilder.present));
    form.appendChild(hiddenField);
    document.getElementsByTagName("body")[0].appendChild(form);
    form.submit();
    jQuery('#pageexport').remove();
  }


  render() {
    const { pageBuilder } = this.props.state;
    let undoCount = pageBuilder.past.length;
    let redoCount = pageBuilder.future.length;

    return(
      <div className="clearfix">
        <div className="pull-left">
          <ul className="sp-pagebuilder-page-tabs">

            <li>
              <a
                href="#"
                onClick={ (e) => {
                  e.preventDefault();
                  this.props.addNewRow();
                }}
                >
                <span><i className="pbfont pbfont-add-new-row"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")}</span>
              </a>
            </li>

            <li>
              <input
                type="file"
                name="upload-file"
                id="upload-file"
                accept=".json"
                style={{ display:'none' }}
                onChange={ (e) => {
                  this.uploadPage();
                }} />
                <a
                  href="#"
                  onClick={(e)=>{
                    e.preventDefault();
                    document.getElementById('upload-file').click();
                  }}>
                  <span><i className="pbfont pbfont-import"></i> {Joomla.JText._("COM_SPPAGEBUILDER_IMPORT_PAGE")}</span>
                </a>
              </li>

              <li>
                <a
                  href="#"
                  onClick={this.exportLayout.bind(this)}>
                  <span><i className="pbfont pbfont-export"></i> {Joomla.JText._("COM_SPPAGEBUILDER_EXPORT_PAGE")}</span>
                </a>
              </li>

              <li>
                <a
                  href="#"
                  onClick={ (e) => {
                    e.preventDefault();
                    ModalManager.open(
                      <PageListModal
                        importPage={this.props.importPage}
                        onRequestClose={() => true}
                        />
                    )
                  }}>
                  <span><i className="pbfont pbfont-templates"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PAGE_TEMPLATES")}</span>
                </a>
              </li>

            </ul>
          </div>

          <div className="pull-right">
            <div className="text-right">
              <button
                type="button"
                id="sp-pagebuilder-btn-save"
                className="sp-pagebuilder-btn sp-pagebuilder-btn-success sp-pagebuilder-btn-save sp-pagebuilder-btn-sm"
                onClick={ (e) => {
                  e.preventDefault();
                }}>
                <span><i className="fa fa-save"></i> {Joomla.JText._("COM_SPPAGEBUILDER_SAVE")}</span>
              </button>

              <button
                type="button"
                className="sp-pagebuilder-btn sp-pagebuilder-btn-link"
                disabled={
                  !undoCount&&
                  'disabled'
                }
                onClick={ (e) => {
                  e.preventDefault();
                  this.props.pageUndoClick();
                }}>
                <span><i className="pbfont pbfont-undo"></i> {Joomla.JText._("COM_SPPAGEBUILDER_UNDO")}</span>
              </button>

              <button
                className="sp-pagebuilder-btn sp-pagebuilder-btn-link"
                type="button"
                disabled={
                  !redoCount&&
                  'disabled'
                }
                onClick={ (e) => {
                  e.preventDefault();
                  this.props.pageRedoClick();
                }}>
                <span><i className="pbfont pbfont-redo"></i> {Joomla.JText._("COM_SPPAGEBUILDER_REDO")}</span>
              </button>
            </div>
          </div>
        </div>
      )
    }
  }

  const mapStateToProps = ( state ) => {
    return {state};
  }

  const mapDispatchToProps = ( dispatch ) => {
    return {
      importPage: ( page ) => {
        dispatch(importPage(page))
      },
      addNewRow: () => {
        dispatch(addRow())
      },
      pageUndoClick: () => {
        dispatch(ActionCreators.undo())
      },
      pageRedoClick: () => {
        dispatch(ActionCreators.redo())
      }
    }
  }

  export default connect(
    mapStateToProps,
    mapDispatchToProps
  )(PageTools);
