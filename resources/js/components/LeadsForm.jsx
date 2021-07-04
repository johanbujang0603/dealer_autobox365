import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LeadsForm from './LeadsForm/index'
if (document.getElementById('leads_form')) {
    ReactDOM.render(<LeadsForm />, document.getElementById('leads_form'));
}
