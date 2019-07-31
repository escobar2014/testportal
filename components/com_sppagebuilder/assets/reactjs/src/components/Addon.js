import React, { Component, PropTypes } from 'react';
import { findDOMNode } from 'react-dom';
import { connect } from 'react-redux'
import { ItemTypes } from './Constants';
import { DragSource, DropTarget } from 'react-dnd';
import AddonSettings from './AddonSettings';
import AddonContent from './AddonContent';
import LinkWithTooltip from '../helpers/tooltip';
import { ModalManager} from '../helpers/index';
import SpPageBuilderModal from '../helpers/modal';
import { saveSetting } from '../actions/index';
import deepEqual from 'deep-equal'

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

    var element = findDOMNode(component),
    elementId = element.getAttribute('id');

    const hoverBoundingRect = findDOMNode(component).getBoundingClientRect();
    const hoverMiddleY      = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
    const clientOffset      = monitor.getClientOffset();
    const hoverClientY      = clientOffset.y - hoverBoundingRect.top;

    if (hoverClientY < hoverMiddleY) {
      jQuery('#'+elementId).removeClass('bottom-placeholder').addClass('top-placeholder');
    }
    if (hoverClientY > hoverMiddleY) {
      jQuery('#'+elementId).removeClass('top-placeholder').addClass('bottom-placeholder');
    }
  },

  drop( props, monitor, component ) {
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

    var options = {
      drag        : item,
      dragIndex   : dragIndex,
      drop        : props,
      hoverIndex  : hoverIndex
    };

    if (hoverClientY < hoverMiddleY) {
      options.dropPosition = 'top';
    }

    if (hoverClientY > hoverMiddleY) {
      options.dropPosition = 'bottom';
    }

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

    // console.log(options);

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

  componentDidMount(){
    this.props.connectDragPreview( this.createCustomDragSource() );
  }

  componentDidUpdate(){
    this.props.connectDragPreview( this.createCustomDragSource() );
  }

  createCustomDragSource(){
    var rowDragImg = pagebuilder_base + 'components/com_sppagebuilder/assets/images/addon.png';
    var img = new Image();
    img.src = rowDragImg;

    return img
  }

  render() {
    const { addon, rowIndex, colIndex, column, index, isOver, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var addonClass = "sp-pagebuilder-addon";
    var styles = {};
    if(isDragging && !isOver) {
      addonClass = "sp-pagebuilder-dragging " + addonClass;
      styles.display = 'none';
    }

    if ( isOver && !isDragging ) {
      addonClass = addonClass + ' sp-pagebuilder-addon-over';
    }

    if(!addon.visibility) {
      addonClass = addonClass + " sp-pagebuilder-addon-disabled";
    }

    let addonKey = 'addon-wrap-' + addon.id;

    return connectDragPreview(connectDropTarget(
      <div key={ addon.id } id={addonKey} className={addonClass} style={styles}>
        <div className="addon-placeholder-top">Drop Here</div>
        <div className="sp-pagebuilder-addon-inner-wrap">
          <div className="sp-pagebuilder-addon-tools">
            <AddonSettings
              addon = { addon }
              rowIndex = { rowIndex }
              colIndex = { colIndex }
              addonIndex = { index }
              column = { column }
              columnMove = { this.props.columnMove }
              connectDragSource = { connectDragSource }
              />
          </div>
          <AddonContent addon = { addon } />
          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ADDON")} position="bottom" id="add-new-addon-bottom">
            <a
              className="sp-pagebuilder-add-addon"
              href="#"
              onClick = {(e) => {
                e.preventDefault();
                ModalManager.open(
                  <SpPageBuilderModal
                    sectionType="list"
                    saveSetting={this.props.onSettingsClick}
                    rowIndex={rowIndex}
                    colIndex={colIndex}
                    onRequestClose={() => true}/>
                );
              }}
              >
              <i className="pbfont pbfont-plus"></i>
            </a>
          </LinkWithTooltip>
        </div>
        <div className="addon-placeholder-bottom">Drop Here</div>
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
  function(connect,monitor) {
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
    onSettingsClick: (options) => {
      dispatch(saveSetting(options))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DropTargetDecorator(DragSourceDecorator(Addon)));
