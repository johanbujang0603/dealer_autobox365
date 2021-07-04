import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import AddInterestForm from './AddInterest/Index';
if (document.getElementById('add_interests_form')) {
    ReactDOM.render(<AddInterestForm />, document.getElementById('add_interests_form'));
}
