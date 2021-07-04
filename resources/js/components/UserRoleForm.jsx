import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import UserRoleForm from './UserRoleForm/Index'
// document.addEventListener('DOMContentLoaded', function () {
if (document.getElementById('user_role_form')) {
    console.log('Form Rendering....')
    ReactDOM.render(<UserRoleForm />, document.getElementById('user_role_form'));
}
// })
