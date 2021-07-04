import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import InvoiceForm from './InvoiceForm/index'
if (document.getElementById('invoice_form')) {
    ReactDOM.render(<InvoiceForm />, document.getElementById('invoice_form'));
}
