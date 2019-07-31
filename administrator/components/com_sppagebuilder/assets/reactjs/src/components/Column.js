import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import ColumnSettings from './ColumnSettings';
import AddonList from './AddonList';

const columnSource = {
  beginDrag(props){
    return {
      id: props.id,
      index: props.index,
      rowIndex: props.rowIndex
    }
  }
}

const columnTarget = {

  canDrop( props, monitor ){
    if ( monitor.getItem().rowIndex !== props.rowIndex ) {
      return;
    }
    return true;
  },

  hover(props, monitor, component) {
    if (monitor.getItem().rowIndex !== props.rowIndex) {
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

    props.columnMove(props.rowIndex, dragIndex, hoverIndex);
    monitor.getItem().index = hoverIndex;
  }
};

class Column extends Component {

  render() {
    const {
      column,
      rowIndex,
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

    if (!column.visibility) {
      columnClass = columnClass + ' sp-pagebuilder-column-disabled';
    }

    return connectDragPreview(connectDropTarget(
      <div key={ column.id } className={columnClass + " " + column.class_name }>
        <div>
          <div className="sp-pagebuilder-column-tools clearfix">
            { connectDragSource(
              <div className="sp-pagebuilder-drag-column"><i className="pbfont pbfont-drag"></i></div>
            )}
            <ColumnSettings
              id={column.id}
              column={column}
              rowIndex={rowIndex}
              colIndex={index}
              colLength={this.props.colLength}
              />
          </div>
          <AddonList
            key={ column.id }
            addons={ column.addons }
            rowIndex={ rowIndex }
            colIndex={ index }
            dropAddon={ this.props.dropAddon }
            />
        </div>
      </div>
    ))
  }
}

var DragSourceDecorator = DragSource(ItemTypes.COLUMN, columnSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget(ItemTypes.COLUMN, columnTarget,
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
    dropAddon: (options) => {
      dispatch(options);
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(DragSourceDecorator(Column)));
