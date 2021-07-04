import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import InventoryForm from './InventoryForm/index'
if (document.getElementById('inventory_form')) {
    ReactDOM.render(<InventoryForm />, document.getElementById('inventory_form'));
}
