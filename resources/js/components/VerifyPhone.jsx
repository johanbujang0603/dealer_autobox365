import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import VerifyPhone from './VerifyPhone/index';
if (document.getElementById('verify_phone')) {
    ReactDOM.render(<VerifyPhone />, document.getElementById('verify_phone'));
}
