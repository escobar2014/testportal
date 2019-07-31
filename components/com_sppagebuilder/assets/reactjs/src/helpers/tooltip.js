import React,{ Component, PropTypes } from 'react';
import { Tooltip, OverlayTrigger } from 'react-bootstrap';

const LinkWithTooltip = React.createClass({
  
  render() {
    let tooltip = <Tooltip id={this.props.id}>{this.props.tooltip}</Tooltip>;

      var position = 'top';

      if(this.props.position) {
        position = this.props.position;
      }

      return (
        <OverlayTrigger
          overlay={tooltip} placement={position}
          delayShow={100} delayHide={150}
          >
          {this.props.children}
        </OverlayTrigger>
      );
    }
  }
);

export default LinkWithTooltip;
