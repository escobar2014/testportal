import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DropTarget } from 'react-dnd';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import Addon from './Addon';
import InnerRow from './InnerRow';
import { saveSetting } from '../actions/index';

const addonListTarget = {
  hover(props, monitor, component) {
    const item          = monitor.getItem();
    const dragIndex     = item.index;
    const hoverIndex    = props.index;

    if ( props.addons.length > 0 ) {
      return;
    }

    if ( props.rowIndex === item.rowIndex && props.colIndex === item.colIndex ) {
      return;
    }

    var options = {
      drag        : item,
      dragIndex   : dragIndex,
      drop        : props,
      hoverIndex  : hoverIndex
    };

    if (typeof item.innerRowIndex === 'undefined')
    {
      if ( props.rowIndex === item.rowIndex) {
        options.type = 'ADDON_SORT_COL';
      } else {
        options.type = 'ADDON_SORT_OUTER_ROW';
      }
    } else {
      if ( props.rowIndex === item.rowIndex) {
        options.type = 'ADDON_SORT_PARENT_COL';
      } else {
        options.type = 'ADDON_SORT_PARENT_OUTER_ROW';
      }
    }

    props.dropAddon( options );  // drop item

    monitor.getItem().index         = 0;
    monitor.getItem().innerRowId    = props.innerRowId;
    monitor.getItem().innerColId    = props.innerColId;
    monitor.getItem().rowIndex      = props.rowIndex;
    monitor.getItem().colIndex      = props.colIndex;
    monitor.getItem().innerRowIndex = props.innerRowIndex;
    monitor.getItem().innerColIndex = props.innerColIndex;
  }
};

class AddonList extends Component {
  render() {
    const { addons, rowIndex, colIndex, connectDropTarget, canDrop, isOver } = this.props;

    var addonsClass = "sp-pagebuilder-addons";

    if(!addons.length) {
      addonsClass = addonsClass + ' sp-pagebuilder-no-addons';
    }

    return connectDropTarget(
      <div className={addonsClass}>

        {
          addons.length > 0
          ?
          <div>
            {addons.map( ( addon, index) =>
              addon.type === 'inner_row'
              ?
              <InnerRow
                key={addon.id}
                id={addon.id}
                rowIndex={rowIndex}
                colIndex={colIndex}
                data={addon}
                index={index}
                addonSort={ this.props.addonSortable }
                />
              :
              <Addon
                key={addon.id}
                id={addon.id}
                rowIndex={rowIndex}
                addon={addon}
                colIndex={colIndex}
                index={index}
                addonSort={ this.props.addonSortable }
                />
            )}
          </div>
          :
          <div>
            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ADDON")} position="top" id="tooltip-empty-add-addon">
              <a className="sp-pagebuilder-add-addon-empty" href="#" onClick={ e => {
                  e.preventDefault();
                  ModalManager.open(
                    <SpPageBuilderModal
                      sectionType="list"
                      saveSetting={this.props.onSettingsClick}
                      rowIndex={rowIndex}
                      colIndex={colIndex}
                      onRequestClose={() => true}/>
                  );
                }}>
                <i className="pbfont pbfont-addon"></i>
              </a>
            </LinkWithTooltip>
          </div>
        }
      </div>
    )
  }
}

var DropTargetDecorator = DropTarget( [ ItemTypes.ADDON, ItemTypes.INNERROW, ItemTypes.INNERADDON ], addonListTarget,
  function( connect, monitor ) {
    return {
      connectDropTarget: connect.dropTarget(),
      isOver: monitor.isOver(),
      canDrop: monitor.canDrop()
    };
  }
);

const mapStateToProps = ( state ) => {
  return {
    state: state
  };
}

const mapDispatchToProps = ( dispatch ) => {
  return {
    addonSortable : ( options ) => {
      dispatch(options);
    },
    onSettingsClick: (options) => {
      dispatch(saveSetting(options))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(AddonList));
