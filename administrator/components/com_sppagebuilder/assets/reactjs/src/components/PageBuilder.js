import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux'
import { DragDropContext } from 'react-dnd';
import HTML5Backend from 'react-dnd-html5-backend';
import { ModalManager} from '../helpers/index';
import PageListModal from '../helpers/PageListModal';
import { addRow, importPage, rowSortable } from '../actions/index';
import Row from './Row';

class Pagebuilder extends Component{
  render(){
    const page = this.props.state.pageBuilder;

    if (document.getElementById('jform_sptext') != null) {
      var pageFiled = document.getElementById('jform_sptext')
      pageFiled.value = JSON.stringify(page.present);
    } else if (document.getElementById('jform_attribs_sppagebuilder_content') != null) {
      var pageFiled = document.getElementById('jform_attribs_sppagebuilder_content')
      pageFiled.value = JSON.stringify(page.present);
    }else if (document.getElementById('jform_params_content') != null) {
      var pageFiled = document.getElementById('jform_params_content')
      pageFiled.value = JSON.stringify(page.present);
    }

    return (
      <div id="sp-page-builder" className="sp-pagebuilder">
        {
          page.present.length
          ?
          page.present.map( (row, index) =>
          {return (
            <Row
              key={row.id}
              text={row.text}
              id={row.id}
              index={index}
              class_name={row.class_name}
              moveRow={this.props.rowSortable}
              row={row}
              />
          )}
        )
        :
        <div className="sp-pagebuilder-blank-page-tools">
          <ul>
            <li>
              <a
                className="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-lg"
                href="#"
                onClick={ (e) => {
                  e.preventDefault();
                  this.props.addNewRow();
                }}>
                <span><i className="pbfont pbfont-plus-circle"></i> {Joomla.JText._("COM_SPPAGEBUILDER_ADD_NEW_ROW")}</span>
              </a>
            </li>

            <li>
              <a
                className="sp-pagebuilder-btn sp-pagebuilder-btn-success sp-pagebuilder-btn-lg"
                href="#"
                onClick={(e)=>{
                  e.preventDefault();
                  document.getElementById('upload-file').click();
                }}>
                <span><i className="pbfont pbfont-import"></i> {Joomla.JText._("COM_SPPAGEBUILDER_IMPORT_PAGE_ALT")}</span>
              </a>
            </li>

            <li>
              <a
                className="sp-pagebuilder-btn sp-pagebuilder-btn-warning sp-pagebuilder-btn-lg"
                href="#"
                onClick={ (e) => {
                  e.preventDefault();
                  ModalManager.open(
                    <PageListModal
                      importPage={this.props.importPage}
                      onRequestClose={() => true}
                      />
                  )
                }}>
                <span><i className="pbfont pbfont-layout"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PAGE_TEMPLATES")}</span>
              </a>
            </li>
          </ul>
        </div>
      }
    </div>
  )
}
}

const mapStateToProps = ( state ) => {
  return {
    state: state
  };
}

const mapDispatchToProps = ( dispatch ) => {
  return {
    rowSortable: ( dragIndex, hoverIndex ) => {
      dispatch(rowSortable(dragIndex,hoverIndex))
    },
    addNewRow: () => {
      dispatch(addRow())
    },
    importPage: ( page ) => {
      dispatch(importPage(page))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DragDropContext(HTML5Backend)(Pagebuilder));
