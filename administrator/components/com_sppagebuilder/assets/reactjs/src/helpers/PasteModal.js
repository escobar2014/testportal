import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Modal, ModalManager } from './index'

class PasteModal extends Component {

  constructor(props) {
    super(props);
    this.state = {
      rowIndex: ''
    };
  }

  componentDidMount() {
    if ( this.props.dataType === 'row' ) {
      this.setState({
        type: this.props.dataType,
        rowIndex: this.props.rowIndex
      });
    } else if( this.props.dataType === 'inner_row') {
      this.setState({
        type: this.props.dataType,
        rowIndex: this.props.rowIndex,
        colIndex: this.props.colIndex,
        addonIndex: this.props.addonIndex
      });
    }
  }

  importPage( data ) {
    ModalManager.close();
    this.props.importPage(JSON.parse( data ) );
  }

  pasteSave(e){
    e.preventDefault();

    var copiedData = this.refs.copiedData.value;
    ModalManager.close();

    if( copiedData !='' ) {
      var options = {
        index: this.state.rowIndex,
        type: this.state.type,
        colIndex: this.state.colIndex,
        addonIndex: this.state.addonIndex,
        data: JSON.parse(copiedData)
      };
      this.props.pasteSave(options);
    }
  }

  render() {
    return(
      <Modal
        onRequestClose={this.props.onRequestClose}
        title="Paste Copied Data"
        customClass="sp-pagebuilder-modal-small"
        openTimeoutMS={0}
        closeTimeoutMS={0}>
        {
          <div>
            <div className="sp-pagebuilder-form-group" style={{
                marginTop: '10px'
              }}>
              <textarea ref="copiedData" name="copied_data" id="copied_data" className="sp-pagebuilder-form-control" placeholder="Paste your copied data here" />
            </div>

            <div className="sp-pagebuilder-modal-actions">
              <a
                href="#"
                className="sp-pagebuilder-btn sp-pagebuilder-btn-success"
                onClick={ this.pasteSave.bind(this) }><i className="fa fa-check-square-o"></i> {Joomla.JText._('COM_SPPAGEBUILDER_APPLY')}</a>
              <a
                href="#"
                className="sp-pagebuilder-btn sp-pagebuilder-btn-default"
                onClick={e=>{
                  e.preventDefault();
                  ModalManager.close()
                }}><i className="fa fa-times-circle"></i> {Joomla.JText._('COM_SPPAGEBUILDER_CANCEL')}
              </a>
            </div>
          </div>
        }
      </Modal>
    )
  }
}

export default PasteModal;
