import React,{ Component, PropTypes } from 'react';
import LinkWithTooltip from '../helpers/tooltip';

class JoomlaActions extends Component {

  render(){

    return (
      <div>
        <a href="#" id="btn-save-page" className="sp-pagebuilder-btn sp-pagebuilder-btn-success"><i className="fa fa-save"></i> Save</a>
        <a href="#" className="sp-pagebuilder-btn sp-pagebuilder-btn-default" onclick="Joomla.submitbutton('page.cancel')"><i className="fa fa-times"></i> Cancel</a>
      </div>
    )
  }
}

export default JoomlaActions;
