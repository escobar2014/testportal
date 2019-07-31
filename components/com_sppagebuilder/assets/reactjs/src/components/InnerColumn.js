import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import ColumnSettings from './ColumnSettings';
import InnerAddonList from './InnerAddonList';

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

  constructor(props) {
    super(props);
    this.state = {
      move: false,
      index: this.props.index
    };
  }

  componentDidMount(){
    this.props.connectDragPreview( this.createCustomDragSource() );
  }

  componentDidUpdate(){
    if ( this.props.index != this.state.index ) {
      this.setState({
        move: false,
        index: this.props.index
      })
    }

    this.props.connectDragPreview( this.createCustomDragSource() );
  }

  createCustomDragSource(){
    var rowDragImg = pagebuilder_base + 'components/com_sppagebuilder/assets/images/column.png';
    var img = new Image();
    img.src = rowDragImg;

    return img
  }

  _activeMoveButton(move){
    this.setState({
      move: move
    });
  }

  getColumnStyle(){
    const { settings } = this.props.column;
    var styles = {};
    if (typeof settings.background !== 'undefined' && settings.background) {
      styles.backgroundColor = settings.background;
    }

    if (typeof settings.color !== 'undefined' && settings.color) {
      styles.color = settings.color;
    }

    if (typeof settings.padding !== 'undefined' && settings.padding) {
      styles.padding = settings.padding;
    }

    if (typeof settings.background_image !== 'undefined' && settings.background_image) {
      styles.backgroundImage = "url(" + pagebuilder_base + "/" + settings.background_image + ")";

      if (typeof settings.background_repeat !== 'undefined' && settings.background_repeat) {
        styles.backgroundRepeat = settings.background_repeat;
      }

      if (typeof settings.background_size !== 'undefined' && settings.background_size) {
        styles.backgroundSize = settings.background_size;
      }

      if (typeof settings.background_attachment !== 'undefined' && settings.background_attachment) {
        styles.backgroundAttachment = settings.background_attachment;
      }

      if (typeof settings.background_position !== 'undefined' && settings.background_position) {
        styles.backgroundPosition = settings.background_position;
      }
    }

    return styles;
  }

  getColumnClassName(){
    const { isOver, canDrop, isDragging } = this.props;
    const { visibility, class_name, settings } = this.props.column;;

    var columnClass = 'sppb-' + class_name;

    if (isDragging) {
      columnClass = 'sp-pagebuilder-dragging ' + columnClass;
    }

    if (isOver && !canDrop) {
      columnClass = 'sp-pagebuilder-not-droppable ' + columnClass;
    }

    if (!visibility) {
      columnClass = columnClass + ' sp-pagebuilder-column-disabled';
    }

    if (typeof settings.hidden_md !== 'undefined' && settings.hidden_md == 1) {
      columnClass = columnClass + ' sppb-hidden-md';
    }

    if (typeof settings.hidden_sm !== 'undefined' && settings.hidden_sm == 1) {
      columnClass = columnClass + ' sppb-hidden-sm';
    }

    if (typeof settings.hidden_xs !== 'undefined' && settings.hidden_xs == 1) {
      columnClass = columnClass + ' sppb-hidden-xs';
    }

    if (typeof settings.sm_col !== 'undefined' && settings.sm_col) {
      columnClass = columnClass + ' sppb-' + settings.sm_col;
    }

    if (typeof settings.xs_col !== 'undefined' && settings.xs_col) {
      columnClass = columnClass + ' sppb-' + settings.xs_col;
    }

    return columnClass;
  }

  getColumnInnerClassName(){
    const { settings } = this.props.column;
    var innerColumnClass = 'sp-pagebuilder-column';

    if (typeof settings.animation !== 'undefined' && settings.animation) {
      innerColumnClass = innerColumnClass + ' sppb-wow ' + settings.animation;
    }

    return innerColumnClass;
  }

  render() {
    const { rowIndex, colIndex, addonIndex, innerRowId, column, isOver, index, canDrop, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    const { settings } = column;

    let innerColumnId = 'column-id-' + column.id;
    let newStyle = this.getColumnStyle();
    let customClass = this.getColumnClassName();
    let customInnerClass = this.getColumnInnerClassName();

    if ( isOver && !isDragging ) {
      customClass = customClass + ' sp-pagebuilder-column-over';
    }

    if ( this.state.move ) {
      customInnerClass = customInnerClass + ' sp-pagebuilder-column-movable'
    }

    // Animation
    if ( typeof settings.animationduration !== 'undefined' && settings.animationduration ) {
      var animationduration = settings.animationduration+'ms';
    }

    if ( typeof settings.animationdelay !== 'undefined' && settings.animationdelay ) {
      var animationdelay = settings.animationdelay+'ms';
    }

    // Overlay
    var overlayStyle = {};
    if(typeof settings.overlay !== 'undefined' && settings.overlay != '') {
      overlayStyle.backgroundColor = settings.overlay;
    }

    return connectDragPreview(connectDropTarget(
      <div
        key={ column.id }
        className={customClass}
      >
        <div id={innerColumnId} className={customInnerClass} data-sppb-wow-duration={animationduration} data-sppb-wow-delay={animationdelay} style={newStyle}>
        {
          this.state.move &&
          <a
            href="#"
            className="close-column-movable"
            onClick={(e) => {
              e.preventDefault();
              this.setState({
                move:false
              })
            }}
            ><i className="fa fa-close"></i></a>
        }
        <div className="sp-pagebuilder-column-tools clearfix">
          { connectDragSource(
            <div className="sp-pagebuilder-drag-column"><i className="pbfont pbfont-drag"></i></div>
          )}
        </div>
          {
            typeof settings.overlay !== 'undefined' && settings.overlay != ''
            ? <div className="sppb-column-overlay" style={overlayStyle}></div>
            : ''
          }
          <InnerAddonList
            key={ column.id }
            innerRowId={ innerRowId }
            innerColId={ column.id }
            rowIndex={ rowIndex }
            colIndex={ colIndex }
            innerRowIndex={ addonIndex }
            innerColIndex={ index }
            column={ column }
            addons={ column.addons }
            dropInnerAddon = {this.props.dropInnerAddon}
            moveButton={ this._activeMoveButton.bind(this)}
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
