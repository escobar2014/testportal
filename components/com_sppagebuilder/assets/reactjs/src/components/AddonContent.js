import React, { Component, PropTypes } from 'react';
import deepEqual from 'deep-equal'

class AddonContent extends Component {

  constructor(props) {
    super(props);
    this.state = {
      settings: this.props.addon.settings
    };
  }
  
  addonAssets() {
    const { addon } = this.props;
    // assets
    if(addon.assets != undefined) {
      // CSS
      if(addon.assets.css != undefined) {
        var css = document.createElement('style');
        css.setAttribute( 'type', 'text/css' );
        css.id = 'addon-style-' + addon.id;

        if(document.getElementById(css.id) != undefined) {
          document.getElementById(css.id).remove();
        }

        if (css.styleSheet) {
          css.styleSheet.cssText = addon.assets.css;
        } else {
          css.appendChild(document.createTextNode(addon.assets.css));
        }

        document.getElementsByTagName('head')[0].appendChild( css );
      }

      // JS
      if(addon.assets.js != undefined) {
        var script = document.createElement('script');
        script.setAttribute( 'type', 'text/javascript' );
        script.id = 'addon-script-' + addon.id;
        script.setAttribute( 'async', true );
        script.text = addon.assets.js;
        document.getElementsByTagName('head')[0].appendChild( script );
      }
    }

    if(addon.name == 'gmap') {
      initSPPageBuilderGMap();
    }
  }

  componentDidMount(){
    const { addon } = this.props;
    this.addonAssets();
  }

  shouldComponentUpdate( nextProps, nextState ) {
    if( !deepEqual( nextProps.addon.settings, nextState.settings ) ) {
      return true;
    }

    return false;
  }

  componentDidUpdate(){
    const { settings } = this.state;
    const { addon } = this.props;

    if( !deepEqual( this.props.addon.settings, settings ) ) {
      this.setState({
        settings: this.props.addon.settings,
      });
    }

    this.addonAssets();

  }

  render() {
    const { addon } = this.props;
    return (
      <div key="1">
        <div dangerouslySetInnerHTML={ { __html: addon.htmlContent } } />
      </div>
    )
  }
}

export default AddonContent;
