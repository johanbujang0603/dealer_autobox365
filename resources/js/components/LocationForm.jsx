import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LocationForm from './LocationForm/index'
if (document.getElementById('location_form')) {
    ReactDOM.render(<LocationForm />, document.getElementById('location_form'));
}
