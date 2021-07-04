import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import InventoryImportForm from './InventoryImportForm/index'
if (document.getElementById('inventory_import_form')) {
    ReactDOM.render(<InventoryImportForm />, document.getElementById('inventory_import_form'));
}
