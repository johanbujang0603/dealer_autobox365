import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import CreateEmailCampaign from './CreateEmailCampaign/index'
if (document.getElementById('create_email_campaign')) {
    ReactDOM.render(<CreateEmailCampaign />, document.getElementById('create_email_campaign'));
}
