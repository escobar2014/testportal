import React, { Component, PropTypes } from 'react';
import { Dropdown, MenuItem } from 'react-bootstrap';
import { connect } from 'react-redux';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import {
  addInnerRow,
  saveSetting,
  deleteColumn,
  deleteInnerColumn,
  disableColumn,
  disableInnerColumn
} from '../actions/index';

class ColumnSettings extends Component{

  _getSettingObjects(){
    return{
      index: this.props.rowIndex,
      settings: {
        colIndex: this.props.colIndex,
        addonIndex: this.props.innerRowIndex,
        innerColIndex: this.props.innerColIndex
      }
    }
  }

  _renderColSettingsHandle(){
    if (typeof this.props.innerRowIndex === 'undefined') {
      var sectionType = 'column';
    }else{
      var sectionType = 'inner_column';
    }

    ModalManager.open(
      <SpPageBuilderModal
        sectionType={ sectionType }
        saveSetting={ this.props.onSettingsClick }
        rowIndex={ this.props.rowIndex }
        colIndex={ this.props.colIndex }
        addonIndex={ this.props.innerRowIndex }
        innerColIndex={ this.props.innerColIndex }
        settings={ this.props.column.settings }
        onRequestClose={() => true}
        />
    )
  }

  _deleteColumnClickHandle(){
    const options = this._getSettingObjects();

    if (window.confirm(Joomla.JText._("COM_SPPAGEBUILDER_DELETE_COLUMN_CONFIRMATION"))) {
      typeof this.props.innerRowIndex === 'undefined'
      ? this.props.deleteColumn(this.props.rowIndex, this.props.colIndex)
      : this.props.deleteInnerColumn(options);
    }
  }

  _disableColumnClickHandle(){
    const options = this._getSettingObjects();

    typeof this.props.innerRowIndex === 'undefined'
    ? this.props.disableColumn(this.props.rowIndex,this.props.colIndex,this.props.id)
    : this.props.disableInnerColumn(options);
  }

  render(){
    return(
      <div className="sp-pagebuilder-column-tools-inner">
        {
          this.props.column.visibility
          ?
          <div>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_OPTIONS")} position="top" id="tooltip-column-settings">
              <Dropdown id="dropdown-column-settings" className="pull-right">
                <Dropdown.Toggle>
                  <i className="pbfont pbfont-settings pbfont-rotate-90"></i>
                </Dropdown.Toggle>

                <Dropdown.Menu>
                  <MenuItem onClick={ (e) =>{
                      this._renderColSettingsHandle();
                    }}>
                    <span><i className="pbfont pbfont-settings"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_OPTIONS")}</span>
                  </MenuItem>
                  {
                    (typeof this.props.innerRowIndex === 'undefined') &&
                    <MenuItem onClick={ (e) =>{
                        this.props.addInnerRow( this.props.rowIndex, this.props.colIndex );
                      }}>
                      <span><i className="pbfont pbfont-add-new-row"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")}</span>
                    </MenuItem>
                  }

                  <MenuItem
                    onClick={ (e) =>{
                      this._disableColumnClickHandle();
                    }}
                    >
                    <span><i className="pbfont pbfont-blind"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_COLUMN")}</span>
                  </MenuItem>
                  {
                    this.props.colLength > 1 &&
                    <MenuItem
                      onClick={ (e) =>{
                        this._deleteColumnClickHandle();
                      }}>
                      <span><i className="pbfont pbfont-trash"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DELETE_COLUMN")}</span>
                    </MenuItem>
                  }
                </Dropdown.Menu>
              </Dropdown>
            </LinkWithTooltip>

            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ADDON")} position="top" id="tooltip-add-addon">
              <a className="sp-pagebuilder-add-addon" href="#" onClick={ (e) => {
                  e.preventDefault();
                  ModalManager.open(
                    <SpPageBuilderModal
                      sectionType="list"
                      saveSetting={this.props.onSettingsClick}
                      rowIndex={this.props.rowIndex}
                      colIndex={this.props.colIndex}
                      addonIndex={this.props.innerRowIndex}
                      innerColIndex={this.props.innerColIndex}
                      onRequestClose={() => true}/>
                  );
                }}>
                <span><i className="pbfont pbfont-addon"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADDON")}</span>
              </a>
            </LinkWithTooltip>
          </div>
          :
          <div>
            <a
              href="#"
              onClick={ (e) =>{
                e.preventDefault();
                this._disableColumnClickHandle();
              }}
              >
              <span><i className="pbfont pbfont-eye"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_COLUMN")}</span>
            </a>
          </div>
        }
      </div>
    )
  }
}

const mapStateToProps = (state) => {
  return { state };
}

const mapDispatchToProps = (dispatch) => {
  return {
    onSettingsClick: (options) => {
      dispatch(saveSetting(options))
    },
    addInnerRow: ( rowIndex, colIndex ) => {
      dispatch(addInnerRow( rowIndex, colIndex ))
    },
    deleteColumn: (index,colIndex) => {
      dispatch(deleteColumn(index,colIndex))
    },
    deleteInnerColumn: (options) => {
      dispatch(deleteInnerColumn(options))
    },
    disableColumn: (index, colIndex, id) => {
      dispatch(disableColumn(index, colIndex, id))
    },
    disableInnerColumn: (options) => {
      dispatch(disableInnerColumn(options))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(ColumnSettings);
