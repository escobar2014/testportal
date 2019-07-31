import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DropTarget } from 'react-dnd';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import InnerAddon from './InnerAddon';
import { saveSetting } from '../actions/index';

const innerAddonListTarget = {
  hover(props, monitor, component) {
    const item          = monitor.getItem();
    const dragIndex     = item.index;
    const hoverIndex    = props.index;

    if ( props.addons.length > 0 ) {
      return;
    }

    if (item.innerColId === props.innerColId) {
      return;
    }

    var options = {
      drag        : item,
      dragIndex   : dragIndex,
      drop        : props,
      hoverIndex  : hoverIndex
    };

    if (typeof item.innerRowIndex === 'undefined') {
      if (item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
        options.type = 'ADDON_SORT_INNER_ADDON_COL';
      }else if(item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex){
        options.type = 'ADDON_SORT_INNER_ADDON_ROW';
      }else{
        options.type = 'ADDON_SORT_INNER_ADDON_OUTER_ROW';
      }
    }
    else
    {
      if ( item.innerRowId === props.innerRowId && item.innerColId !== props.innerColId ) {
        options.type = 'INNER_ADDON_SORT_INNER_ROW';
      } else if ( item.rowIndex === props.rowIndex && item.colIndex === props.colIndex && item.innerRowId !== props.innerRowId ) {
        options.type = 'INNER_ADDON_SORT_OUTER_ROW';
      } else if ( item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex ) {
        options.type = 'INNER_ADDON_SORT_PARENT_ROW';
      } else {
        options.type = 'INNER_ADDON_SORT_PARENT_OUTER_ROW';
      }
    }

    props.dropInnerAddon(options);

    if (typeof item.innerRowIndex === 'undefined' && item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
      if (monitor.getItem().index < props.innerRowIndex) {
        monitor.getItem().innerRowIndex = (props.innerRowIndex - 1);
      }else{
        monitor.getItem().innerRowIndex = props.innerRowIndex;
      }
    }else{
      monitor.getItem().innerRowIndex = props.innerRowIndex;
    }

    monitor.getItem().index         = 0;
    monitor.getItem().innerRowId    = props.innerRowId;
    monitor.getItem().innerColId    = props.innerColId;
    monitor.getItem().rowIndex      = props.rowIndex;
    monitor.getItem().colIndex      = props.colIndex;
    monitor.getItem().innerColIndex = props.innerColIndex;
  }
};

class InnerAddonList extends Component {
  render() {
    const {
      addons,
      rowIndex,
      colIndex,
      innerRowId,
      innerColId,
      innerRowIndex,
      innerColIndex,
      connectDropTarget,
      canDrop,
      isOver
    } = this.props;

    var addonsClass = "sp-pagebuilder-addons";

    if(!addons.length) {
      addonsClass = addonsClass + ' sp-pagebuilder-no-addons';
    }

    return connectDropTarget(
      <div className={addonsClass}>
        {
          addons.length > 0
          ?
          addons.map( ( addon, index) =>
          <InnerAddon
            key={addon.id}
            id={addon.id}
            addon={addon}
            index={index}
            innerRowId={innerRowId}
            innerColId={innerColId}
            rowIndex={rowIndex}
            colIndex={colIndex}
            innerRowIndex={innerRowIndex}
            innerColIndex={innerColIndex}
            innerAddonSort={this.props.addonInnerSortable} />
        )
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
                    addonIndex={innerRowIndex}
                    innerColIndex={innerColIndex}
                    onRequestClose={() => true} />
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

var DropTargetDecorator = DropTarget( [ ItemTypes.ADDON, ItemTypes.INNERADDON ], innerAddonListTarget,
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
    addonInnerSortable: (options) => {
      dispatch(options)
    },
    onSettingsClick: (options) => {
      dispatch(saveSetting(options))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(InnerAddonList));
