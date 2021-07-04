import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import TransactionForm from './TransactionForm/CustomerTransaction'
if (document.getElementById('customer_transaction_form')) {
    ReactDOM.render(<TransactionForm />, document.getElementById('customer_transaction_form'));
}
