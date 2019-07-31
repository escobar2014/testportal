import React, { Component, PropTypes } from 'react';
import { Dropdown, MenuItem } from 'react-bootstrap';
import { connect } from 'react-redux';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';

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

  render(){
    return(
      this.props.row.visibility
      ?
      <div className="sp-pagebuilder-row-settings">
        <ul>

          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ROW_OPTIONS")} position="top" id="row-options">
            <li>
              <a href="#"
                onClick={(e)=>{
                  e.preventDefault();
                  this._addRowButtonHandle();
                }}>
                <i className="pbfont pbfont-settings"></i>
              </a>
            </li>
          </LinkWithTooltip>

          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DUPLICATE_ROW")} position="top" id="row-clone">
            <li>
              <a href="#"
                onClick={(e) =>{
                  e.preventDefault();
                  this._duplicateRowClickHandle()
                }}>
                <i className="pbfont pbfont-duplicate"></i>
              </a>
            </li>
          </LinkWithTooltip>

          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")} position="top" id="add-new-row">
            <li>
              <a href="#"
                onClick={(e) =>{
                  e.preventDefault();
                  this._addNewRowClickHandle()
                }}>
                <i className="pbfont pbfont-add-new-row"></i>
              </a>
            </li>
          </LinkWithTooltip>

          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_ROW")} position="top" id="row-disable">
            <li>
              <a href="#"
                onClick={ (e) => {
                  e.preventDefault();
                  this._rowVisbilityToggleHandle();
                }}
                >
                { this.props.row.visibility ? <span><i className="pbfont pbfont-blind"></i></span> : <span><i className="pbfont pbfont-eye"></i></span> }
              </a>
            </li>
          </LinkWithTooltip>

          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DELETE_ROW")} position="top" id="row-delete">
            <li>
              <a href="#"
                onClick={(e)=>{
                  e.preventDefault();
                  this._deleteRowClickHandle();
                }}>
                <i className="pbfont pbfont-trash"></i>
              </a>
            </li>
          </LinkWithTooltip>

        </ul>
      </div>
      :
      <div className="sp-pagebuilder-enable-row">
        <a href="#"
          onClick={ (e) => {
            e.preventDefault();
            this._rowVisbilityToggleHandle();
          }}
          >
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
