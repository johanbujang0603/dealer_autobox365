import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LeadConvertForm from './LeadConvertForm/Index'
if (document.getElementById('lead_convert_modal')) {
    ReactDOM.render(<LeadConvertForm />, document.getElementById('lead_convert_modal'));
}
