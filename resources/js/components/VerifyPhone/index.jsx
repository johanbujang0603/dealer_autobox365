
import React, { Component } from 'react';
import Lang from "../../Lang/Lang";
import ReactPhoneInput from 'react-phone-input-2';
import 'react-phone-input-2/dist/style.css';
import Axios from 'axios';
import config from '../../config';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css


export default class VerifyPhone extends Component {
    constructor(props) {
        super(props)
        this.state = {
            phone_number: '',
            valid: '',
            number: '',
            local_format: '',
            international_format: '',
            country_prefix: '',
            country_code: '',
            country_name: '',
            location: '',
            carrier: '',
            line_type: ''
        }
    }
    componentDidMount() {
    }
    
    updatePhoneNumber (value) {
        this.setState({
            phone_number: value
        })

    }
    verifyPhoneNumber(e) {
        let phone_num = e.target.value;
        Axios.get(`https://apilayer.net/api/validate?access_key=${config.numverify_access_key}&number=${phone_num}`)
        .then(response => {
            this.setState({
                phone_number: phone_num,
                valid: response.data.valid,
                number: response.data.number,
                local_format: response.data.local_format,
                international_format: response.data.international_format,
                country_prefix: response.data.country_prefix,
                country_code: response.data.country_code,
                country_name: response.data.country_name,
                location: response.data.location,
                carrier: response.data.carrier,
                line_type: response.data.line_type,
            })
        })
    }
    render() {
        return (
            <>
                <div className="grid grid-cols-12 gap-6 mt-5">
                    <div className="col-span-4 lg:col-span-4">
                        <div className="form-group form-group-feedback form-group-feedback-right mb-5">
                            <ReactPhoneInput
                                inputClass={'input w-full pt-3 pb-3 w-100 '}
                                containerClass='react-tel-input'
                                placeholder='Enter Phone Number' 
                                defaultCountry={this.state.countryCode}
                                value={this.state.phone_number}
                                onChange={(value) => {
                                    this.updatePhoneNumber(value)
                                }}
                                onBlur={(event) => {
                                    this.verifyPhoneNumber(event)
                                }}
                            />
                        </div>
                    </div>
                    <div className="col-span-6 lg:col-span-6">

                        <table id="phone-verification-info" className="table">
                            <tbody>
                                <tr>
                                    <td className="border-b">{this.state.valid}</td>
                                    <td className="border-b">{this.state.valid == true ? <i className="icon-check" style={{color: 'green'}}></i> : <i style={{color: 'red'}} className="icon-times"></i>}</td>
                                </tr>
                                
                                <tr>
                                    <td className="border-b">{this.state.local_format}</td>
                                    <td className="border-b" >{this.state.local_format}</td>
                                </tr>
                                
                                <tr>
                                    <td className="border-b">{this.state.intl_format}</td>
                                    <td className="border-b" >{this.state.international_format}</td>
                                </tr>
                                
                                <tr>
                                    <td className="border-b">{this.state.country}</td>
                                    <td className="border-b" >{this.state.country_name}</td>
                                </tr>
                                
                                <tr>
                                    <td className="border-b">{Lang().verify_email.location}</td>
                                    <td className="border-b" >{this.state.location}</td>
                                </tr>
                                
                                <tr>
                                    <td className="border-b">{Lang().verify_email.carrier}</td>
                                    <td className="border-b" >{this.state.carrier}</td>
                                </tr>
                                                
                                <tr>
                                    <td className="border-b">{Lang().verify_email.line_type}</td>
                                    <td className="border-b" >{this.state.line_type}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </>
        );
    }
}
