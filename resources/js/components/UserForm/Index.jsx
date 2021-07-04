import React from 'react'
import Lang from "../../Lang/Lang";
import RadioBox from "../../global_ui_components/RadioBox";
import Dropify from "../../global_ui_components/Dropify";
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'
import Select from 'react-select';
import config from '../../config';
import Axios from 'axios'
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import { formatPhoneNumberIntl } from 'react-phone-number-input'
const JSON_DATA = JSON.parse(document.getElementById('user_form_details').innerHTML)
export default class UserForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            first_name: null,
            last_name: null,
            civility: null,
            gender: null,
            profile_image_src: null,
            profile_image: null,
            phone_numbers: [],
            email: null,
            email_verify: {},
            id: null,
            page_mode: 'create',

            roles: JSON_DATA['roles'],
            locations: JSON_DATA['locations'],
        }
        this.handleChange = this.handleChange.bind(this)
        this.hanldeAddProfileImage = this.hanldeAddProfileImage.bind(this)
        this.handleRemoveProfileImage = this.handleRemoveProfileImage.bind(this)
        this.updatePhoneNumber = this.updatePhoneNumber.bind(this)
        this.handleAddNewPhone = this.handleAddNewPhone.bind(this)
        this.handleRemovePhone = this.handleRemovePhone.bind(this)
        this.verifyPhoneNumber = this.verifyPhoneNumber.bind(this)
        this.verifyEmail = this.verifyEmail.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getLoadData = this.getLoadData.bind(this)
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit' || urlParams.mode == 'self_edit') {
            this.setState({
                page_mode:urlParams.mode 
            }, ()=>{
                this.getLoadData(urlParams.userId)
            })
            
        }
    }
    getLoadData(userId) {
        this.setState({
            is_loading: true
        }, () => {
            Axios.get(`/users/ajax_load/${userId}`)
                .then(response => {
                    let phone_numbers = response.data.phone_numbers

                    this.setState({
                        ...this.state,
                        ...response.data,
                        is_loading: false
                    }, () => {
                    })
                })
        })
    }
    getParams(url) {
        var params = {};
        var parser = document.createElement('a');
        parser.href = url;
        var query = parser.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            params[pair[0]] = decodeURIComponent(pair[1]);
        }
        return params;
    }
    handleChange(e) {
        this.setState({
            [e.target.name]: e.target.value
        })
    }
    handleRemoveProfileImage() {
        this.setState({ profile_image: null, profile_image_src: null })
    }
    async hanldeAddProfileImage(file) {
        const photoPreview = await this.readUploadedPhotoPreview(file);
        this.setState({ profile_image: file, profile_image_src: photoPreview })
    }
    readUploadedPhotoPreview(imageFile) {
        const imageFileReader = new FileReader();
        return new Promise((resolve, reject) => {
            imageFileReader.onerror = () => {
                imageFileReader.abort();
                reject(new DOMException("Problem parsing input file."));
            };
            imageFileReader.onload = () => {
                resolve(imageFileReader.result)
            };
            imageFileReader.readAsDataURL(imageFile);
        });
    }
    handleAddNewPhone() {
        let { phone_numbers } = this.state
        phone_numbers.push({
            valid: false,
            number: "",
            mobile_no: "",
            local_format: "",
            international_format: "",
            country_prefix: "",
            country_code: "",
            country_name: "",
            location: "",
            carrier: "",
            intl_formmated_number: "",
            line_type: ""
        })
        this.setState({
            phone_numbers: phone_numbers
        })
    }
    handleRemovePhone(index) {
        let { phone_numbers } = this.state
        phone_numbers.splice(index, 1)
        this.setState({
            phone_numbers: phone_numbers
        })
    }
    verifyPhoneNumber(event, index) {
        let phone_number = event.target.value
        let phone_numbers = this.state.phone_numbers
        phone_numbers[index].loading = true
        this.setState({
            phone_numbers: phone_numbers
        }, () => {
            Axios.get(`https://apilayer.net/api/validate?access_key=${config.numverify_access_key}&number=${phone_number}`)
                .then(response => {
                    phone_numbers[index] = { ...phone_numbers[index], ...response.data, loading: false }
                    this.setState({
                        phone_numbers: phone_numbers
                    })
                })
        })
    }
    updatePhoneNumber(value, index) {
        let { phone_numbers } = this.state
        phone_numbers[index].mobile_no = value
        phone_numbers[index].intl_formmated_number = formatPhoneNumberIntl(value)
        this.setState({
            phone_numbers: phone_numbers
        })
    }
    verifyEmail(event) {
        let email_verify = this.state.email_verify
        email_verify.loading = true
        let email = event.target.value
        let self = this
        this.setState({
            email_verify: email_verify
        }, () => {
            Axios.get(`https://apps.emaillistverify.com/api/verifyEmail?secret=${config.email_verify_api_key}&email=${email}`)
                .then(response => {
                    if (response.data == 'ok' || response.data == 'ok_for_all') {
                        email_verify.valid = true
                        email_verify.loading = false
                        self.setState({
                            email_verify: email_verify,
                        })
                    }
                    else {
                        email_verify.valid = false
                        email_verify.loading = false
                        if (response.data == 'error') {
                            email_verify.error_message = "The server is saying that delivery failed, but no information about,the email exists"
                        }
                        else if (response.data == 'smtp_error') {
                            email_verify.error_message = "The SMTP answer from the server is invalid or the destination server, reported an internal error to us"
                        }
                        else if (response.data == 'smtp_protocol') {
                            email_verify.error_message = "The destination server allowed us to connect but the SMTP, session was closed before the email was verified"
                        }
                        else if (response.data == 'unknown_email') {
                            email_verify.error_message = "The server said that the delivery failed and that the email address does, not exist"
                        }
                        else if (response.data == 'attempt_rejected') {
                            email_verify.error_message = "The delivery failed; the reason is similar to “rejected”"
                        }
                        else if (response.data == 'relay_error') {
                            email_verify.error_message = "The delivery failed because a relaying problem took place"
                        }
                        else if (response.data == 'antispam_system') {
                            email_verify.error_message = "Some anti - spam technology is blocking the, verification progress"
                        }
                        else if (response.data == 'email_disabled') {
                            email_verify.error_message = "The email account is suspended, disabled, or limited and can not, receive emails"
                        }
                        else if (response.data == 'domain_error') {
                            email_verify.error_message = "The email server for the whole domain is not installed or is, incorrect, so no emails are deliverable"
                        }

                        else if (response.data == 'dead_server') {
                            email_verify.error_message = "The email server is dead, and no connection to it could be established"
                        }
                        else if (response.data == 'syntax_error') {
                            email_verify.error_message = "There is a syntax error in the email address"
                        }
                        else if (response.data == 'unknown') {
                            email_verify.error_message = "The email delivery failed, but no reason was given"
                        }
                        else if (response.data == 'accept_all') {
                            email_verify.error_message = "The server is set to accept all emails at a specific domain., These domains accept any email you send to them"
                        }
                        else if (response.data == 'disposable') {
                            email_verify.error_message = "The email is a temporary address to receive letters and expires, after certain time period"
                        }
                        else if (response.data == 'spam_traps' || response.data == 'spamtrap') {
                            email_verify.error_message = "The email address is maintained by an ISP or a third party, which neither clicks nor opens emails"
                        }
                        else {
                            email_verify.error_message = "Invalid Email"
                        }
                        self.setState({
                            email_verify: email_verify,
                        })
                    }
                })
        })

    }
    handleSubmit(e) {
        e.preventDefault()
        let post_data = {
            id: this.state.id,
            first_name: this.state.first_name,
            civility: this.state.civility,
            last_name: this.state.last_name,
            gender: this.state.gender,
            phone_numbers: this.state.phone_numbers,
            email: this.state.email,
            name: this.state.name,
            password: this.state.password,
            role: this.state.role,
            location: this.state.selected_location,
        }
        let formData = new FormData();
        if (this.state.profile_image != null) {
            formData.append('profile_image', this.state.profile_image)
        }
        formData.append('post_data', JSON.stringify(post_data))
        Axios.post('/users/save', formData)
            .then(response => {
                if (response.data.status == 'success') {
                    if(this.state.page_mode != 'self_edit'){
                        confirmAlert({
                            title: response.data.option == 'created' ? "You created a user successfully!" : "You updated a user successfully!",
                            message: response.data.option == 'created' ? 'Do you want to create new user continue?' : "Do you want to stay in this page?",
                            buttons: [
                                {
                                    label: 'Yes',
                                    onClick: () => response.data.option == 'created' ? location.href = "/users/create" : location.href = `/users/edit/${response.data.user_id}`
                                },
                                {
                                    label: 'No, Thanks',
                                    onClick: () => location.href = "/users"
                                }
                            ]
                        });
                    }
                    else{
                         confirmAlert({
                             title: 'Success',
                             message: "You saved profile successfully",
                             buttons: [
                                 {
                                     label: 'Okay',
                                     onCLick: ()=> null
                                 }
                             ]
                         })
                    }
                    
                }
            })
    }
    render() {
        return <form
            onSubmit={this.handleSubmit}
            className='p-3'
        >
            <div className={'grid grid-cols-12 gap-6 mt-5'}>
                <div className={'intro-y col-span-12 lg:col-span-6 sm: col-span-6'}>
                    <label className="font-medium">{Lang().user.first_name}</label>
                    <input className='input border w-full' name='first_name' placeholder={Lang().user.enter_first_name} onChange={this.handleChange} value={this.state.first_name} />
                </div>
                <div className={'intro-y col-span-12 lg:col-span-6 sm: col-span-6'}>
                    <label className="font-medium">{Lang().user.last_name}</label>
                    <input className='input border w-full' name='last_name' placeholder={Lang().user.enter_last_name} onChange={this.handleChange} value={this.state.last_name} />
                </div>
            </div>
            <div className={'grid grid-cols-12 gap-6 mt-5'}>
                <div className={'intro-y col-span-12 lg:col-span-6 sm: col-span-6'}>
                    <label className="d-block">{Lang().user.gender}:</label>
                    <div className="flex items-center flex-wrap">
                        <RadioBox
                            name='gender'
                            checked={this.state.gender == 'Male'}
                            value='Male'
                            label='Male'
                            onChange={this.handleChange}
                        />
                        <RadioBox
                            name='gender'
                            checked={this.state.gender == 'Female'}
                            value='Female'
                            label='Female'
                            onChange={this.handleChange}
                        />
                    </div>
                </div>
                <div className={'intro-y col-span-12 lg:col-span-6 sm: col-span-6'}>
                    <label className="d-block">{Lang().user.civility}:</label>
                    <div className="flex items-center flex-wrap">
                        <RadioBox
                            name='civility'
                            checked={this.state.civility == 'Mr.'}
                            value='Mr.'
                            label='Mr.'
                            onChange={this.handleChange}
                        />
                        <RadioBox
                            name='civility'
                            checked={this.state.civility == 'Mrs.'}
                            value='Mrs.'
                            label='Mrs.'
                            onChange={this.handleChange}
                        />
                    </div>
                </div>
            </div>
            <div className={'grid grid-cols-12 gap-6 mt-5'}>
                <div className={'intro-y col-span-12 lg:col-span-12 sm: col-span-12'}>
                    <label className={'font-medium'}>{Lang().user.photo}</label>
                    <Dropify
                        title={Lang().user.photo_upload}
                        subtitle={Lang().app.dropify_subtitle}
                        defaultImage={this.state.profile_image_src}
                        onRemove={this.handleRemoveProfileImage}
                        onChange={this.hanldeAddProfileImage}
                    />
                </div>
            </div>
            <div className={'grid grid-cols-12 gap-6 mt-5'}>
                <div className={'intro-y col-span-12 lg:col-span-12 sm: col-span-12'}>
                    <label className="font-medium">{Lang().user.phone_numbers}:</label>
                    {
                        this.state.phone_numbers.map((phone_number, index) => {
                            return <div className="grid grid-cols-12 gap-3 mt-5" key={index}>
                                <div className="w-full col-span-6 sm:col-span-12 relative md:col-span-12 xxl:col-span-12">
                                    <ReactPhoneInput
                                        inputClass={'input border w-full'}
                                        containerClass='react-tel-input'
                                        placeholder='Enter Phone Number'
                                        defaultCountry={this.state.countryCode}
                                        value={phone_number.mobile_no}
                                        onBlur={(event) => {
                                            this.verifyPhoneNumber(event, index)
                                        }}
                                        onChange={(value) => {
                                            this.updatePhoneNumber(value, index)
                                        }} />

                                    <div className="absolute top-0 right-0 rounded-r w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600" style={{ cursor: 'pointer' }} onClick={() => this.handleRemovePhone(index)}>

                                        {
                                            phone_number.loading == false && phone_number.valid == true && <i className='text-theme-1 icon-check' />
                                        }

                                        {
                                            phone_number.loading == false && phone_number.mobile_no != null && phone_number.mobile_no != "" && phone_number.valid == false && <i className='text-warning mi-error' />
                                        }
                                        {
                                            phone_number.loading == true && <i className="icon-spinner2 spinner"></i>
                                        }
                                        <i className="icon-bin" />

                                    </div>
                                </div>
                                <div className="col-span-6 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                {
                                    phone_number.mobile_no != null && phone_number.mobile_no != "" && phone_number.loading == false && phone_number.valid == false &&
                                    <span className="text-theme-6 mt-2">{Lang().customers.invalid_phone_number}</span>
                                }
                                </div>
                            </div>
                        })
                    }
                </div>
                <div className={'intro-y col-span-12 lg:col-span-12 sm: col-span-12 mt-1'}>
                    <button type="button" className="button mr-2 mb-2 bg-theme-1 text-white" onClick={this.handleAddNewPhone}><b><i className="icon-plus3" /></b> Add New</button>
                </div>
            </div>
            
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-6 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().app.email}:</label>
                    <div className="relative">
                        <input type="text" className="input border w-full pt-3 pb-3" placeholder={Lang().user.enter_email} name="email"
                            onBlur={this.verifyEmail}
                            disabled = {this.state.page_mode == 'self_edit'}
                            value={this.state.email} onChange={this.handleChange} />
                        <div className="absolute top-0 right-0 rounded-r w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600" style={{ cursor: 'pointer' }}  >
                            {
                                this.state.email_verify.loading == false && this.state.email_verify.valid == true && <i className='text-theme-1 icon-check' />
                            }

                            {
                                this.state.email_verify.loading == false && this.state.email != null && this.state.email != "" && this.state.email_verify.valid == false && <i className='text-theme-6 icon-error' />
                            }
                            {
                                this.state.email_verify.loading == true && <i className="icon-spinner2 spinner"></i>
                            }
                        </div>
                    </div>
                    {
                        this.state.email != null && this.state.email != "" && this.state.email_verify.loading == false && this.state.email_verify.valid == false &&
                        <span className="text-theme-6 pl-2">{this.state.email_verify.error_message}</span>
                    }
                </div>

            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-6 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().user.account_name}:</label>
                    <input className='input border w-full' name='name' 
                    disabled = {this.state.page_mode == 'self_edit'}
                    value={this.state.name} onChange={this.handleChange} placeholder={Lang().user.enter_account_name} />
                </div>
            </div>
            {
                this.state.id == null && <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-6 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label className="font-medium">{Lang().user.password}:</label>
                        <input className='input border w-full' type='password' onChange={this.handleChange} name='password' placeholder={Lang().user.enter_password} />
                    </div>
                </div>
            }

            {
                this.state.page_mode != 'self_edit' && <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                    <label className="font-medium">{Lang().user.role}:</label>
                    <Select
                        value={this.state.selected_role}
                        onChange={(selectedOption) => {
                            this.setState({
                                role: selectedOption.value, selected_role: selectedOption
                            })
                        }}
                        options={this.state.roles.map((role, index) => {
                            return { label: role.role_name, value: role.id }
                        })}
                        placeholder={Lang().user.choose_a_role}
                    />
                </div>
                <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                    <label className="font-meidum">{Lang().user.choose_locations}:</label>
                    <Select
                        value={this.state.selected_location}
                        onChange={(selectedOption) => {
                            this.setState({
                                selected_location: selectedOption
                            })
                        }}
                        options={this.state.locations.map((location, index) => {
                            return { label: location.name, value: location.id }
                        })}
                        isMulti
                        placeholder={Lang().user.choose_locations} />
                </div>
            </div>
            }
            <div className="p-5">
                <button type="submit" className="button mr-2 mb-2 bg-theme-6 text-white">{Lang().user.save}</button>
            </div>
        </form>
    }
}
