import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import MoneyConversion from './MoneyConversion/index';
if (document.getElementById('money_conversion')) {
    ReactDOM.render(<MoneyConversion />, document.getElementById('money_conversion'));
}
