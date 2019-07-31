import React,{ Component, PropTypes } from 'react';
import ReactDOM, { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import LinkWithTooltip from '../helpers/tooltip';
import RowSettings from './RowSettings';
import ColumnLayout from './ColumnLayout';
import Column from './Column';
import { changeColumn, columnSortable, toggleCollapse } from '../actions/index';

const rowSource = {
  beginDrag(props){
    return {
      id: props.id,
      index: props.index
    }
  }
}

const rowTarget = {
  hover(props, monitor, component) {
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

    props.moveRow(dragIndex, hoverIndex);
    monitor.getItem().index = hoverIndex;
  }
};

class Row extends Component {

  render() {
    const { text, id, row, index, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var rowClass = 'sp-pagebuilder-section';
    if(row.collapse){
      rowClass = rowClass + ' sp-pagebuilder-row-collapsed';
    }

    var customClass;
    if (isDragging) {
      if (row.visibility) {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag sp-pagebuilder-row-disable';
      }
    } else {
      if (row.visibility) {
        var customClass = 'sp-pagebuilder-section-inner';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-disable';
      }
    }

    const rowIndex = index;
    let adminTitle ="Row";

    if (row.settings.admin_label != undefined && row.settings.admin_label !=''){
      adminTitle = row.settings.admin_label;
    } else if (row.settings.title != undefined && row.settings.title !='') {
      adminTitle = row.settings.title;
    }

    let colLength = row.columns.length;

    return connectDragPreview(connectDropTarget(
      <div key={ id } className={rowClass}>
        <div className={customClass}>
          <div className="sp-pagebuilder-row-tools clearfix">
            { connectDragSource(
              <span className="sp-pagebuilder-drag-row"><i className="pbfont pbfont-drag"></i></span>
            )}
            <span className="sp-pagebuilder-row-title" ><span>{adminTitle}</span></span>
            <RowSettings row={row} index={index} />
            {
              row.visibility &&
              <ColumnLayout
                changeColumn={this.props.changeColumnGen}
                rowIndex={ index }
                current={ row.layout }
                />
            }

            <span className="sp-pagebuilder-row-toggle pull-right">
              <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ROW_TOGGLE")} position="top" id="tooltip-toggle-row">
                <a
                  href="#"
                  onClick={ (e) => {
                    e.preventDefault();
                    this.props.toggleCollapse(row.id);
                  }}
                  >
                  <i className="fa fa-angle-up"></i>
                </a>
              </LinkWithTooltip>
            </span>
          </div>
          {
            !row.collapse &&
            <div className="sp-pagebuilder-columns-container">
              <div className="sp-pagebuilder-row sp-pagebuilder-columns">
                {
                  row.columns.map( (column, index) =>
                  {return(<Column
                    key={column.id}
                    id={column.id}
                    column={column}
                    rowIndex={rowIndex}
                    index={index}
                    colLength = {colLength}
                    columnMove={this.props.columnSortable}
                    />)}
                  )
                }
              </div>
            </div>
          }
        </div>
      </div>
    ))
  }
}

var DragSourceDecorator = DragSource(ItemTypes.ROW, rowSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget(ItemTypes.ROW, rowTarget,
  function(connect) {
    return {
      connectDropTarget: connect.dropTarget()
    };
  }
);

const mapStateToProps = ( state ) => {
  return {state};
}

const mapDispatchToProps = ( dispatch ) => {
  return {
    columnSortable: ( rowIndex, dragIndex, hoverIndex ) => {
      dispatch(columnSortable(rowIndex, dragIndex, hoverIndex))
    },
    changeColumnGen: ( colLayout, current, rowIndex ) => {
      dispatch(changeColumn( colLayout, current, rowIndex ))
    },
    toggleCollapse: (id) => {
      dispatch(toggleCollapse(id))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(DragSourceDecorator(Row)));
