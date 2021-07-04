import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Valuation from './Valuation/Index';

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('valuation_content')) {
        ReactDOM.render(<Valuation />, document.getElementById('valuation_content'));
    }
});

