import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Axios from 'axios'
import ReactDataSheet from 'react-datasheet';
import 'react-datasheet/lib/react-datasheet.css';
import Select from 'react-select';
import Spreadsheet from "react-spreadsheet";
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import Pusher from 'pusher-js';
import openSocket from 'socket.io-client';
import CheckBox from '../../global_ui_components/CheckBox';
function fillArray(value, len) {
    var arr = [];
    for (var i = 0; i < len; i++) {
        arr.push(value);
    }
    return arr;
}
const emptyRowSpreadSheet = [
    { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }, { value: '' }
]
const emptySpreadSheet = fillArray(emptyRowSpreadSheet, 30)
const socket = openSocket(document.currentScript.getAttribute('socket_url'));
export default class ValuationForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            file: null,
            file_path: null,
            isImporting: false,
            progress: null,
            progress_logs: [],
            preview_rows: [],
            exclude_first_row_option: true,
            unique_column: null,
            name_column: null,
            make_column: null,
            model_column: null,
            year_column: null,
            country_column: null,
            city_column: null,
            price_column: null,
            currency_column: null,
            color_column: null,
            transmission_column: null,
            mileage_column: null,
            fuel_column: null,
            photo_column: null,
        }
        this.handleUpload = this.handleUpload.bind(this)
        this.handleChooseFile = this.handleChooseFile.bind(this)
        this.handleStartImport = this.handleStartImport.bind(this)
        this.validate = this.validate.bind(this)
    }
    componentDidMount() {
        console.log()

        let self = this
        console.log('channel_valuation_data_import')
        socket.on('channel_valuation_data_import', function (data) {
            console.log(data)
            let { progress_logs } = self.state
            progress_logs.push({ time: new Date(), message: data.log })
            self.setState({
                isImporting: true,
                progress: data.progress,
                progress_logs: progress_logs
            }, () => {
                if (data.progress == 100) {
                    confirmAlert({
                        title: 'Great!',
                        message: "You imported listings successfully! Do you want to import new buik file?",
                        buttons: [
                            {
                                label: 'Yes',
                                onClick: () => {
                                    self.setState({
                                        isImporting: false
                                    })
                                }
                            },
                            {
                                label: 'No, Thanks',
                                onClick: () => null
                            }
                        ]
                    })
                }
            })
            //data.actionId and data.actionData hold the data that was broadcast
            //process the data, add needed functionality here
        });
    }
    validate() {
        let isValid = true
        if (this.state.preview_rows.length == 0) {
            isValid = false
        }
        if (this.state.unique_column == null) {
            isValid = false
        }
        if (this.state.name_column == null) {
            isValid = false
        }
        if (this.state.make_column == null) {
            isValid = false
        }
        if (this.state.model_column == null) {
            isValid = false
        }
        if (this.state.year_column == null) {
            isValid = false
        }
        if (this.state.country_column == null) {
            isValid = false
        }
        if (this.state.city_column == null) {
            isValid = false
        }
        if (this.state.price_column == null) {
            isValid = false
        }
        if (this.state.currency_column == null) {
            isValid = false
        }
        if (this.state.color_column == null) {
            isValid = false
        }
        if (this.state.transmission_column == null) {
            isValid = false
        }
        if (this.state.mileage_column == null) {
            isValid = false
        }
        if (this.state.fuel_column == null) {
            isValid = false
        }
        if (this.state.photo_column == null) {
            isValid = false
        }
        return isValid
    }
    handleStartImport() {
        if (this.validate()) {
            let post_data = this.state
            Axios.post('/admin/inventories/valuation/start_import', post_data)
                .then(response => {
                    this.setState({
                        isImporting: true
                    })
                })
        }
        else {
            confirmAlert({
                title: "Sorry!",
                message: "Please confirm you uploaded source file and choosed columns correctly.",
                buttons: [
                    {
                        label: 'Okay, I understand',
                        onClick: null
                    }
                ]
            });
        }
    }
    handleChooseFile(e) {
        console.log(e.target.files[0])
        if (e.target.files[0]) {
            var block = $('.page-content');
            $(block).block({
                message: '<i class="icon-spinner4 spinner"></i>',
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8,
                    cursor: 'wait'
                },
                css: {
                    width: 16,
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
            let formData = new FormData()
            formData.append('file', e.target.files[0])
            Axios.post('/admin/inventories/valuation/upload', formData)
                .then(Response => {
                    this.setState({ preview_rows: Response.data.preview_rows, file_path: Response.data.file_path }, () => {
                        $(block).unblock();
                    })
                }).catch(e => {
                    $(block).unblock();
                })
        }
    }
    handleUpload() {
        if (this.state.file) {

        }
    }
    toExcelHeader(index) {
        if (index <= 0) {
            throw new Error("index must be 1 or greater");
        }
        // index--;
        var charCodeOfA = ("a").charCodeAt(0); // you could hard code to 97
        var charCodeOfZ = ("z").charCodeAt(0); // you could hard code to 122
        var excelStr = "";
        var base24Str = (index).toString(charCodeOfZ - charCodeOfA + 1);
        for (var base24StrIndex = 0; base24StrIndex < base24Str.length; base24StrIndex++) {
            var base24Char = base24Str[base24StrIndex];
            var alphabetIndex = (base24Char * 1 == base24Char) ? base24Char : (base24Char.charCodeAt(0) - charCodeOfA + 10);
            // bizarre thing, A==1 in first digit, A==0 in other digits
            if (base24StrIndex == 0) {
                alphabetIndex -= 1;
            }
            excelStr += String.fromCharCode(charCodeOfA * 1 + alphabetIndex * 1);
        }
        return excelStr.toUpperCase();
    }
    render() {
        let celloptions = this.state.preview_rows.length > 0 ? this.state.preview_rows[0].map((cell, index) => { return { label: `${this.toExcelHeader(index + 1)} (${cell.value})`, value: index } }) : null
        return <div>
            {
                this.state.isImporting == true && <div>
                    <div className="pace-demo w-100 h-auto p-3 pb-4" style={{ paddingBottom: '30px' }}>
                        {
                            this.state.progress < 100 && <div className="theme_xbox theme_xbox_with_text" style={{ position: 'unset', marginTop: '0' }}>
                                <div className="pace_activity" />
                            </div>
                        }
                        <div className="theme_bar_lg w-100"><div className="pace_progress"
                            data-progress-text={this.state.progress + '%'}
                            data-progress={this.state.progress}
                            style={{ width: `${this.state.progress}%`, maxWidth: '100%' }}>{this.state.progress}%</div></div>
                    </div>
                    <pre className="language-javascript" style={{ height: 300 }}><code><br />
                        {
                            this.state.progress_logs.map((log, index) => {
                                return '[' + log.time + '] ' + log.message + '\n'
                            })
                        }
                    </code></pre>
                </div>
            }
            {
                1 && <div>
                    <div className="form-group">
                        <label>Choose import file:</label>
                        <input type="file" name="file" className="form-control" onChange={this.handleChooseFile} />
                    </div>
                    <div className="form-group row">
                        <div className="col-md-6">
                            <label>Preview of your uploaded file.</label>
                            {
                                this.state.preview_rows.length > 0 ? <Spreadsheet
                                    data={this.state.preview_rows}
                                />
                                    : <Spreadsheet
                                        data={emptySpreadSheet} />
                            }
                        </div>
                        <div className="col-md-6">
                            <label>Column Match</label>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Unique Field:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ unique_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Name:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ name_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Make:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ make_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Model:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ model_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Year:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ year_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Country:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ country_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">City:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ city_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Price:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ price_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Currency:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ currency_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Color:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ color_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Transmission:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ transmission_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Mileage:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ mileage_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Fuel:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ fuel_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group row">
                                <label className="col-lg-3 col-form-label">Photo:</label>
                                <div className="col-lg-9">
                                    <Select
                                        placeholder='Choose column'
                                        options={celloptions}
                                        isDisabled={this.state.preview_rows.length == 0}
                                        onChange={(selectedOption) => { this.setState({ photo_column: selectedOption.value }) }}
                                    />
                                </div>
                            </div>
                            <div className="form-group">

                                <CheckBox
                                    checkbox_type='inline'
                                    label={"Exclude Header(First) Row"}
                                    checked={this.state.exclude_first_row_option}
                                    onChange={() => { this.setState({ exclude_first_row_option: !this.state.exclude_first_row_option }) }}
                                />
                            </div>
                            <div className="form-group">
                                <button className="btn btn-primary" disabled={this.state.preview_rows.length == 0} onClick={this.handleStartImport}>Import</button>
                            </div>
                        </div>
                    </div>


                </div>
            }

        </div>

    }
}
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('admin_valuation_upload_element')) {
        ReactDOM.render(<ValuationForm />, document.getElementById('admin_valuation_upload_element'));
    }
});

