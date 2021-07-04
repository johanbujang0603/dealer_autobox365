import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import CreateSmsCampaign from './CreateSmsCampaign/index'
if (document.getElementById('create_sms_campaign')) {
    ReactDOM.render(<CreateSmsCampaign />, document.getElementById('create_sms_campaign'));
}
