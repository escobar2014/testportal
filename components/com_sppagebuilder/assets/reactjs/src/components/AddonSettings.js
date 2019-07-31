import React from 'react';
import { connect } from 'react-redux';
import { Dropdown, MenuItem } from 'react-bootstrap';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import {
  addInnerRow,
  deleteColumn,
  deleteInnerColumn,
  disableColumn,
  disableInnerColumn,
  cloneAddon,
  deleteAddon,
  cloneAddonInner,
  deleteInnerAddon,
  saveSetting,
  disableAddon,
  disableInnerAddon
} from '../actions/index'

const AddonSettings = ({
  addon,
  rowIndex,
  colIndex,
  addonIndex,
  innerColIndex,
  addonInnerIndex,
  onSettingsClick,
  addInnerRow,
  deleteColumn,
  deleteInnerColumn,
  cloneAddon,
  cloneAddonInner,
  deleteInnerAddon,
  deleteAddon,
  disableAddon,
  disableInnerAddon,
  column,
  connectDragSource,
  columnMove,
  state
}) => {

  let options = {
    index: rowIndex,
    settings: {
      colIndex: colIndex,
      addonIndex: addonIndex,
      innerColIndex: innerColIndex,
      addonInnerIndex: addonInnerIndex
    }
  };

  if (typeof addonInnerIndex === 'undefined') {
    var sectionType = 'addon';
  } else {
    var sectionType = 'inner_addon';
  }

  function _getSettingObjects(){
    return{
      index: rowIndex,
      settings: {
        colIndex: colIndex,
        addonIndex: addonIndex,
        innerColIndex: innerColIndex
      }
    }
  }

  function _renderColSettingsHandle() {
    if (typeof innerColIndex === 'undefined') {
      var sectionType = 'column';
    } else {
      var sectionType = 'inner_column';
    }

    ModalManager.open(
      <SpPageBuilderModal
        sectionType={ sectionType }
        saveSetting={ onSettingsClick }
        rowIndex={ rowIndex }
        colIndex={ colIndex }
        addonIndex={ addonIndex }
        innerColIndex={ innerColIndex }
        settings={ column.settings }
        onRequestClose={() => true}
        />
    )
  }

  function _deleteColumnClickHandle(){
    const options = _getSettingObjects();

    if (window.confirm(Joomla.JText._("COM_SPPAGEBUILDER_DELETE_COLUMN_CONFIRMATION"))) {
      typeof innerColIndex === 'undefined'
      ? deleteColumn( rowIndex, colIndex )
      : deleteInnerColumn(options);
    }
  }

  function _disableColumnClickHandle(){
    const options = _getSettingObjects();

    typeof innerColIndex === 'undefined'
    ? disableColumn( rowIndex, colIndex, column.id)
    : disableInnerColumn(options);
  }

  return(
    <ul>

      <li>
        <span className="sp-pagebuilder-drag-addon">
          { connectDragSource(
            <i className="pbfont pbfont-drag"></i>
          )}
        </span>
      </li>

      {addon.visibility &&

        <li>
          <a href="#" className="sp-pagebuilder-edit-addon" onClick={ (e) => {
              e.preventDefault();
              ModalManager.open(
                <SpPageBuilderModal
                  sectionType={ sectionType }
                  saveSetting={ onSettingsClick }
                  rowIndex={ rowIndex }
                  colIndex={ colIndex }
                  addonIndex={ addonIndex }
                  innerColIndex={ innerColIndex }
                  addonInnerIndex={ addonInnerIndex }
                  settings={ addon.settings }
                  addonName={ addon.name }
                  addonId={ addon.id }
                  onRequestClose={(e) => true}/>
              );
            }}>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_EDIT_ADDON")} position="top" id="tooltip-edit-addon">
              <i className="pbfont pbfont-pencil"></i>
            </LinkWithTooltip>
          </a>
        </li>
      }
      {addon.visibility &&
        <li>
          <a href="#" className="sp-pagebuilder-clone-addon" onClick={ (e) => {
              e.preventDefault();
              typeof addonInnerIndex === 'undefined'
              ? cloneAddon(options)
              : cloneAddonInner(options)
            }}>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DUPLICATE_ADDON")} position="top" id="tooltip-clone-addon">
              <i className="pbfont pbfont-duplicate"></i>
            </LinkWithTooltip>
          </a>
        </li>
      }

      <li>
        <a href="#" className="sp-pagebuilder-toggle-addon" onClick={ (e) => {
            e.preventDefault()
            typeof addonInnerIndex === 'undefined'
            ? disableAddon(options)
            : disableInnerAddon(options)
          }}>
          <LinkWithTooltip tooltip={addon.visibility?Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_ADDON"):Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_ADDON")} position="top" id="tooltip-delete-addon">
            {addon.visibility?<i className="pbfont pbfont-eye"></i>:<span><i className="pbfont pbfont-blind"></i></span>}
          </LinkWithTooltip>
        </a>
      </li>

      {addon.visibility &&
        <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADDON_PARENT_COLUMN")} position="top" id="tooltip-addon-column">
          <li>
            <Dropdown id="sp-pagebuilder-addon-column" className="sp-pagebuilder-addon-column">
              <Dropdown.Toggle className="btn-link">
                <i className="fa fa-columns"></i>
              </Dropdown.Toggle>
              <Dropdown.Menu className="sp-pagebuilder-addon-column-dropdown">
                <MenuItem
                  onClick ={ (e) => {
                    e.preventDefault();
                    columnMove(true);
                  }}
                  >
                  <span><i className="pbfont pbfont-drag"></i> {Joomla.JText._("COM_SPPAGEBUILDER_MOVE_COLUMN")}</span>
                </MenuItem>

                <MenuItem
                  onClick = {(e) => {
                    _renderColSettingsHandle();
                  }}
                  >
                  <span><i className="pbfont pbfont-gear"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_OPTIONS")}</span>
                </MenuItem>
                {
                  (typeof innerColIndex === 'undefined') &&
                  <MenuItem
                    onClick = { (e) => {
                      addInnerRow( rowIndex, colIndex );
                    }}>
                    <span><i className="pbfont pbfont-add-new-row"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_INNER_ROW")}</span>
                  </MenuItem>
                }
                <MenuItem
                  onClick = { (e) => {
                    _disableColumnClickHandle();
                  }}>
                  <span><i className="pbfont pbfont-eye"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_COLUMN")}</span>
                </MenuItem>

                <MenuItem
                  onClick = { (e) => {
                    _deleteColumnClickHandle();
                  }}>
                  <span><i className="pbfont pbfont-trash"></i> {Joomla.JText._("COM_SPPAGEBUILDER_DELETE_COLUMN")}</span>
                </MenuItem>
              </Dropdown.Menu>
            </Dropdown>
          </li>
        </LinkWithTooltip>
      }

      {addon.visibility &&
        <li>
          <a href="#" className="sp-pagebuilder-delete-addon" onClick={ (e) => {
              e.preventDefault()

              if (window.confirm("Do you really want to delete this Addon?")) {
                jQuery(document).find('#addon-script-'+addon.id).remove();
                typeof addonInnerIndex === 'undefined'
                ? deleteAddon(options)
                : deleteInnerAddon(options)
              }
            }}>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DELETE_ADDON")} position="top" id="tooltip-delete-addon">
              <i className="pbfont pbfont-trash"></i>
            </LinkWithTooltip>
          </a>
        </li>
      }
    </ul>
  )
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
    },
    cloneAddon: (options) => {
      dispatch(cloneAddon(options))
    },
    deleteAddon: (options) => {
      dispatch(deleteAddon(options))
    },
    cloneAddonInner: (options) => {
      dispatch(cloneAddonInner(options))
    },
    deleteInnerAddon: (options) => {
      dispatch(deleteInnerAddon(options))
    },
    disableAddon: (options) => {
      dispatch(disableAddon(options))
    },
    disableInnerAddon: (options) => {
      dispatch(disableInnerAddon(options))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(AddonSettings);
