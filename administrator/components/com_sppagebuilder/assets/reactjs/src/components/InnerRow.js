import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import RowSettings from './RowSettings';
import ColumnLayout from './ColumnLayout';
import InnerColumn from './InnerColumn';
import { changeInnerColumn, innerColumnSortable } from '../actions/index';

const innerRowSource = {
  beginDrag(props){
    return {
      id: props.id,
      index: props.index,
      rowIndex: props.rowIndex,
      colIndex: props.colIndex
    }
  }
}

const innerRowTarget = {
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

class InnerRow extends Component {
  render() {
    const { data, rowIndex, isOver, colIndex, index, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var customClass = "sp-pagebuilder-section-inner";

    if (isDragging) {
      if (data.visibility) {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag sp-pagebuilder-row-disable';
      }
    }else{
      if (data.visibility) {
        var customClass = 'sp-pagebuilder-section-inner';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-disable';
      }
    }

    let colLength = data.columns.length;

    let adminTitle ="Row";

    if (data.settings.admin_label != undefined && data.settings.admin_label !=''){
      adminTitle = data.settings.admin_label;
    } else if (data.settings.title != undefined && data.settings.title !='') {
      adminTitle = data.settings.title;
    }

    return connectDragPreview(
      <div key={ data.id } className="sp-pagebuilder-section">
        <div className={customClass}>
          {connectDropTarget(
            <div className="sp-pagebuilder-row-tools clearfix">
              { connectDragSource(
                <span className="sp-pagebuilder-drag-row"><i className="pbfont pbfont-drag"></i></span>
              )}

              <span className="sp-pagebuilder-row-title sp-pagebuilder-inner-row-title" ><span>{adminTitle}</span></span>

              <RowSettings row={data} index={rowIndex} colIndex={colIndex} innerRowIndex={index} />
              {
                data.visibility &&
                <ColumnLayout
                  changeInnerColumn={ this.props.changeInnerColumnGen }
                  rowIndex={ rowIndex }
                  current={ data.layout }
                  colIndex={colIndex}
                  innerRowIndex={index}
                  />
              }
            </div>
          )}

          <div className="sp-pagebuilder-row sp-pagebuilder-columns">
            {
              data.columns.map( (column, i) =>
              {return(
                <InnerColumn
                  key={column.id}
                  id={column.id}
                  index={i}
                  column={column}
                  rowIndex={rowIndex}
                  colIndex={colIndex}
                  addonIndex={index}
                  innerRowId={data.id}
                  innerColumnMove={this.props.innerColumnSortable}
                  colLength={colLength}
                  />
              )}
            )}
          </div>
        </div>
      </div>
    )
  }
}

var DragSourceDecorator = DragSource(ItemTypes.INNERROW, innerRowSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget([ ItemTypes.INNERROW, ItemTypes.ADDON, ItemTypes.INNERADDON ], innerRowTarget,
  function(connect,monitor) {
    return {
      connectDropTarget: connect.dropTarget()
    };
  }
);

const mapStateToProps = ( state ) => {
  return {};
}

const mapDispatchToProps = ( dispatch ) => {
  return {
    innerColumnSortable: ( rowIndex, colIndex, addonIndex, dragIndex, hoverIndex ) => {
      dispatch(innerColumnSortable(rowIndex, colIndex, addonIndex, dragIndex, hoverIndex))
    },
    changeInnerColumnGen: ( colLayout, current, rowIndex, colIndex, innerRowIndex ) => {
      dispatch(changeInnerColumn( colLayout, current, rowIndex, colIndex, innerRowIndex ))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(DragSourceDecorator(InnerRow)));
