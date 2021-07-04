import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import AllInventories from './AllInventories/Index'
if (document.getElementById('all_inventories')) {
    ReactDOM.render(<AllInventories />, document.getElementById('all_inventories'));
}
