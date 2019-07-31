import React from 'react';
import { connect } from 'react-redux';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import { cloneAddon, deleteAddon, cloneAddonInner, deleteInnerAddon, saveSetting, disableAddon, disableInnerAddon } from '../actions/index'

const AddonSettings = ({
  addon,
  rowIndex,
  colIndex,
  addonIndex,
  innerColIndex,
  addonInnerIndex,
  onSettingsClick,
  cloneAddon,
  cloneAddonInner,
  deleteInnerAddon,
  deleteAddon,
  disableAddon,
  disableInnerAddon
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

  return(
    <ul>
      {addon.visibility &&
        <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_EDIT_ADDON")} position="top" id="tooltip-edit-addon">
          <li>
            <a href="#" className="sp-pagebuilder-edit-addon" onClick={ (e) => {
                e.preventDefault();
                ModalManager.open(
                  <SpPageBuilderModal
                    sectionType={sectionType}
                    saveSetting={onSettingsClick}
                    rowIndex={rowIndex}
                    colIndex={colIndex}
                    addonIndex={addonIndex}
                    innerColIndex={innerColIndex}
                    addonInnerIndex={addonInnerIndex}
                    settings={addon.settings}
                    addonName={addon.name}
                    onRequestClose={(e) => true}/>
                );
              }}>
              <i className="pbfont pbfont-pencil"></i>
            </a>
          </li>
        </LinkWithTooltip>
      }
      {addon.visibility &&
        <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DUPLICATE_ADDON")} position="top" id="tooltip-clone-addon">
          <li>
            <a href="#" className="sp-pagebuilder-clone-addon" onClick={ (e) => {
                e.preventDefault();
                typeof addonInnerIndex === 'undefined'
                ? cloneAddon(options)
                : cloneAddonInner(options)
              }}>
              <i className="pbfont pbfont-duplicate"></i>
            </a>
          </li>
        </LinkWithTooltip>
      }

      <LinkWithTooltip tooltip={addon.visibility?Joomla.JText._("COM_SPPAGEBUILDER_DISABLE_ADDON"):Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_ADDON")} position="top" id="tooltip-delete-addon">
        <li>
          <a href="#" className="sp-pagebuilder-toggle-addon" onClick={ (e) => {
              e.preventDefault()
              typeof addonInnerIndex === 'undefined'
              ? disableAddon(options)
              : disableInnerAddon(options)
            }}>
            {addon.visibility?<i className="pbfont pbfont-eye"></i>:<span><i className="pbfont pbfont-blind"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ENABLE_ADDON")}</span>}
          </a>
        </li>
      </LinkWithTooltip>

      {addon.visibility &&
        <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_DELETE_ADDON")} position="top" id="tooltip-delete-addon">
          <li>
            <a href="#" className="sp-pagebuilder-delete-addon" onClick={ (e) => {
                e.preventDefault()

                if (window.confirm("Do you really want to delete this Addon?")) {
                  typeof addonInnerIndex === 'undefined'
                  ? deleteAddon(options)
                  : deleteInnerAddon(options)
                }
              }}>
              <i className="pbfont pbfont-trash"></i>
            </a>
          </li>
        </LinkWithTooltip>
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
