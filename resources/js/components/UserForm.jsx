import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import UserForm from './UserForm/Index'
// document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('user_form')) {
        console.log('Form Rendering....')
        ReactDOM.render(<UserForm />, document.getElementById('user_form'));
    }
// })
