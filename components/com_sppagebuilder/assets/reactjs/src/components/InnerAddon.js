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

  drop(props, monitor, component) {
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
    const { addon, column, id, isOver, index, innerRowId, innerColId, rowIndex, colIndex, innerRowIndex, innerColIndex, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

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
        <div>
          <div className="sp-pagebuilder-addon-tools">
            <AddonSettings
              addon={addon}
              rowIndex={rowIndex}
              colIndex={colIndex}
              addonIndex={innerRowIndex}
              innerColIndex={innerColIndex}
              addonInnerIndex={index}
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
                    addonIndex={innerRowIndex}
                    innerColIndex={innerColIndex}
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
)(DropTargetDecorator(DragSourceDecorator(InnerAddon)));
