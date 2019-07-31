import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import ColumnSettings from './ColumnSettings';
import InnerAddonList from './InnerAddonList';

/*
import ColumnSettings from './ColumnSettings';
*/
const innerColumnSource = {
  beginDrag(props){
    return {
      id          : props.id,
      index       : props.index,
      innerRowId  : props.innerRowId,
      rowIndex    : props.rowIndex,
      colIndex    : props.colIndex,
      addonIndex  : props.addonIndex
    }
  }
}

const innerColumnTarget = {

  canDrop( props, monitor ){
    if ( monitor.getItem().innerRowId !== props.innerRowId ) {
      return;
    }
    return true;
  },

  hover(props, monitor, component) {
    if (monitor.getItem().innerRowId !== props.innerRowId) {
      return;
    }

    const dragIndex = monitor.getItem().index;
    const hoverIndex = props.index;

    if (dragIndex === hoverIndex) {
      return;
    }

    const hoverBoundingRect = findDOMNode(component).getBoundingClientRect();
    const hoverMiddleY = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
    const clientOffset = monitor.getClientOffset();
    const hoverClientY = clientOffset.y - hoverBoundingRect.top;

    if (dragIndex < hoverIndex && hoverClientY < hoverMiddleY) {
      return;
    }

    if (dragIndex > hoverIndex && hoverClientY > hoverMiddleY) {
      return;
    }

    props.innerColumnMove(props.rowIndex, props.colIndex, props.addonIndex, dragIndex, hoverIndex);

    monitor.getItem().index = hoverIndex;
  }
};

class InnerColumn extends Component {
  render() {
    const {
      rowIndex,
      colIndex,
      addonIndex,
      innerRowId,
      column,
      isOver,
      index,
      canDrop,
      isDragging,
      connectDropTarget,
      connectDragPreview,
      connectDragSource
    } = this.props;

    var columnClass = "sp-pagebuilder-column";

    if (isDragging) {
      columnClass = 'sp-pagebuilder-dragging ' + columnClass;
    }

    if (isOver && !canDrop) {
      columnClass = 'sp-pagebuilder-not-droppable ' + columnClass;
    }

    return connectDragPreview(connectDropTarget(
      <div key={ column.id } className={columnClass + " " + column.class_name }>
        <div>
          <div className="sp-pagebuilder-column-tools clearfix">
            { connectDragSource(
              <div className="sp-pagebuilder-drag-column"><i className="pbfont pbfont-drag"></i></div>
            )}
            <ColumnSettings
              column={column}
              rowIndex={rowIndex}
              colIndex={colIndex}
              colLength={this.props.colLength}
              innerRowIndex={addonIndex}
              innerColIndex={index}
              />
          </div>
          <InnerAddonList
            key={ column.id }
            innerRowId={ innerRowId }
            innerColId={ column.id }
            rowIndex={ rowIndex }
            colIndex={ colIndex }
            innerRowIndex={ addonIndex }
            innerColIndex={ index }
            addons={ column.addons }
            dropInnerAddon = {this.props.dropInnerAddon}
            />
        </div>
      </div>
    ))
  }
}

var DragSourceDecorator = DragSource( ItemTypes.INNERCOLUMN, innerColumnSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget( ItemTypes.INNERCOLUMN, innerColumnTarget,
  function( connect, monitor) {
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
    dropInnerAddon: (options) => {
      dispatch(options)
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(DragSourceDecorator(InnerColumn)));
