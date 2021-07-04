import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import AllLeads from './AllLeads/Index'
if (document.getElementById('all_leads')) {
    ReactDOM.render(<AllLeads />, document.getElementById('all_leads'));
}
