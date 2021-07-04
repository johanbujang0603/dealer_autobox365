import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import CarRecognition from './CarRecognition/index';
if (document.getElementById('car_recog')) {
    ReactDOM.render(<CarRecognition />, document.getElementById('car_recog'));
}
