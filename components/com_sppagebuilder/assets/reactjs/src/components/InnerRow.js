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

    var element = findDOMNode(component),
    elementId = element.getAttribute('id');

    const hoverBoundingRect = findDOMNode(component).getBoundingClientRect();
    const hoverMiddleY      = (hoverBoundingRect.bottom - hoverBoundingRect.top) / 2;
    const clientOffset      = monitor.getClientOffset();
    const hoverClientY      = clientOffset.y - hoverBoundingRect.top;

    jQuery('#'+elementId).addClass('inner-row-placeholder');
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

    console.log(options)

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

  componentDidMount(){
    this.props.connectDragPreview( this.createCustomDragSource() );
    const { data } = this.props;
    const { settings } = data;

    // Video Player
    var videoId = '#section-id-' + data.id;
    if (typeof settings.background_video_mp4 !== 'undefined' && settings.background_video_mp4 !='') {
      var background_video_mp4 = pagebuilder_base + settings.background_video_mp4;
      jQuery(''+ videoId +'').vide(
        { mp4:background_video_mp4 },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else if (typeof settings.background_video_ogv !== 'undefined' && settings.background_video_ogv !='') {
      var background_video_ogv = pagebuilder_base + settings.background_video_ogv;
      jQuery(''+ videoId +'').vide(
        { ogv:background_video_ogv },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    }
  }

  componentDidUpdate(){
    this.props.connectDragPreview( this.createCustomDragSource() );

    const { data } = this.props;
    const { settings } = data;

    // Video Player
    var videoId = '#section-id-' + data.id;
    if (typeof settings.background_video_mp4 !== 'undefined' && settings.background_video_mp4) {
      var background_video_mp4 = pagebuilder_base + settings.background_video_mp4;
      jQuery(''+ videoId +'').vide(
        { mp4:background_video_mp4 },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else if (typeof settings.background_video_ogv !== 'undefined' && settings.background_video_ogv) {
      var background_video_ogv = pagebuilder_base + settings.background_video_ogv;
      jQuery(''+ videoId +'').vide(
        { ogv:background_video_ogv },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else {
      jQuery(''+ videoId +'').find('.sppb-bg-video-player').remove();
    }
  }

  createCustomDragSource(){
    var rowDragImg = pagebuilder_base + 'components/com_sppagebuilder/assets/images/addon.png';
    var img = new Image();
    img.src = rowDragImg;

    return img
  }

  getRowStyle(){
    const { settings } = this.props.data;
    var styles = {};

    if (typeof settings.background_color !== 'undefined' && settings.background_color) {
      styles.backgroundColor = settings.background_color;
    }

    if (typeof settings.color !== 'undefined' && settings.color) {
      styles.color = settings.color;
    }

    if (typeof settings.padding !== 'undefined' && settings.padding) {
      styles.padding = settings.padding;
    }

    if (typeof settings.margin !== 'undefined' && settings.margin) {
      styles.margin = settings.margin;
    }

    if (typeof settings.background_image !== 'undefined' && settings.background_image) {
      styles.backgroundImage = 'url(' + pagebuilder_base + settings.background_image + ')';

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

  getRowCustomClass(){
    let customClass = 'sp-pagebuilder-section sppb-section';
    const { settings } = this.props.data;

    if (typeof settings.class !== 'undefined' && settings.class) {
      customClass = customClass + ' ' + settings.class;
    }

    if (typeof settings.columns_equal_height !== 'undefined' && settings.columns_equal_height == '1') {
      customClass = customClass + ' sppb-equalize-columns';
    }

    if (typeof settings.hidden_md !== 'undefined' && settings.hidden_md == '1') {
      customClass = customClass + ' sppb-hidden-md sppb-hidden-lg';
    }

    if (typeof settings.hidden_sm !== 'undefined' && settings.hidden_sm == '1') {
      customClass = customClass + ' sppb-hidden-sm';
    }

    if (typeof settings.hidden_xs !== 'undefined' && settings.hidden_xs == '1') {
      customClass = customClass + ' sppb-hidden-xs';
    }

    if (typeof settings.animation !== 'undefined' && settings.animation ) {
      customClass = customClass + ' sppb-wow ' + settings.animation;
    }

    return customClass;
  }

  getSectionTitle(){
    const { settings } = this.props.data;
    let html = '';

    if ((typeof settings.title !=='undefined' && settings.title !='') || (typeof settings.subtitle !=='undefined' && settings.subtitle !='')) {
      if (settings.fullscreen !=='undefined' && settings.fullscreen == 1) {
        html += '<div class="sppb-container">';
      }

      let title_position = '';
      if (typeof settings.title_position !=='undefined' && settings.title_position) {
        title_position = settings.title_position;
      }

      var secStyle = '';
      if (typeof settings.title_section_padding !=='undefined' && settings.title_section_padding != '') {
        secStyle += 'padding: '+ settings.title_section_padding  + ';';
      }

      if (typeof settings.title_section_margin !=='undefined' && settings.title_section_margin != '') {
        secStyle += 'margin: '+ settings.title_section_margin  + ';';
      }

      html += '<div class="sppb-section-title ' + title_position +'" style="'+ secStyle +'">';

      /* Section Title */
      if (typeof settings.title !=='undefined' && settings.title) {
        let heading_selector = 'h2';
        if (typeof settings.heading_selector !=='undefined' && settings.heading_selector) {
          heading_selector = settings.heading_selector;
        }

        let hStyle = '';
        if (typeof settings.title_fontsize !=='undefined' && settings.title_fontsize != '') {
          hStyle += 'font-size:' + settings.title_fontsize + 'px;line-height: '+ settings.title_fontsize +'px;';
        }

        if (typeof settings.title_fontweight !=='undefined' && settings.title_fontweight != '') {
          hStyle += 'font-weight: '+ settings.title_fontweight + ';';
        }

        if (typeof settings.title_text_color !=='undefined' && settings.title_text_color != '') {
          hStyle += 'color: '+ settings.title_text_color  + ';';
        }

        if (typeof settings.title_margin_top !=='undefinded' && settings.title_margin_top != '') {
          hStyle += 'margin-top: '+ settings.title_margin_top  + 'px;';
        }

        if (typeof settings.title_margin_bottom !=='undefined' && settings.title_margin_bottom != '') {
          hStyle += 'margin-bottom: '+ settings.title_margin_bottom  + 'px;';
        }

        html +='<'+heading_selector+' class="sppb-title-heading" style="'+ hStyle +'">'+ settings.title +'</'+heading_selector+'>';

        if (typeof settings.subtitle !=='undefined' && settings.subtitle != '') {
          html += '<p class="sppb-title-subheading" style="';

          if (typeof settings.subtitle_fontsize !=='undefined' && settings.subtitle_fontsize != '') {
            html += 'font-size: '+ settings.subtitle_fontsize  + 'px;';
          }

          html += '">'+ settings.subtitle +'</p>';
        }
      }
      html += '</div>';

      if (settings.fullscreen !=='undefinded' && settings.fullscreen == 1) {
        html += '</div>';
      }
    }

    return html;
  }

  render() {
    const { data, rowIndex, isOver, colIndex, index, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var customClass = "sp-pagebuilder-section-inner";

    const { settings } = data;

    var dragStyle = {};
    if (isDragging) {
      if (data.visibility) {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag sp-pagebuilder-row-disable';
      }
      dragStyle.display = 'none';
    }else{
      if (data.visibility) {
        var customClass = 'sp-pagebuilder-section-inner';
      } else {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-disable';
      }
    }

    var overClass = "sp-pagebuilder-section-parent";
    if ( isOver && !isDragging ) {
      overClass = overClass + ' sp-pagebuilder-addon-over';
    }

    let rowId = 'section-id-' + data.id;
    let styles = this.getRowStyle();
    let sectionClass = this.getRowCustomClass();
    let cusHtml = this.getSectionTitle();

    if (typeof settings.animationduration !== 'undefined' && settings.animationduration !='') {
      var animationduration = settings.animationduration+'ms';
    }

    if (typeof settings.animationdelay !== 'undefined' && settings.animationdelay !='') {
      var animationdelay = settings.animationdelay+'ms';
    }

    var row_class = 'sppb-row';
    if (typeof settings.no_gutter !=='undefined' && settings.no_gutter == 1) {
      row_class = row_class + ' sppb-no-gutter';
    }

    var overlayStyle = {};
    if (typeof settings.animationdelay !== 'undefined' && settings.animationdelay !='') {
      overlayStyle.backgroundColor = settings.overlay;
    }

    let colLength = data.columns.length;

    let innerRowId = 'sppb-inner-row-id-' + data.id;

    return connectDragPreview(
      <div
        id={innerRowId}
        key={ data.id }
        className={overClass}
        style={dragStyle}
      >
        <div>
          <div className="clearfix">

            {
              connectDropTarget(
                <div className="sp-pagebuilder-row-tools inner-row-tools clearfix">
                  { connectDragSource(
                    <span className="sp-pagebuilder-drag-row"><i className="pbfont pbfont-drag"></i></span>
                  )}

                  <RowSettings row={data} index={rowIndex} colIndex={colIndex} innerRowIndex={index} />
                  {
                    data.visibility &&
                    <ColumnLayout
                      changeInnerColumn={ this.props.changeInnerColumnGen }
                      rowIndex={ rowIndex }
                      current={ data.layout }
                      colIndex={colIndex}
                      innerRowIndex={index} />
                  }
                </div>
              )
            }

            <div className="sp-pagebuilder-section">
              <div className={customClass}>
                <div
                  id={rowId}
                  className={sectionClass}
                  data-sppb-wow-duration={animationduration}
                  data-sppb-wow-delay={animationdelay}
                  style={styles}
                >
                  {
                    (typeof settings.overlay !== 'undefined' && settings.overlay != '') &&
                    <div className="sppb-row-overlay" style={overlayStyle}></div>
                  }
                  {
                    cusHtml &&
                    <div dangerouslySetInnerHTML={ { __html: cusHtml } } />
                  }
                  <div className={row_class}>
                    {data.columns.map((column, i) => {
                      return(
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
                          colLength={colLength} />
                      )}
                    )}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    )
  }
}

var DragSourceDecorator = DragSource( ItemTypes.INNERROW, innerRowSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget( [ ItemTypes.ADDON, ItemTypes.INNERROW, ItemTypes.INNERADDON ], innerRowTarget,
  function(connect,monitor) {
    return {
      connectDropTarget: connect.dropTarget(),
      isOver: monitor.isOver(),
      canDrop: monitor.canDrop()
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
