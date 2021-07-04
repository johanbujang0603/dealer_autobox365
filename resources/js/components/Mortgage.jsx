import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Mortgage from './Mortgage/index';
if (document.getElementById('mortgage_content')) {
    ReactDOM.render(<Mortgage />, document.getElementById('mortgage_content'));
}
