import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LeadImportForm from './LeadImportForm/index'
if (document.getElementById('lead_import_form')) {
    ReactDOM.render(<LeadImportForm />, document.getElementById('lead_import_form'));
}
