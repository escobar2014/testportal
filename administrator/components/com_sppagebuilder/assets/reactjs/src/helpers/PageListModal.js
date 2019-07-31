import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LinkWithTooltip from './tooltip';
import { Modal, ModalManager } from './index'

class PageListModal extends Component {

  constructor(props) {
    super(props);
    this.state = {
      status: false,
      data: ''
    };
  }

  componentDidMount()
  {
    jQuery.ajax({
      type: 'POST',
      url: 'index.php?option=com_sppagebuilder&view=ajax&format=json&callback=pre-page-list',
      dataType: 'json',
      data: {},
      processData: false,
      contentType: false,
      success: function(response) {
        if ( response.status ) {
          this.setState({
            status: response.status,
            data: response.data
          });
        } else {
          this.setState({
            data: response.data
          });
        }
      }.bind(this)
    });
  }

  importPage( data ) {
    ModalManager.close();
    this.props.importPage(JSON.parse( data ) );
  }

  render() {
    return(
      <Modal
        className="sp-pagebuilder-modal-pages-list"
        onRequestClose={this.props.onRequestClose}
        openTimeoutMS={0}
        title={Joomla.JText._("COM_SPPAGEBUILDER_PAGE_TEMPLATES_LIST")}
        closeTimeoutMS={0}>
        {
          this.state.status
          ?
          <div className="clearfix">
            <ul className="sp-pagebuilder-page-templates">
              {this.state.data.map( ( page,index )=>
                <li key={index}>
                  <a href="#"
                    onClick={(e) =>{
                      e.preventDefault();
                      this.importPage(page.data);
                    }}
                    >
                    <div>
                      <img src={page.img} alt={page.name} />
                      <span className="sp-pagebuilder-btn sp-pagebuilder-btn-success"><i className="pbfont pbfont-plus"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PAGE_TEMPLATE_LOAD")}</span>
                    </div>
                    <span>{page.name}</span>
                  </a>
                </li>
              )}
            </ul>

            <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_MODAL_CLOSE")} position="top" id="sp-pagebuilder-close-modal">
              <a href="#" className="sp-pagebuilder-close-modal" onClick={ e => {
                  e.preventDefault();
                  ModalManager.close()
                }}>
                <i className="fa fa-times"></i>
              </a>
            </LinkWithTooltip>
          </div>
          :
          <div>
            <h3>{this.state.data}</h3>
          </div>
        }
      </Modal>
    )
  }
}

export default PageListModal;
