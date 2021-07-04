import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import DistanceConversion from './DistanceConversion/index';
if (document.getElementById('distance_conversion')) {
    ReactDOM.render(<DistanceConversion />, document.getElementById('distance_conversion'));
}
