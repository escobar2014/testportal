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
      <div className="sp-pabebuilder-column-settings">
        {
          this.props.column.visibility
          ?
          <ul>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ADDON")} position="top" id="column-add-addon">
              <li>
                <a  className="sp-pagebuilder-add-addon"
                  href="#"
                  onClick={ (e) => {
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
                  }}><i className="pbfont pbfont-addon"></i>
                </a>
              </li>
            </LinkWithTooltip>
            {
              typeof this.props.innerRowIndex === 'undefined'
              ?
              <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")} position="top" id="column-add-row">
                <li>
                  <a href="#"
                    onClick={ (e) => {
                      e.preventDefault();
                      this.props.addInnerRow( this.props.rowIndex, this.props.colIndex );
                    }}><i className="pbfont pbfont-add-new-row"></i>
                  </a>
                </li>
              </LinkWithTooltip>
              :
              ''
            }
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_COLUMN")} position="top" id="column-disable">
              <li>
                <a href="#"
                  onClick={ (e) =>{
                    e.preventDefault();
                    this._disableColumnClickHandle();
                  }}>
                  <i className="pbfont pbfont-blind"></i>
                </a>
              </li>
            </LinkWithTooltip>
            {
              this.props.colLength > 1 &&
              <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DELETE_COLUMN")} position="top" id="column-delete">
                <li>
                  <a href="#"
                    onClick={ (e) =>{
                      e.preventDefault();
                      this._deleteColumnClickHandle();
                    }}>
                    <i className="pbfont pbfont-trash"></i>
                  </a>
                </li>
              </LinkWithTooltip>
            }
          </ul>
          :
          <div>
            <a
              href="#"
              onClick={ (e) =>{
                e.preventDefault();
                this._disableColumnClickHandle();
              }}>
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
