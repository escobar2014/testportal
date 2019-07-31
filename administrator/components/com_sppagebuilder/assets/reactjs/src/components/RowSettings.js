import React, { Component, PropTypes } from 'react';
import { Dropdown, MenuItem } from 'react-bootstrap';
import { connect } from 'react-redux';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import PasteModal from '../helpers/PasteModal';
import CopyToClipboard from 'react-copy-to-clipboard';

import {
  addRowBottom,
  toggleRow,
  deleteRow,
  cloneRow,
  saveSetting,
  pasteRow,
  innerAddRowBottom,
  deleteAddon,
  innerCloneRow,
  innerToggleRow,
  innerPasteRow
} from '../actions/index';

class RowSettings extends Component {

  _getSettingObjects(){
    return{
      index: this.props.index,
      settings: {
        colIndex: this.props.colIndex,
        addonIndex: this.props.innerRowIndex
      }
    }
  }

  _addRowButtonHandle(){
    if (typeof this.props.innerRowIndex === 'undefined') {
      var sectionType = 'row';
    } else {
      var sectionType = 'inner_row';
    }

    ModalManager.open(
      <SpPageBuilderModal
        sectionType={sectionType}
        saveSetting={this.props.onSettingsClick}
        rowIndex={ this.props.index }
        colIndex={ this.props.colIndex }
        addonIndex={ this.props.innerRowIndex }
        settings={ this.props.row.settings }
        onRequestClose={() => true}
        />
    );
  }

  _duplicateRowClickHandle(){
    const options = this._getSettingObjects();

    typeof this.props.innerRowIndex === 'undefined'
    ? this.props.cloneRow(this.props.index)
    : this.props.innerCloneRow(options)
  }

  _addNewRowClickHandle(){
    const options = this._getSettingObjects();

    typeof this.props.innerRowIndex === 'undefined'
    ? this.props.addRowBottom(this.props.index)
    : this.props.innerAddRowBottom(options)
  }

  _rowVisbilityToggleHandle(){
    const options = this._getSettingObjects();

    typeof this.props.innerRowIndex === 'undefined'
    ? this.props.toggleRow(this.props.row.id)
    : this.props.innerToggleRow(options)
  }

  _pasteClickHandle(){
    if (typeof this.props.innerRowIndex === 'undefined') {
      var dataType = 'row';
    } else {
      var dataType = 'inner_row';
    }

    ModalManager.open(
      <PasteModal
        dataType={dataType}
        pasteSave={this.props.pasteSave}
        rowIndex={ this.props.index }
        colIndex={ this.props.colIndex }
        addonIndex={ this.props.innerRowIndex }
        onRequestClose={() => true}
        />
    );
  }

  _deleteRowClickHandle(){
    const options = this._getSettingObjects();

    if (window.confirm(Joomla.JText._("COM_SPPAGEBUILDER_DELETE_ROW_CONFIRMATION"))) {
      typeof this.props.innerRowIndex === 'undefined'
      ? this.props.deleteRow(this.props.index)
      : this.props.innerDeleteRow(options)
    }
  }

  _onCopyNotification(){
    jQuery('<div class="notify-success"><i class="fa fa-check-circle"></i> ' + Joomla.JText._('COM_SPPAGEBUILDER_ROW_COPIED') + '</div>').css({
      opacity: 0,
      'margin-top': -15,
      'margin-bottom': 0
    }).animate({
      opacity: 1,
      'margin-top': 0,
      'margin-bottom': 15
    },200).prependTo('.sp-pagebuilder-notifications');

    jQuery('.sp-pagebuilder-notifications').find('>div').each(function() {
      var $this = jQuery(this);

      setTimeout(function(){
        $this.animate({
          opacity: 0,
          'margin-top': -15,
          'margin-bottom': 0
        }, 200, function() {
          $this.remove();
        });
      }, 1000);
    });
  }

  render(){
    return(
      this.props.row.visibility
      ?
      <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ROW_OPTIONS")} position="top" id="row-options">
        <Dropdown
          id="dropdown-row-settings"
          className="pull-right">
          <Dropdown.Toggle>
            <i className="pbfont pbfont-gear"></i>
          </Dropdown.Toggle>
          <Dropdown.Menu>
            <MenuItem
              onClick={(e)=>{
                this._addRowButtonHandle();
              }}>
              <span><i className="pbfont pbfont-settings"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ROW_OPTIONS")}</span>
            </MenuItem>

            <MenuItem
              onClick={(e)=>{
                this._addNewRowClickHandle();
              }}>
              <span><i className="pbfont pbfont-add-new-row"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")}</span>
            </MenuItem>

            <MenuItem
              onClick={(e) =>{
                this._duplicateRowClickHandle()
              }}>
              <span><i className="pbfont pbfont-duplicate"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DUPLICATE_ROW")}</span>
            </MenuItem>

            <MenuItem
              onClick={ (e) => {
                this._rowVisbilityToggleHandle();
              }}
              >
              { this.props.row.visibility ? <span><i className="pbfont pbfont-blind"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_ROW")}</span> : <span><i className="pbfont pbfont-eye"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_ROW")}</span> }
            </MenuItem>
            <CopyToClipboard
              text={JSON.stringify(this.props.row)}
              onCopy={() =>{ this._onCopyNotification() }}
            >
              <MenuItem>
                <span><i className="pbfont pbfont-copy"></i> {Joomla.JText._("COM_SPPAGEBUILDER_COPY_ROW")}</span>
              </MenuItem>
            </CopyToClipboard>
            {
              (typeof this.props.innerRowIndex === 'undefined') &&
              <MenuItem
                onClick={(e) => {
                  this._pasteClickHandle();
                }}
                >
                <span><i className="pbfont pbfont-paste"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PASTE_ROW")}</span>
              </MenuItem>
            }
            {
              (typeof this.props.innerRowIndex !== 'undefined') &&
              <MenuItem
                onClick={(e)=> {
                  this._pasteClickHandle();
                }}>
                <span><i className="pbfont pbfont-paste"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PASTE_ROW")}</span>
              </MenuItem>
            }
            <MenuItem
              onClick={(e)=>{
                this._deleteRowClickHandle();
              }}>
              <span><i className="pbfont pbfont-trash"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DELETE_ROW")}</span>
            </MenuItem>
          </Dropdown.Menu>
        </Dropdown>
      </LinkWithTooltip>
      :
      <div className="pull-right">
        <a href="#"
          onClick={ (e) => {
            e.preventDefault();
            this._rowVisbilityToggleHandle();
          }}>
          <span><i className="pbfont pbfont-eye"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_ROW")}</span>
        </a>
      </div>
    )
  }
}


const mapStateToProps = (state) => {
  return {state};
}

const mapDispatchToProps = (dispatch) => {
  return {
    onSettingsClick: (options) => {
      dispatch(saveSetting(options))
    },
    addRowBottom: (index) => {
      dispatch(addRowBottom(index))
    },
    cloneRow: (index) => {
      dispatch(cloneRow(index))
    },
    toggleRow: (id) => {
      dispatch(toggleRow(id))
    },
    deleteRow: (index) => {
      dispatch( deleteRow( index ) )
    },
    innerAddRowBottom: (options) => {
      dispatch( innerAddRowBottom( options ) )
    },
    innerCloneRow: (options) => {
      dispatch( innerCloneRow( options ) )
    },
    innerToggleRow: (options) => {
      dispatch( innerToggleRow( options ) )
    },
    innerDeleteRow: (options) => {
      dispatch( deleteAddon( options ) )
    },
    pasteSave: (options) => {
      if (options.type === 'row') {
        dispatch( pasteRow( options ) );
      } else {
        dispatch( innerPasteRow( options ) )
      }
    }
  }
}

export default  connect(
  mapStateToProps,
  mapDispatchToProps
)(RowSettings)
