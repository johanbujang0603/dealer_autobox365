import React from 'react'
import Lang from "../../Lang/Lang";
import Axios from 'axios'
import ReactDataSheet from 'react-datasheet';
// Be sure to include styles at some point, probably during your bootstrapping
import 'react-datasheet/lib/react-datasheet.css';
import Select from 'react-select';
import Spreadsheet from "react-spreadsheet";
import CheckBox from '../../global_ui_components/CheckBox';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import Pusher from 'pusher-js';
import openSocket from 'socket.io-client';
import countryList from 'react-select-country-list'


const metas = document.getElementsByTagName('meta');
const socket = openSocket(document.currentScript.getAttribute('socket_url'));
console.log(document.currentScript.getAttribute('socket_url'))
const pusher = new Pusher('7d91bf2ba08f5efb3b5e', {
    cluster: 'mt1',
    encrypted: true,
    disableStats: true
});
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
export default class InventoryImportForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            file: null,
            file_path: null,
            isImporting: $("#lead_import_form").attr('data-status') == 'running' ? true : false,
            progress: $("#lead_import_form").attr('data-progress'),
            progress_logs: [],
            preview_rows: [],
            exclude_first_row_option: true,
            unique_column: null,
            name_column: null,
            civility_column: null,
            gender_column: null,
            date_of_birth_column: null,
            phone_column: null,
            email_column: null,
            address_column: null,
            country_column: null,
            city_column: null,
            post_code_column: null,
            facebook_column: null,
            photo_column: null,
            country_default: null,
            countries: countryList().getData(),
        }
        this.handleUpload = this.handleUpload.bind(this)
        this.handleChooseFile = this.handleChooseFile.bind(this)
        this.handleStartImport = this.handleStartImport.bind(this)
        this.validate = this.validate.bind(this)
    }
    componentDidMount() {
        console.log()

        let self = this
        console.log('channel_' + metas['LogedUserId'].content + '_lead_import')
        socket.on('channel_' + metas['LogedUserId'].content + '_lead_import', function (data) {
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

        return isValid
    }
    handleStartImport() {
        if (this.validate()) {
            let post_data = this.state
            Axios.post('/leads/import/start', post_data)
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
        if (e.target.files[0]) {
            var block = $('.page-content');
            $(block).block({
                message: '<i className="icon-spinner4 spinner"></i>',
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
            Axios.post('/leads/import/upload', formData)
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
                1 && <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().leads.choose_import_file}:</label>
                        <input type="file" name="file" className="form-control" onChange={this.handleChooseFile} />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-6">
                        <label>{Lang().leads.preview_uploaded_file}.</label>
                        {
                            this.state.preview_rows.length > 0 ? <Spreadsheet
                                data={this.state.preview_rows}
                            />
                                : <Spreadsheet
                                    data={emptySpreadSheet} />
                        }
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-6">
                        <label>{Lang().leads.column_match}</label>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.unique_filed}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ unique_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.name}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ name_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.civility}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ civility_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().user.gender}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ gender_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.date_of_birth}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ date_of_birth_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().user.phone_number}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ phone_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.email}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ email_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.address}:</label>
                            <div className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ address_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">{Lang().leads.country}:</label>
                            <div className="col-span-12 sm:col-span-5">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ country_column: selectedOption.value }) }}
                                />
                            </div>
                            <div className="col-span-12 sm:col-span-4">
                                <Select
                                    placeholder='Choose default country'
                                    options={this.state.countries.map((country, index) => {
                                        return { value: country.value, label: `${country.label}(${country.value})` }
                                    })}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ country_default: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3">{Lang().leads.city}:</label>
                            <div className="col-span-12 sm:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ city_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3">{Lang().leads.postcode}:</label>
                            <div className="col-span-12 sm:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ post_code_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3">{Lang().leads.facebook_url}:</label>
                            <div className="col-span-12 sm:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ facebook_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <label className="col-span-12 sm:col-span-3">{Lang().user.photo}:</label>
                            <div className="col-span-12 sm:col-span-9">
                                <Select
                                    placeholder='Choose column'
                                    options={celloptions}
                                    isDisabled={this.state.preview_rows.length == 0}
                                    onChange={(selectedOption) => { this.setState({ photo_column: selectedOption.value }) }}
                                />
                            </div>
                        </div>

                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <input type="checkbox" 
                                className="col-span-12 sm:col-span-1 md:col-span-1 xxl:col-span-1 input border mr-2"
                                checked={this.state.exclude_first_row_option}
                                onChange={() => { this.setState({ exclude_first_row_option: !this.state.exclude_first_row_option }) }} />
                            <label className="col-span-12 sm:col-span-9 md:col-span-9 xxl:col-span-9 cursor-pointer select-none">Exclude Header(First) Row</label>
                        </div>
                        <div className="p-5">
                                
                            <button disabled={this.state.preview_rows.length == 0} 
                                    className="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"
                                    onClick={this.handleStartImport}>
                                {Lang().leads.import}
                            </button>
                        </div>
                    </div>

                </div>
            }

        </div>
    }
}
