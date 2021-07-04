import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import CustomersForm from './CustomersForm/index'
if (document.getElementById('customers_form')) {
    ReactDOM.render(<CustomersForm />, document.getElementById('customers_form'));
}
