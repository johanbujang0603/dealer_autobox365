import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import TransactionForm from './TransactionForm/Index'
if (document.getElementById('transaction_form')) {
    ReactDOM.render(<TransactionForm />, document.getElementById('transaction_form'));
}
