import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import ReactDOM from 'react-dom';
import { Dropdown, MenuItem } from 'react-bootstrap';
import { ModalManager} from '../helpers/index';
import PageListModal from '../helpers/PageListModal';
import { importPage } from '../actions/index'


class PageToolDropdown extends Component {

  constructor(props) {
    super(props);
    this.state = {
      isOpen: false,
      visibility: false,
    };
  }

  toggleLayout () {
    const { isOpen } = this.state;
    const { visibility } = this.state;

    if (!visibility) {
      this.setState({ isOpen: !isOpen })
    }
  }

  handleClose(){
    this.setState({visibility: false});
  }

  keepOpen () {
    this.setState({visibility: true});
  }

  onclickHandle(){
    const { visibility } = this.state;
    this.setState({visibility: !visibility});
  }

  onClickHideDropdown(){
    this.setState({
      isOpen: false,
      visibility: false
    });
  }

  exportLayout(){

    const { present } = this.props.state.pageBuilder;

    if ( present.length == 0 ) {
      return;
    }

    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("id", "pageexport");
    form.setAttribute("action", "index.php?option=com_sppagebuilder&task=export");
    form.setAttribute("target", "_blank");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", 'hidden');
    hiddenField.setAttribute("name", 'template');
    hiddenField.setAttribute("value", JSON.stringify(present));
    form.appendChild(hiddenField);
    document.getElementsByTagName("body")[0].appendChild(form);
    form.submit();
    jQuery('#pageexport').remove();
  }

  render(){
    const { isOpen } = this.state;

    return(
      <Dropdown
        dropup
        open={this.state.isOpen}
        onClose={this.handleClose.bind(this)}
        onToggle={this.toggleLayout.bind(this)}
        id="sp-pagebuilder-tools-dropup">
        <Dropdown.Toggle
          onClick={this.onclickHandle.bind(this)}
          className="sp-pagebuilder-btn sp-pagebuilder-btn-link">
          <span><i className="pbfont pbfont-gear"></i> Tools</span>
        </Dropdown.Toggle>
        <Dropdown.Menu className="sp-pagebuilder-tools-dropup">

          <li>
            <a href="#" id="btn-page-options">
              <span><i className="pbfont pbfont-settings"></i> Page Options</span>
            </a>
          </li>

          <li>
            <a
              href="#"
              onClick={(e)=>{
                e.preventDefault();
                document.getElementById('upload-file').click();
                this.onClickHideDropdown();
              }}
              >
              <span><i className="pbfont pbfont-import"></i> {Joomla.JText._("COM_SPPAGEBUILDER_IMPORT_PAGE")}</span>
            </a>
          </li>

          <li>
            <a
              href="#"
              onClick={(e) => {
                e.preventDefault();
                this.onClickHideDropdown();
                this.exportLayout();
              }}>
              <span><i className="pbfont pbfont-export"></i> {Joomla.JText._("COM_SPPAGEBUILDER_EXPORT_PAGE")}</span>
            </a>
          </li>

          <li>
            <a
              href="#"
              onClick={ (e) => {
                e.preventDefault();
                this.onClickHideDropdown();
                ModalManager.open(
                  <PageListModal
                    importPage={this.props.importPage}
                    onRequestClose={() => true } />
                )
              }}>
              <span><i className="pbfont pbfont-templates"></i> {Joomla.JText._("COM_SPPAGEBUILDER_PAGE_TEMPLATES")}</span>
            </a>
          </li>
        </Dropdown.Menu>
      </Dropdown>
    )
  }
}

const mapStateToProps = ( state ) => {
  return {state};
}

const mapDispatchToProps = ( dispatch ) => {
  return {
    importPage: ( page ) => {
      dispatch(importPage(page))
    }
  }
}

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(PageToolDropdown);
