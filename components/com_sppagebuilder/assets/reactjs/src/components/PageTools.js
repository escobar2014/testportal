import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux'
import PageToolDropdown from './PageToolDropdown'
import JoomlaActions from './JoomlaActions'
import { addRow } from '../actions/index'
import { ActionCreators } from 'redux-undo';
import { importPage } from '../actions/index'

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
        url: 'index.php?option=com_sppagebuilder&view=ajax&format=json&callback=upload-page&editarea=frontend',
        dataType: 'json',
        data: formData,
        cache: false,
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

  render(){
    const { pageBuilder } = this.props.state;
    let undoCount = pageBuilder.past.length;
    let redoCount = pageBuilder.future.length;

    return(
      <div className="clearfix">
        <input
          type="file"
          name="upload-file"
          id="upload-file"
          accept=".json"
          style={{ display:'none'}}
          onChange={ (e) => {
            this.uploadPage();
          }}
          />
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
                <span><i className="pbfont pbfont-plus"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")}</span>
              </a>
            </li>

            <li>
              <PageToolDropdown />
            </li>

            <li>
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
                }}
                >
                <span><i className="pbfont pbfont-undo"></i> {Joomla.JText._("COM_SPPAGEBUILDER_UNDO")}</span>
              </button>
            </li>
            <li>
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
                }}
                >
                <span><i className="pbfont pbfont-redo"></i> {Joomla.JText._("COM_SPPAGEBUILDER_REDO")}</span>
              </button>
            </li>

          </ul>
        </div>
        <div className="pull-right">
          <div className="text-right">
            <JoomlaActions />
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
    addNewRow: () => {
      dispatch(addRow())
    },
    pageUndoClick: () => {
      dispatch(ActionCreators.undo())
    },
    pageRedoClick: () => {
      dispatch(ActionCreators.redo())
    },
    importPage: ( page ) => {
      dispatch(importPage(page))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(PageTools);
