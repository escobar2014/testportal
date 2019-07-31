import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import AddonSettings from './AddonSettings';

const addonSource = {
  beginDrag(props){
    return {
      id: props.id,
      index: props.index,
      rowIndex: props.rowIndex,
      colIndex: props.colIndex
    }
  }
}

const addonTarget = {
  hover( props, monitor, component ) {
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

    if (typeof item.innerRowIndex === 'undefined') {
      if (item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
        options.type = 'ADDON_SORT_COL_INNER';
      } else if (item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex){
        options.type = 'ADDON_SORT_COL';
      } else {
        options.type = 'ADDON_SORT_OUTER_ROW';
      }
    } else {
      if (item.rowIndex === props.rowIndex && item.colIndex === props.colIndex) {
        options.type = 'ADDON_SORT_PARENT_COL_INNER';
      } else if (item.rowIndex === props.rowIndex && item.colIndex !== props.colIndex){
        options.type = 'ADDON_SORT_PARENT_COL';
      } else {
        options.type = 'ADDON_SORT_PARENT_OUTER_ROW';
      }
    }

    props.addonSort(options);

    monitor.getItem().index         = hoverIndex;
    monitor.getItem().innerRowId    = props.innerRowId;
    monitor.getItem().innerColId    = props.innerColId;
    monitor.getItem().rowIndex      = props.rowIndex;
    monitor.getItem().colIndex      = props.colIndex;
    monitor.getItem().innerRowIndex = props.innerRowIndex;
    monitor.getItem().innerColIndex = props.innerColIndex;
  }
};

class Addon extends Component {
  render() {
    const { addon, rowIndex, colIndex, index, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var addonClass = "sp-pagebuilder-addon";
    if(isDragging) {
      addonClass = "sp-pagebuilder-dragging " + addonClass;
    }

    if(!addon.visibility) {
      addonClass = addonClass + " sp-pagebuilder-addon-disabled";
    }

    let addonName  = addon.name;
    let addonTitle = addonName;
    let addonIcon = pagebuilder_base + '/administrator/components/com_sppagebuilder/assets/img/addon-default.png';
    if (addonsJSON[addonName]) {
      addonTitle  = addonsJSON[addonName].title;
      addonIcon   = addonsJSON[addonName].icon;
    }

    let admin_label = '';
    if(addon.settings.admin_label !== undefined && addon.settings.admin_label !== '') {
      admin_label = addon.settings.admin_label;
    } else if ( addon.settings.title !== undefined && addon.settings.title !== '' ) {
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
                addonIndex={index}
                />
            </div>
            <div className="sp-pagebuilder-addon-content">
              <img src={ addonIcon } alt={ addonTitle } />
              <span className="sp-pagebuilder-addon-title">{ addonTitle }</span>
              {
                admin_label != ''
                ?
                <span className="sp-pagebuilder-admin-label" dangerouslySetInnerHTML={ { __html: admin_label } } />
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

var DragSourceDecorator = DragSource( ItemTypes.ADDON, addonSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget( [ ItemTypes.ADDON, ItemTypes.INNERROW, ItemTypes.INNERADDON ], addonTarget,
  function(connect) {
    return {
      connectDropTarget: connect.dropTarget()
    };
  }
);

export default DropTargetDecorator(DragSourceDecorator(Addon));
