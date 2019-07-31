import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LinkWithTooltip from '../helpers/tooltip';
import { Dropdown, MenuItem, FormControl } from 'react-bootstrap';
import { connect } from 'react-redux';

class ColumnLayout extends Component {

  constructor(props) {
    super(props);
    this.state = {
      isOpen: false,
      visibility: false,
      active: props.current,
      layouts:[
        {
          id: 0,
          size: '12',
          icon: 'pbfont pbfont-12',
          className: 'layout-12'
        },
        {
          id: 1,
          size: '6,6',
          icon: 'pbfont pbfont-6-6',
          className: 'layout-66'
        },
        {
          id: 2,
          size: '4,4,4',
          icon: 'pbfont pbfont-4-4-4',
          className: 'layout-444'

        },
        {
          id: 3,
          size: '3,3,3,3',
          icon: 'pbfont pbfont-3-3-3-3',
          className: 'layout-3333'

        },
        {
          id: 4,
          size: '8,4',
          icon: 'pbfont pbfont-8-4',
          className: 'layout-84'
        },
        {
          id: 5,
          size: '3,9',
          icon: 'pbfont pbfont-3-9',
          className: 'layout-39'
        },
        {
          id: 6,
          size: '3,6,3',
          icon: 'pbfont pbfont-3-6-3',
          className: 'layout-363'
        },
        {
          id: 7,
          size: '2,10',
          icon: 'pbfont pbfont-2-10',
          className: 'layout-210'
        },
        {
          id: 8,
          size: '2,5,5',
          icon: 'pbfont pbfont-2-5-5',
          className: 'layout-255'
        },
        {
          id: 9,
          size: '6,6,12',
          icon: 'pbfont pbfont-6-6-12',
          className: 'layout-6612'
        }
      ]
    };
  }

  _generateLayout(current, size){
    if ( size !== '' ) {
      if (typeof this.props.innerRowIndex === 'undefined') {
        this.props.changeColumn( size, current, this.props.rowIndex );
      }else{
        this.props.changeInnerColumn( size, current, this.props.rowIndex, this.props.colIndex, this.props.innerRowIndex );
      }
    }

    this.setState({
      isOpen: false,
      visibility: false
    });
  }

  _generateCustomLayout(){

    var customLayout = this.refs.customLayout.value;

    if ( customLayout !== '' ) {

      var correctSize = customLayout.split('+')
      .filter(function(el){
        var regEx = /[A-Za-z]/;
        if( regEx.exec(el) == null && (el > 0 && el <= 12) ) {
          return el;
        }
      })
      .join(',');

      if (correctSize != '') {
        if (typeof this.props.innerRowIndex === 'undefined') {
          this.props.changeColumn( correctSize, this.props.current, this.props.rowIndex );
        }else{
          this.props.changeInnerColumn( correctSize, this.props.current, this.props.rowIndex, this.props.colIndex, this.props.innerRowIndex );
        }
      }
    }

    this.setState({
      isOpen: false,
      visibility: false
    });
  }

  onItemMouseEnter(size){
    this.setState({ active:size })
  }

  onItemMouseLeave(size){
    this.setState({ active:size })
  }

  _rednerDropDown(){
    const current = this.props.current;
    const { layouts } = this.state;

    return(
      layouts.map(
        (layout, index) =>
        <li key={layout.id}
          className={
            layout.size !== current ?
            layout.className
            : layout.className + ' ' + 'active'
          }
          onClick={ (e) => {
            e.preventDefault();
            this._generateLayout( current,layout.size );
          }}
          onMouseEnter={this.onItemMouseEnter.bind(this, layout.size)}
          onMouseLeave={this.onItemMouseLeave.bind(this,current)}
          >
          <a href="#">
            <i className={layout.icon} ></i>
          </a>

        </li>
      )
    )
  }

  _renderActiveItem(){
    const current = this.state.active;
    const { layouts } = this.state;

    let newLayouts = layouts.filter((layout,index) => {
      if (layout.size !== current) {
        return;
      }
      return layout;
    });

    if (newLayouts.length < 1) {
      var icon = 'pbfont pbfont-12',
      size = current;
    }else{
      var icon = newLayouts[0].icon,
      size = newLayouts[0].size;
    }

    return(
      <div className="sp-pagebuilder-layout-preview">
        <i className={ icon }></i>
        <strong>{ size }</strong>
      </div>
    )
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

  render(){
    const { isOpen } = this.state;

    return(
      <Dropdown
        open={this.state.isOpen}
        onClose={this.handleClose.bind(this)}
        onToggle={this.toggleLayout.bind(this)}
        id="dropdown-columns-manage"
        className="sp-pagebuilder-row-columns">

        <Dropdown.Toggle
          onClick={this.onclickHandle.bind(this)}
          >
          <LinkWithTooltip tooltip={Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_MANAGEMENT")} position="top" id="tooltip-manage-columns">
            <i className="pbfont pbfont-add-columns"></i>
          </LinkWithTooltip>
        </Dropdown.Toggle>

        <Dropdown.Menu className="sp-pagebuilder-dropdown-row-layouts">
          <div className="sp-pagebuilder-layouts-container" >
            <div className="sp-pagebuilder-layouts-container-inner">
              { this._renderActiveItem() }
              <ul className="sp-pagebuilder-layouts-list clearfix">
                { this._rednerDropDown() }
              </ul>
              <div className="sp-pagebuilder-custom-layout clearfix">
                <div>
                  <span>{Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_CUSTOM")}</span>
                </div>
                <div>
                  <input
                    type="text"
                    onSelect={this.keepOpen.bind(this)}
                    ref="customLayout"
                    defaultValue='4+4+4'
                    placeholder="4+4+4"
                    />
                </div>
                <div>
                  <a
                    href="#"
                    className="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-block"
                    onClick={(e) => {
                      e.preventDefault();
                      this._generateCustomLayout()
                    }}>
                    {Joomla.JText._("COM_SPPAGEBUILDER_ROW_COLUMNS_GENERATE")}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </Dropdown.Menu>
      </Dropdown>
    )
  }
}

export default ColumnLayout;
