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

  componentDidMount() {
    this.props.connectDragPreview( this.createCustomDragSource() );
    const { row } = this.props;

    // Video Player
    var videoId = '#section-id-' + row.id;
    if (typeof row.settings.background_video_mp4 !== 'undefined' && row.settings.background_video_mp4 !='') {
      var background_video_mp4 = pagebuilder_base + row.settings.background_video_mp4;
      jQuery(''+ videoId +'').vide(
        { mp4:background_video_mp4 },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else if (typeof row.settings.background_video_ogv !== 'undefined' && row.settings.background_video_ogv !='') {
      var background_video_ogv = pagebuilder_base + row.settings.background_video_ogv;
      jQuery(''+ videoId +'').vide(
        { ogv:background_video_ogv },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    }
  }

  componentDidUpdate() {
    this.props.connectDragPreview( this.createCustomDragSource() );
    const { row } = this.props;

    // Video Player
    var videoId = '#section-id-' + row.id;
    if (typeof row.settings.background_video_mp4 !== 'undefined' && row.settings.background_video_mp4) {
      var background_video_mp4 = pagebuilder_base + row.settings.background_video_mp4;
      jQuery(''+ videoId +'').vide(
        { mp4:background_video_mp4 },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else if (typeof row.settings.background_video_ogv !== 'undefined' && row.settings.background_video_ogv) {
      var background_video_ogv = pagebuilder_base + row.settings.background_video_ogv;
      jQuery(''+ videoId +'').vide(
        { ogv:background_video_ogv },
        { posterType:'none', className:'sppb-bg-video-player' }
      );
    } else {
      jQuery(''+ videoId +'').find('.sppb-bg-video-player').remove();
    }
  }

  createCustomDragSource(){
    var rowDragImg = pagebuilder_base + 'components/com_sppagebuilder/assets/images/row.png';
    var img = new Image();
    img.src = rowDragImg;

    return img
  }

  getRowStyle(){
    const { settings } = this.props.row;
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

      var bdImage = settings.background_image;

      if (bdImage.startsWith('http') == true) {
        bdImage = settings.background_image;
      } else {
        bdImage = pagebuilder_base + settings.background_image;
      }

      styles.backgroundImage = 'url(' + bdImage + ')';

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
    const { settings } = this.props.row;

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
    const { settings } = this.props.row;
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
    const { id, row, index, isOver, canDrop, isDragging, connectDropTarget, connectDragPreview, connectDragSource } = this.props;

    var row_class = 'sppb-row';
    if (typeof row.settings.no_gutter !=='undefined' && row.settings.no_gutter == 1) {
      row_class = row_class + ' sppb-no-gutter';
    }

    var customClass;
    if (isDragging && !isOver) {
        var customClass = 'sp-pagebuilder-section-inner sp-pagebuilder-row-drag';
    }else{
        var customClass = 'sp-pagebuilder-section-inner';
    }

    var overClass= 'sp-pagebuilder-section-parent';
    if (isOver && !isDragging) {
      overClass = overClass + ' sp-pagebuilder-row-over';
    }

    if (!row.visibility) {
      overClass = overClass + ' sp-pagebuilder-row-disable';
    }

    const rowIndex = index;
    let colLength = row.columns.length;

    let fluid_row = 0;
    if (row.settings.fullscreen != undefined) {
      fluid_row = row.settings.fullscreen;
    }

    let rowId = 'section-id-' + row.id;
    let styles = this.getRowStyle();
    let sectionClass = this.getRowCustomClass();
    let cusHtml = this.getSectionTitle();

    if (typeof row.settings.animationduration !== 'undefined' && row.settings.animationduration !='') {
      var animationduration = row.settings.animationduration+'ms';
    }

    if (typeof row.settings.animationdelay !== 'undefined' && row.settings.animationdelay !='') {
      var animationdelay = row.settings.animationdelay+'ms';
    }
    var overlayStyle = {};
    if (typeof row.settings.animationdelay !== 'undefined' && row.settings.animationdelay !='') {
      overlayStyle.backgroundColor = row.settings.overlay;
    }

    return connectDragPreview(connectDropTarget(
      <div
        key={id}
        className={overClass}>
        <div>
          <div className="clearfix">
            <div className="sp-pagebuilder-row-tools clearfix">
              { connectDragSource(
                <span className="sp-pagebuilder-drag-row"><i className="pbfont pbfont-drag"></i></span>
              )}

              {
                row.visibility &&
                <ColumnLayout
                  changeColumn={this.props.changeColumnGen}
                  rowIndex={ index }
                  current={ row.layout }
                  />
              }
              <RowSettings row={row} index={index} />
            </div>
            <div className="sp-pagebuilder-section">
              <div className={customClass}>
                {
                  fluid_row == 0
                  ?
                  <section
                    id={rowId}
                    className={sectionClass}
                    data-sppb-wow-duration={animationduration}
                    data-sppb-wow-delay={animationdelay}
                    style={styles}>

                    {
                      (typeof row.settings.overlay !== 'undefined' && row.settings.overlay != '') &&
                      <div className="sppb-row-overlay" style={overlayStyle}></div>
                    }

                    <div className="sppb-row-container">
                      {
                        cusHtml &&
                        <div dangerouslySetInnerHTML={ { __html: cusHtml } } />
                      }

                      <div className={row_class}>
                        { row.columns.map( (column, index) =>
                          {
                            return(
                              <Column
                                key={column.id}
                                id={column.id}
                                column={column}
                                rowIndex={rowIndex}
                                index={index}
                                colLength = {colLength}
                                columnMove={this.props.columnSortable} />
                            )
                          }
                        )}
                      </div>
                    </div>
                  </section>
                  :
                  <div
                    id={rowId}
                    className={sectionClass}
                    data-sppb-wow-duration={animationduration}
                    data-sppb-wow-delay={animationdelay} style={styles}>
                    {
                      (typeof row.settings.overlay !== 'undefined' && row.settings.overlay != '') &&
                      <div className="sppb-row-overlay" style={overlayStyle}></div>
                    }
                    <div className="sppb-container-inner">
                      {
                        cusHtml &&
                        <div dangerouslySetInnerHTML={ { __html: cusHtml } } />
                      }
                      <div className={row_class}>
                        { row.columns.map( (column, index) =>
                          {
                            return(
                              <Column
                                key={column.id}
                                id={column.id}
                                column={column}
                                rowIndex={rowIndex}
                                index={index}
                                colLength = {colLength}
                                columnMove={this.props.columnSortable} />
                            )
                          }
                        )}
                      </div>
                    </div>
                  </div>
                }
              </div>
            </div>
          </div>
        </div>
      </div>
    ))
  }
}

var DragSourceDecorator = DragSource( ItemTypes.ROW, rowSource,
  function(connect, monitor) {
    return {
      connectDragSource: connect.dragSource(),
      connectDragPreview: connect.dragPreview(),
      isDragging: monitor.isDragging()
    };
  }
);

var DropTargetDecorator = DropTarget(ItemTypes.ROW, rowTarget,
  function(connect, monitor) {
    return {
      connectDropTarget: connect.dropTarget(),
      isOver: monitor.isOver(),
      canDrop: monitor.canDrop()
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
