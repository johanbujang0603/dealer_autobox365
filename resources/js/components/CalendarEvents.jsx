import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import CalendarEvents from './CalendarEvents/index'
if (document.getElementById('calendar_container')) {
    ReactDOM.render(<CalendarEvents />, document.getElementById('calendar_container'));
}
