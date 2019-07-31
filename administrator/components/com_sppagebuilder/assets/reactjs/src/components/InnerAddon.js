import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import AddonSettings from './AddonSettings';

const inenrAddonSource = {
  beginDrag(props){
    return {
      id              : props.id,
      index           : props.index,
      innerRowId      : props.innerRowId,
      innerColId      : props.innerColId,
      rowIndex        : props.rowIndex,
      colIndex        : props.colIndex,
      innerRowIndex   : props.innerRowIndex,
      innerColIndex   : props.innerColIndex,
    }
  }
}

const innerAddonTarget = {
  hover(props, monitor, component) {
    const item          = monitor.getItem();
    const dragIndex     = item.index;
    const hoverIndex    = props.index;

    if ( monitor.getItem().id === props.id) {
      return;
    }

    const hoverBoundingRect = findDOMNode(component).getBoundingClientRect();
    const hoverMiddleY      = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
    const clientOffset      = monitor.getClientOffset();
    const hoverClientY      = clientOffset.y - hoverBoundingRect.top;

    if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY) {
      return;
    }
    if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY) {
      return;
    }

    var options = {
      drag        : item,
      dragIndex   : dragIndex,
      drop        : props,
      hoverIndex  : hoverIndex
    };

    if ( typeof item.innerRowIndex === 'undefined') {
      if (item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
        options.type = 'ADDON_SORT_INNER_ADDON_COL';
      } else if (item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex){
        options.type = 'ADDON_SORT_INNER_ADDON_ROW';
      } else {
        options.type = 'ADDON_SORT_INNER_ADDON_OUTER_ROW';
      }
    }
    else
    {
      if ( item.innerRowId === props.innerRowId && item.innerColId === props.innerColId ) {
        options.type = 'INNER_ADDON_SORT_INNER_COL';
      } else if ( item.innerRowId === props.innerRowId && item.innerColId !== props.innerColId ) {
        options.type = 'INNER_ADDON_SORT_INNER_ROW';
      } else if ( item.rowIndex === props.rowIndex && item.colIndex === props.colIndex && item.innerRowId !== props.innerRowId ) {
        options.type = 'INNER_ADDON_SORT_OUTER_ROW';
      } else if ( item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex ) {
        options.type = 'INNER_ADDON_SORT_PARENT_ROW';
      } else {
        options.type = 'INNER_ADDON_SORT_PARENT_OUTER_ROW';
      }
    }

    props.innerAddonSort(options);

    if (typeof item.innerRowIndex === 'undefined' && item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
      if (monitor.getItem().index < props.innerRowIndex) {
        monitor.getItem().innerRowIndex = (props.innerRowIndex - 1);
      }else{
        monitor.getItem().innerRowIndex = props.innerRowIndex;
      }
    }else{
      monitor.getItem().innerRowIndex = props.innerRowIndex;
    }

    monitor.getItem().index = hoverIndex;
    monitor.getItem().innerRowId    = props.innerRowId;
    monitor.getItem().innerColId    = props.innerColId;
    monitor.getItem().rowIndex      = props.rowIndex;
    monitor.getItem().colIndex      = props.colIndex;
    monitor.getItem().innerColIndex = props.innerColIndex;
  }
};

class InnerAddon extends Component {
  render() {
    const {
      addon,
      id,
      index,
      innerRowId,
      innerColId,
      rowIndex,
      colIndex,
      innerRowIndex,
      innerColIndex,
      isDragging,
      connectDropTarget,
      connectDragPreview,
      connectDragSource
    } = this.props;

    var addonClass = "sp-pagebuilder-addon";
    if(isDragging) {
      addonClass = "sp-pagebuilder-dragging " + addonClass;
    }

    let addonName  = addon.name;
    let addonTitle = addonName;
    let addonIcon = pagebuilder_base + '/administrator/components/com_sppagebuilder/assets/img/addon-default.png';
    if (addonsJSON[addonName]) {
      addonTitle  = addonsJSON[addonName].title;
      addonIcon   = addonsJSON[addonName].icon;
    }

    let admin_label = '';
    if(addon.settings.admin_label !== 'undefined' && addon.settings.admin_label !== '') {
      admin_label = addon.settings.admin_label;
    } else if ( addon.settings.title !== 'undefined' && addon.settings.title !== '' ) {
      admin_label = addon.settings.title;
    }

    return connectDragPreview(connectDropTarget(

      <div key={ addon.id } className={addonClass}>
        { connectDragSource(
          <div className="clearfix">
            <div className="sp-pagebuilder-addon-tools">
              <AddonSettings
                addon={addon}
                rowIndex={rowIndex}
                colIndex={colIndex}
                addonIndex={innerRowIndex}
                innerColIndex={innerColIndex}
                addonInnerIndex={index}
                />
            </div>
            <div className="sp-pagebuilder-addon-content">
              <img src={ addonIcon } alt={ addonTitle } />
              <span className="sp-pagebuilder-addon-title">{ addonTitle }</span>
              {
                admin_label != ''
                ?
                <span className="sp-pagebuilder-admin-label">{ admin_label }</span>
                :
                ''
              }
            </div>
          </div>
        )}
      </div>
    ))
  }
}

var DragSourceDecorator = DragSource( ItemTypes.INNERADDON, inenrAddonSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget( [ ItemTypes.INNERADDON, ItemTypes.ADDON ], innerAddonTarget,
  function(connect) {
    return {
      connectDropTarget: connect.dropTarget()
    };
  }
);

export default DropTargetDecorator(DragSourceDecorator(InnerAddon));
