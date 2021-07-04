import React from 'react'
import Lang from "../../Lang/Lang";
import RadioBox from "../../global_ui_components/RadioBox";
import CheckBox from "../../global_ui_components/CheckBox";
import Dropify from "../../global_ui_components/Dropify";
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'
import countryList from 'react-select-country-list'
import Select, { createFilter } from 'react-select';
import DatePicker from 'react-date-picker';
import NumberFormat from 'react-number-format';
import Axios from 'axios';
import config from '../../config';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import Modal from 'react-responsive-modal';
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Autocomplete from 'react-google-autocomplete';
import { formatPhoneNumberIntl } from 'react-phone-number-input'

const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)
const countryFormat = ({ value, label }) => {
    return <div className='flex items-center px-1 py-1'>
        <img src={`https://www.countryflags.io/${value}/shiny/48.png`} className="mr-2"/>{countryList().getLabel(value)}
    </div>
}
const duration_options = [{ label: 'Day(s)', value: 'day' }, { label: 'Week(s)', value: 'week' }, { label: 'Month(s)', value: 'month' }, { label: 'Year(s)', value: 'year' }]

const CustomOption = (option) =>

    <div {...option.innerProps}>{
        inventoryFormat(option.data)
    }{/* your component internals */}</div>


const inventoryFormat = ({ value, inventory }) => {
    return <div className='p-2'>
        <div className="flex items-center">
            <div className="mr-3 flex items-center justify-center flex-wrap">
                <a href="#" onClick={e => e.preventDefault()}>
                    <img src={inventory.make_details.brand_logo} className="rounded-full" width={60} height={60} alt="" />
                </a>
                {
                    inventory.photo_details.length > 0 && <a href="#" onClick={e => e.preventDefault()}>
                        <img src={inventory.photo_details[0].image_src} className="car_photo" id={9} width={100} height={60} alt="" />
                    </a>
                }
            </div>
            <div>
                <a href="#" onClick={e => e.preventDefault()} className="text-theme-1 font-medium">{inventory.make_details.name} {inventory.model_details.name} {inventory.year}</a>
                <div className="text-sm">
                    {inventory.generation_details ? inventory.generation_details.name : ''} - {inventory.serie_details ? inventory.serie_details.name : ''} - {inventory.trim_details ? inventory.trim_details.name : ''} - {inventory.equipment_details ? inventory.equipment_details.name : ''}
                </div>
                <div className="text-sm">
                    {inventory.location_details ? inventory.location_details.name : ''}
                </div>
            </div>
        </div>
    </div>
}

export default class LeadsForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            first_name: null,
            last_name: null,
            middle_name: null,
            gender: null,
            civility: null,
            date_of_birth: null,
            new_tags: null,
            status: null,
            facebook_url: null,
            profile_image_src: null,
            profile_image: null,
            country_base_residence: null,
            looking_to_price_currency: JSON_DATA['cur_currency']['value'],
            timeframe_to_sell: null,
            timeframe_to_buy: null,
            timeframe_to_buy_duration: null,
            timeframe_to_sell_duration: null,
            phone_numbers: [
                {
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
                    line_type: "",
                    messaging_apps: [],
                    intl_formmated_number: "",
                    loading: false
                }
            ],
            removed_phone_numbers: [],
            emails: [
                {
                    email: '', valid: false
                }
            ],
            removed_emails: [],
            messaging_apps: [
                {
                    type: '', number: ''
                }
            ],
            deals: [{
                date: new Date(),
                price: null,
                inventory: null,
                notes: null,
                user: null,
                location: null,
                currency: null
            }],
            looking_to: [],
            looking_to_price_from: null,
            looking_to_price_to: null,


            //page details
            messaging_app_types: [
                'WhatsApp', 'Viber', 'Telegram', 'Line'
            ],
            currencies: JSON_DATA['currencies'],
            inventories: JSON_DATA['inventories'],
            locations: JSON_DATA['locations'],
            users: JSON_DATA['users'],
            tags: JSON_DATA['tags'],
            sts: JSON_DATA['status'],
            selected_looking_to_price_currency: JSON_DATA['cur_currency'],
            countries: countryList().getData(),
            is_convertmodal_show: false,
            open_inventory_dropdown: false,
            create_transaction: false,
        }
        this.handleChange = this.handleChange.bind(this)
        this.hanldeAddProfileImage = this.hanldeAddProfileImage.bind(this)
        this.handleRemoveProfileImage = this.handleRemoveProfileImage.bind(this)
        this.handleAddNewPhone = this.handleAddNewPhone.bind(this)
        this.handleRemovePhone = this.handleRemovePhone.bind(this)
        this.updatePhoneNumber = this.updatePhoneNumber.bind(this)
        this.verifyPhoneNumber = this.verifyPhoneNumber.bind(this)
        this.handleAddEmail = this.handleAddEmail.bind(this)
        this.handleUpdateEmail = this.handleUpdateEmail.bind(this)
        this.handleRemoveEmail = this.handleRemoveEmail.bind(this)
        this.handleAddMessagingApp = this.handleAddMessagingApp.bind(this)
        this.handleRemoveMessagingApp = this.handleRemoveMessagingApp.bind(this)
        this.handleAddDeal = this.handleAddDeal.bind(this)
        this.handleUpdateDeal = this.handleUpdateDeal.bind(this)
        this.handleRemoveDeal = this.handleRemoveDeal.bind(this)
        this.handleUpdatePhoneNumberType = this.handleUpdatePhoneNumberType.bind(this)
        this.verifyEmail = this.verifyEmail.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.handleSave = this.handleSave.bind(this)
        this.getLoadData = this.getLoadData.bind(this)
        this.handleConvertModalShow = this.handleConvertModalShow.bind(this)
        this.handleChangeLeadLookingOption = this.handleChangeLeadLookingOption.bind(this)
        this.handleConvert = this.handleConvert.bind(this)
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit') {
            this.getLoadData(urlParams.leadId)
        }
    }
    getLoadData(leadId) {
        this.setState({
            is_loading: true
        }, () => {
            Axios.get(`/leads/ajax_load/${leadId}`)
                .then(response => {
                    let phone_numbers = response.data.phone_numbers
                    for (let i = 0; i < phone_numbers.length; i++) {
                        phone_numbers[i].messaging_apps = phone_numbers[i].messaging_apps.split(',')
                    }
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
    handleAddEmail() {
        let { emails } = this.state
        emails.push({
            email: '', valid: false
        })
        this.setState({
            emails: emails
        })
    }
    handleUpdateEmail(event, index) {
        let { emails } = this.state
        emails[index].email = event.target.value
        this.setState({
            emails: emails
        })
    }
    handleRemoveEmail(index) {
        let { emails, removed_emails } = this.state
        let email = emails[index]
        if (email._id) {
            removed_emails.push(email._id)
        }
        emails.splice(index, 1)
        this.setState({
            emails: emails,
            removed_emails: removed_emails
        })
    }

    handleAddMessagingApp() {
        let messaging_apps = this.state.messaging_apps
        messaging_apps.push({ type: '', number: '' })
        this.setState({
            messaging_apps: messaging_apps
        })
    }
    handleRemoveMessagingApp(index) {
        let messaging_apps = this.state.messaging_apps
        messaging_apps.splice(index, 1)
        this.setState({
            messaging_apps: messaging_apps
        })
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
            line_type: "",
            messaging_apps: [],
            intl_formmated_number: "",
            loading: false
        })
        this.setState({
            phone_numbers: phone_numbers
        })
    }
    handleRemovePhone(index) {
        let { phone_numbers, removed_phone_numbers } = this.state
        let phone_number = phone_numbers[index]
        if (phone_number._id) {
            removed_phone_numbers.push(phone_number._id)
        }
        phone_numbers.splice(index, 1)

        this.setState({
            phone_numbers: phone_numbers,
            removed_phone_numbers: removed_phone_numbers
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

    handleUpdatePhoneNumberType(e, index) {
        let phone_numbers = this.state.phone_numbers
        if (phone_numbers[index].messaging_apps.includes(e.target.value)) {
            let appIndex = phone_numbers[index].messaging_apps.indexOf(e.target.value)
            phone_numbers[index].messaging_apps.splice(appIndex, 1)
        }
        else {
            phone_numbers[index].messaging_apps.push(e.target.value)
        }
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
                    }, () => {
                        let post_data = {
                            phone_number: phone_numbers[index],
                        }
                        // let formData = new FormData();
                        // formData.append('post_data', JSON.stringify(post_data))
                        // Axios.post(`/leads/ajax_phone_exist`, formData)
                        //     .then(response => {
                        //         console.log(response);
                        //         if (response.data.status == 'success' && response.data.is_exist == true) {
                        //             confirmAlert({
                        //                 title: "Alert!",
                        //                 message: "An other lead has the same phone number. Do you want to continue create a new lead or edit the existing one?",
                        //                 buttons: [
                        //                     {
                        //                         label: 'Continue',
                        //                     },
                        //                     {
                        //                         label: 'Edit',
                        //                         onClick: () => location.href = `/leads/edit/${response.data.lead_id}`
                        //                     }
                        //                 ]
                        //             });
                        //         }
                        //     })
                    })
                })
        })

    }

    handleAddDeal() {
        let deals = this.state.deals
        deals.push({
            date: new Date(),
            price: null,
            inventory: null,
            notes: null,
            user: null,
            location: null,
            currency: null
        })
        this.setState({
            deals: deals
        })
    }

    handleUpdateDeal(value, index, field) {
        let deals = this.state.deals
        deals[index][field] = value
        this.setState({
            deals: deals
        })
    }
    handleRemoveDeal(index) {
        let { deals } = this.state
        deals.splice(index, 1)
        this.setState({
            deals: deals
        })

    }
    verifyEmail(event, index) {
        let emails = this.state.emails
        emails[index].loading = true
        let email = event.target.value
        let self = this
        this.setState({
            emails: emails
        }, () => {
            Axios.get(`https://apps.emaillistverify.com/api/verifyEmail?secret=${config.email_verify_api_key}&email=${email}`)
                .then(response => {
                    if (response.data == 'ok') {
                        emails[index].valid = true
                        emails[index].loading = false
                        self.setState({
                            emails: emails,
                        })
                    }
                    else {
                        emails[index].valid = false
                        emails[index].loading = false
                        if (response.data == 'error') {
                            emails[index].error_message = "The server is saying that delivery failed, but no information about,the email exists"
                        }
                        else if (response.data == 'smtp_error') {
                            emails[index].error_message = "The SMTP answer from the server is invalid or the destination server, reported an internal error to us"
                        }
                        else if (response.data == 'smtp_protocol') {
                            emails[index].error_message = "The destination server allowed us to connect but the SMTP, session was closed before the email was verified"
                        }
                        else if (response.data == 'unknown_email') {
                            emails[index].error_message = "The server said that the delivery failed and that the email address does, not exist"
                        }
                        else if (response.data == 'attempt_rejected') {
                            emails[index].error_message = "The delivery failed; the reason is similar to “rejected”"
                        }
                        else if (response.data == 'relay_error') {
                            emails[index].error_message = "The delivery failed because a relaying problem took place"
                        }
                        else if (response.data == 'antispam_system') {
                            emails[index].error_message = "Some anti - spam technology is blocking the, verification progress"
                        }
                        else if (response.data == 'email_disabled') {
                            emails[index].error_message = "The email account is suspended, disabled, or limited and can not, receive emails"
                        }
                        else if (response.data == 'domain_error') {
                            emails[index].error_message = "The email server for the whole domain is not installed or is, incorrect, so no emails are deliverable"
                        }
                        else if (response.data == 'ok_for_all') {
                            emails[index].error_message = "The email server is saying that it is ready to accept letters, to any email address"
                        }
                        else if (response.data == 'dead_server') {
                            emails[index].error_message = "The email server is dead, and no connection to it could be established"
                        }
                        else if (response.data == 'syntax_error') {
                            emails[index].error_message = "There is a syntax error in the email address"
                        }
                        else if (response.data == 'unknown') {
                            emails[index].error_message = "The email delivery failed, but no reason was given"
                        }
                        else if (response.data == 'accept_all') {
                            emails[index].error_message = "The server is set to accept all emails at a specific domain., These domains accept any email you send to them"
                        }
                        else if (response.data == 'disposable') {
                            emails[index].error_message = "The email is a temporary address to receive letters and expires, after certain time period"
                        }
                        else if (response.data == 'spam_traps' || response.data == 'spamtrap') {
                            emails[index].error_message = "The email address is maintained by an ISP or a third party, which neither clicks nor opens emails"
                        }
                        else {
                            emails[index].error_message = "Invalid Email"
                        }
                        self.setState({
                            emails: emails,
                        })
                    }
                })
        })

    }
    handleSubmit(e) {
        e.preventDefault();
        this.handleSave()
    }
    handleSave(option = null) {
        if (option == 'draft') {

        }
        else if (option == 'customer') {

        }
        else {

        }

        let { emails } = this.state
        let { phone_numbers } = this.state
        let flag = 0
        emails.map((email, index) => {
            if (email.valid == false || email.loading == true) {
                flag = 1;
                return;
            }
        })

        phone_numbers.map((number, index) => {
            if (number.valid == false || number.loading == true) {
                flag = 1;
                return;
            }
        })

        if (flag == 1) {
            confirmAlert({
                title: "There are some invalid emails or phone numbers",
                message: 'Do you want to continue or edit?',
                buttons: [
                    {
                        label: 'Edit',
                    },
                    {
                        label: 'Continue',
                        onClick: () => {
                            let post_data = {
                                id: this.state.id,
                                first_name: this.state.first_name,
                                last_name: this.state.last_name,
                                middle_name: this.state.middle_name,
                                gender: this.state.gender,
                                civility: this.state.civility,
                                phone_numbers: this.state.phone_numbers,
                                emails: this.state.emails,
                                country_base_residence: this.state.country_base_residence,
                                address: this.state.address,
                                city: this.state.city,
                                postal_code: this.state.postal_code,
                                tags: this.state.new_tags,
                                status: this.state.status,
                                facebook_url: this.state.facebook_url,
                                looking_to: this.state.looking_to,
                                looking_to_price_from: this.state.looking_to_price_from,
                                looking_to_price_to: this.state.looking_to_price_to,
                                date_of_birth: this.state.date_of_birth,
                                looking_to_price_currency: this.state.looking_to_price_currency,
                                timeframe_to_sell: this.state.timeframe_to_sell,
                                timeframe_to_buy: this.state.timeframe_to_buy,
                                timeframe_to_buy_duration: this.state.timeframe_to_buy_duration,
                                timeframe_to_sell_duration: this.state.timeframe_to_sell_duration,
                                removed_phone_numbers: this.state.removed_phone_numbers,
                                removed_emails: this.state.removed_emails,
                            }
                            let formData = new FormData();
                            if (this.state.profile_image != null) {
                                formData.append('profile_image', this.state.profile_image)
                            }
                            formData.append('post_data', JSON.stringify(post_data))
                            Axios.post('/leads/save', formData)
                                .then(response => {
                                    if (response.data.status == 'success') {
                                        confirmAlert({
                                            title: response.data.option == 'created' ? "You created a lead successfully!" : "You updated a lead successfully!",
                                            message: response.data.option == 'created' ? 'Do you want to create new lead continue?' : "Do you want to stay in this page?",
                                            buttons: [
                                                {
                                                    label: 'Yes',
                                                    onClick: () => response.data.option == 'created' ? location.href = "/leads/create" : location.href = `/leads/edit/${response.data.lead_id}`
                                                },
                                                {
                                                    label: 'No, Thanks',
                                                    onClick: () => location.href = "/leads/all"
                                                }
                                            ]
                                        });
                                    }
                                })
                        }
                    }
                ]
            });
        }
        else {

            let post_data = {
                id: this.state.id,
                first_name: this.state.first_name,
                last_name: this.state.last_name,
                middle_name: this.state.middle_name,
                gender: this.state.gender,
                civility: this.state.civility,
                phone_numbers: this.state.phone_numbers,
                emails: this.state.emails,
                country_base_residence: this.state.country_base_residence,
                address: this.state.address,
                city: this.state.city,
                postal_code: this.state.postal_code,
                tags: this.state.new_tags,
                status: this.state.status,
                facebook_url: this.state.facebook_url,
                looking_to: this.state.looking_to,
                looking_to_price_from: this.state.looking_to_price_from,
                looking_to_price_to: this.state.looking_to_price_to,
                date_of_birth: this.state.date_of_birth,
                looking_to_price_currency: this.state.looking_to_price_currency,
                timeframe_to_sell: this.state.timeframe_to_sell,
                timeframe_to_buy: this.state.timeframe_to_buy,
                timeframe_to_buy_duration: this.state.timeframe_to_buy_duration,
                timeframe_to_sell_duration: this.state.timeframe_to_sell_duration,
                removed_phone_numbers: this.state.removed_phone_numbers,
                removed_emails: this.state.removed_emails,
            }
            let formData = new FormData();
            if (this.state.profile_image != null) {
                formData.append('profile_image', this.state.profile_image)
            }
            formData.append('post_data', JSON.stringify(post_data))
            Axios.post('/leads/save', formData)
                .then(response => {
                    if (response.data.status == 'success') {
                        confirmAlert({
                            title: response.data.option == 'created' ? "You created a lead successfully!" : "You updated a lead successfully!",
                            message: response.data.option == 'created' ? 'Do you want to create new lead continue?' : "Do you want to stay in this page?",
                            buttons: [
                                {
                                    label: 'Yes',
                                    onClick: () => response.data.option == 'created' ? location.href = "/leads/create" : location.href = `/leads/edit/${response.data.lead_id}`
                                },
                                {
                                    label: 'No, Thanks',
                                    onClick: () => location.href = "/leads/all"
                                }
                            ]
                        });
                    }
                })
        }
    }
    handleConvertModalShow() {

    }
    handleConvert() {
        let post_data = {
            id: this.state.id,
            first_name: this.state.first_name,
            last_name: this.state.last_name,
            middle_name: this.state.middle_name,
            gender: this.state.gender,
            civility: this.state.civility,
            phone_numbers: this.state.phone_numbers,
            emails: this.state.emails,
            country_base_residence: this.state.country_base_residence,
            address: this.state.address,
            city: this.state.city,
            postal_code: this.state.postal_code,
            tag: this.state.tag,
            status: this.state.status,
            facebook_url: this.state.facebook_url,
            looking_to: this.state.looking_to,
            looking_to_price_from: this.state.looking_to_price_from,
            looking_to_price_to: this.state.looking_to_price_to,
            date_of_birth: this.state.date_of_birth,
            looking_to_price_currency: this.state.looking_to_price_currency,
            timeframe_to_sell: this.state.timeframe_to_sell,
            timeframe_to_buy: this.state.timeframe_to_buy,
            timeframe_to_buy_duration: this.state.timeframe_to_buy_duration,
            timeframe_to_sell_duration: this.state.timeframe_to_sell_duration,
        }
        let formData = new FormData();
        if (this.state.profile_image != null) {
            formData.append('profile_image', this.state.profile_image)
        }
        formData.append('post_data', JSON.stringify(post_data))
        Axios.post('/leads/save', formData)
            .then(response => {
                if (response.data.status == 'success') {


                    let convert_data = {
                        deals: this.state.deals,
                        create_transaction: this.state.create_transaction,
                        id: this.state.id
                    }

                    Axios.post('/leads/convert', convert_data)
                        .then(response => {
                            if (response.data.status == 'success') {
                                location.href = "/customers/all"
                            }
                        })
                }
            })
    }
    handleChangeLeadLookingOption(option) {
        let looking_to = this.state.looking_to
        if (looking_to.includes(option)) {
            let optionIndex = this.state.looking_to.indexOf(option)
            looking_to.splice(optionIndex, 1)
        }
        else {
            looking_to.push(option)
        }
        this.setState({
            looking_to: looking_to
        })
    }
    getDuration(option) {
        if (option == 'day') return { label: 'Day(s)', value: 'day' }
        else if (option == 'week') return { label: 'Week(s)', value: 'week' }
        else if (option == 'month') return { label: 'Month(s)', value: 'month' }
        else if (option == 'year') return { label: 'Year(s)', value: 'year' }
        else return null
    }
    render() {
        return <><form
            onSubmit={this.handleSubmit}
            className='p-3'
        >
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-4">
                    <label>{Lang().user.first_name}</label>
                    <input className='input w-full border' name='first_name' value={this.state.first_name} onChange={this.handleChange} placeholder={Lang().user.enter_first_name} required />
                </div>
                <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-4">
                    <div className="form-group">
                        <label>{Lang().user.middle_name}</label>
                        <input className='input w-full border' name='middle_name' value={this.state.middle_name} onChange={this.handleChange} placeholder={Lang().user.enter_middle_name} />
                    </div>
                </div>
                <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-4">
                    <div className="form-group">
                        <label>{Lang().user.last_name}</label>
                        <input className='input w-full border' name='last_name' value={this.state.last_name} onChange={this.handleChange} placeholder={Lang().user.enter_last_name} required />
                    </div>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                    <label>{Lang().user.civility}:</label>
                    <div className="flex flex-col sm:flex-row mt-2">
                        <RadioBox
                            name='civility'
                            checked={this.state.civility == 'Mr.'}
                            value='Mr.'
                            label='Mr.'
                            onChange={this.handleChange}
                        />
                        <RadioBox
                            name='civility'
                            checked={this.state.civility == 'Ms.'}
                            value='Ms.'
                            label='Ms.'
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
                <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                    <label>{Lang().user.gender}:</label>
                    <div className="flex flex-col sm:flex-row mt-2">
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
                <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                    <label>{Lang().leads.date_of_birth}</label>
                    <DatePicker
                        onChange={(date) => this.setState({ date_of_birth: date })}
                        value={this.state.date_of_birth}
                    />
                </div>
            </div>


            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                <label>{Lang().user.phone_numbers}:</label>
                {
                    this.state.phone_numbers.map((phone_number, index) => {
                        return <div className="grid grid-cols-12 gap-3 mt-5" key={index}>
                            <div className="w-full col-span-12 sm:col-span-12 relative md:col-span-12 xxl:col-span-12">
                                <ReactPhoneInput
                                    style={{width: '100% !important'}}
                                    inputClass={'input border w-full'}
                                    containerClass='react-tel-input '
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
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                            {
                                phone_number.mobile_no != null && phone_number.mobile_no != "" && phone_number.loading == false && phone_number.valid == false &&
                                <span className="text-theme-6 mt-2">Invalid Phone Number</span>
                            }
                            </div>
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                {/* <label className="font-weight-semibold">App Type</label> */}
                                <div className="flex flex-col sm:flex-row mt-2">
                                    {
                                        this.state.messaging_app_types.map((messaging_app_type, type_index) => {
                                            return (
                                                <div key={type_index} className="flex items-center text-gray-700 mr-2">
                                                    <input type="checkbox" 
                                                        className="input border mr-2" id={`horizontal-checkbox-${type_index}`}
                                                        checked= {phone_number.messaging_apps.includes(messaging_app_type)}
                                                        value={messaging_app_type}
                                                        onChange={(event) => this.handleUpdatePhoneNumberType(event, index)} />
                                                    <label className="cursor-pointer select-none" htmlFor={`horizontal-checkbox-${type_index}`}>
                                                        {messaging_app_type}
                                                    </label>
                                                </div>
                                            );
                                        })
                                    }
                                </div>
                            </div>

                        </div>
                    })
                }
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 mt-2">
                    <button type="button" className="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" onClick={this.handleAddNewPhone}><b><i className="icon-plus3" /></b> Add New</button>
                </div>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label>{Lang().app.email}</label>
                    {
                        this.state.emails.map((email, index) => {
                            return <div className="grid grid-cols-12 gap-3 mt-5" key={index}>
                                <div className="w-full col-span-12 sm:col-span-12 relative md:col-span-12 xxl:col-span-12">
                                    <input type="email" className="w-full input border" placeholder={Lang().user.enter_email} value={email.email} onChange={(e) => this.handleUpdateEmail(e, index)} onBlur={(e) => this.verifyEmail(e, index)} />
                                    <div className="absolute top-0 right-0 rounded-r w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600" style={{ cursor: 'pointer' }} onClick={() => this.handleRemoveEmail(index)}>
                                        {
                                            email.loading == false && email.valid == true && <i className='text-theme-1 icon-check' />
                                        }

                                        {
                                            email.loading == false && email.email != null && email.email != "" && email.valid == false && <i className='text-them-6 icon-error' />
                                        }
                                        {
                                            email.loading == true && <i className="icon-spinner2 spinner"></i>
                                        }
                                        <i className="icon-bin" />
                                    </div>
                                </div>
                                
                                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                {
                                    email.email != null && email.email != "" && email.loading == false && email.valid == false &&
                                    <span className="text-theme-6 mt-5 pl-2">{email.error_message}</span>
                                }
                                </div>
                            </div>
                        })
                    }
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 mt-2">
                        <button type="button" className="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" onClick={this.handleAddEmail}><b><i className="icon-plus3" /></b> Add New</button>
                    </div>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="">{Lang().leads.address}:</label>
                    <Autocomplete
                        onPlaceSelected={(place) => {
                            let address_data = {}
                            place.address_components.map((address_component, index) => {

                                if (address_component.types.includes('country')) {
                                    address_data.country_base_residence = address_component.short_name
                                    address_data.select_country_base_residence = { label: address_component.long_name, value: address_component.short_name }
                                }
                                if (address_component.types.includes('postal_code')) {
                                    address_data.postal_code = address_component.short_name

                                }
                                if (address_component.types.includes('locality') && address_component.types.includes('political')) {
                                    address_data.city = address_component.long_name

                                }

                            })
                            this.setState({
                                address: place.formatted_address,
                                latitude: place.geometry.location.lat(),
                                longitude: place.geometry.location.lng(),
                                ...address_data
                            })
                        }}
                        value={this.state.address}
                        name='address'

                        onChange={this.handleChange}
                        className='input border w-full pt-3 pb-3'
                        types={[]}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label>{Lang().leads.country_residence}:</label>
                    {/* <input type="text" className="form-control" placeholder="..." /> */}
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        value={this.state.select_country_base_residence}
                        options={this.state.countries.map((country, index) => {
                            return { value: country.value, label: `${country.label}(${country.value})` }
                        })}

                        isClearable={true}
                        formatOptionLabel={countryFormat}
                        onChange={(selectedOption) => {
                            this.setState({
                                country_base_residence: selectedOption ? selectedOption.value : null,
                                select_country_base_residence: selectedOption,
                                city: '',
                                latitude: null,
                                longitude: null
                            }, () => {
                            })
                        }}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                    <label>{Lang().leads.city}</label>
                    <input className='input border w-full' value={this.state.city} name='city' onChange={this.handleChange} />
                </div>
                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                    <label>{Lang().leads.postcode}</label>
                    <input className='w-full input border' value={this.state.postal_code} name='postal_code' onChange={this.handleChange} />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label>{Lang().leads.facebook_url}</label>
                    <input className='input w-full border' name='facebook_url' onChange={this.handleChange} value={this.state.facebook_url} />
                </div>

            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label>{Lang().user.photo}</label>
                    <Dropify
                        title={Lang().leads.photo_upload}
                        subtitle={Lang().app.dropify_subtitle}
                        defaultImage={this.state.profile_image_src}
                        onRemove={this.handleRemoveProfileImage}
                        onChange={this.hanldeAddProfileImage}
                    />
                </div>
            </div>


            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="d-block">{Lang().leads.looking_to}:</label>
                    <div className="flex items-center text-gray-700 mr-2">
                        <CheckBox
                            checkbox_type='inline'
                            checked={this.state.looking_to.includes('Buy')}
                            onChange={() => this.handleChangeLeadLookingOption('Buy')}
                            name='looking_to[]'
                            value={'Buy'}
                            label={'Buy'}
                        />
                        <CheckBox
                            checkbox_type='inline'
                            checked={this.state.looking_to.includes('Sell')}
                            onChange={() => this.handleChangeLeadLookingOption('Sell')}
                            name='looking_to[]'
                            value={'Sell'}
                            label={'Sell'}
                        />
                        <CheckBox
                            checkbox_type='inline'
                            checked={this.state.looking_to.includes('Trade')}
                            onChange={() => this.handleChangeLeadLookingOption('Trade')}
                            name='looking_to[]'
                            value={'Trade'}
                            label={'Trade'}
                        />
                    </div>
                </div>
            </div>

            {
                this.state.looking_to.length > 0 && <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().leads.price_range_looking}:</label>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-4 md:col-span-4 xxl:col-span-4">
                                <NumberFormat
                                    value={this.state.looking_to_price_from}
                                    className={'input border w-full'}
                                    placeholder='Enter Price'
                                    thousandSeparator={true}
                                    onValueChange={(values) => {
                                        const { formattedValue, value } = values
                                        this.setState({ looking_to_price_from: value })
                                    }} />
                            </div>
                            <div className="col-span-12 sm:col-span-4 md:col-span-4 xxl:col-span-4">
                                <NumberFormat
                                    value={this.state.looking_to_price_to}
                                    className={'input border w-full'}
                                    placeholder='Enter Price'
                                    thousandSeparator={true}
                                    onValueChange={(values) => {
                                        const { formattedValue, value } = values
                                        this.setState({ looking_to_price_to: value })
                                    }} />
                            </div>
                            <div className="col-span-12 sm:col-span-4 md:col-span-4 xxl:col-span-4">
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    options={this.state.currencies.map((currency, index) => {
                                        return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                                    })}
                                    onChange={selectedOption => {
                                        this.setState({
                                            looking_to_price_currency: selectedOption.value,
                                            selected_looking_to_price_currency: selectedOption
                                        })
                                    }}
                                    value={this.state.selected_looking_to_price_currency}
                                    placeholder='Choose Currency'
                                    isClearable={true}
                                />
                            </div>
                        </div>
                    </div>
                    {
                        (this.state.looking_to.includes('Buy') || this.state.looking_to.includes('Trade')) && 
                            
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                <label>{Lang().leads.timeframe_to_buy}:</label>
                                <div className="grid grid-cols-12 gap-3 mt-5">
                                    <div className="col-span-12 sm:col-span-8 md:col-span-8 xxl:col-span-8">
                                        <input className="input border w-full"
                                            name='timeframe_to_buy' onChange={this.handleChange} value={this.state.timeframe_to_buy}
                                            placeholder="Enter timeframe to buy" />
                                    </div>
                                    <div className="col-span-12 sm:col-span-4 md:col-span-4 xxl:col-span-4">
                                        <Select
                                            options={duration_options}
                                            value={this.getDuration(this.state.timeframe_to_buy_duration)}
                                            onChange={(selectedOption) => {
                                                this.setState({ timeframe_to_buy_duration: selectedOption.value })
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>
                    }
                    {
                        (this.state.looking_to.includes('Sell') || this.state.looking_to.includes('Trade')) &&
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                <label>{Lang().leads.timeframe_to_sell}:</label>
                                    <div className="grid grid-cols-12 gap-3 mt-5">
                                        <div className="col-span-12 sm:col-span-8 md:col-span-8 xxl:col-span-8">
                                        <input className="input border w-full"
                                            name='timeframe_to_sell' onChange={this.handleChange} value={this.state.timeframe_to_sell}
                                            placeholder="Enter timeframe to sell" />
                                    </div>
                                    <div className="col-span-12 sm:col-span-4 md:col-span-4 xxl:col-span-4">
                                        <Select
                                            options={duration_options}
                                            value={this.getDuration(this.state.timeframe_to_sell_duration)}
                                            onChange={(selectedOption) => {
                                                this.setState({ timeframe_to_sell_duration: selectedOption.value })
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>
                    }

                </div>
            }

            {/*  */}
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-6 xxl:col-span-6">
                    <label>{Lang().leads.tags}</label>
                    <Select
                        isMulti={true}
                        onChange={(selectedOption) => {
                            this.setState({
                                new_tags: selectedOption
                            })
                        }}
                        value={this.state.new_tags}
                        options={
                            this.state.tags.map((tag, index) => {
                                return { label: tag.tag_name, value: tag.id }
                            })
                        }
                    />
                </div>
                <div className="col-span-12 sm:col-span-12 md:col-span-6 xxl:col-span-6">
                    <label>Status</label>
                    <Select
                        isMulti={false}
                        onChange={(selectedOption) => {
                            this.setState({
                                status: selectedOption
                            })
                        }}
                        value={this.state.status}
                        options={
                            this.state.sts.map((st, index) => {
                                return { label: st.status_name, value: st.id }
                            })
                        }
                    />
                </div>
            </div>
            <div className="p-5">
                <div className="preview flex flex-wrap">
                <button type='submit' className="button mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white"><i className="icon-save"></i>{Lang().leads.save}</button>
                <button type='button' className="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white ml-2" disabled={this.state.id ? false : true} data-toggle="modal" data-target="#modal_default"><i className="icon-save"></i>{Lang().leads.convert_as_customer}</button>
                </div>
            </div>

        </form >
            {
                this.state.id != null && <div id="modal_default" className="modal fade" tabIndex={-1}>
                    <div className="modal-dialog modal-lg">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">{Lang().leads.convert_as_customer}</h5>
                                <button type="button" className="close" data-dismiss="modal">×</button>
                            </div>
                            <div className="modal-body">
                                {
                                    this.state.deals.map((deal, index) => {
                                        return <div className="form" key={index}>
                                            <div className="form-group">
                                                <label className="font-weight-semibold">{Lang().leads.date_of_conversion}:</label>
                                                <DatePicker
                                                    onChange={(date) => this.handleUpdateDeal(date, index, 'date')}
                                                    className='form-control'
                                                    value={deal.date}
                                                />
                                            </div>
                                            <div className="form-group">
                                                <label className="font-weight-semibold">{Lang().leads.inventory_item_purchased}:</label>
                                                <Select
                                                    options={
                                                        this.state.inventories.map((inventory, index) => {
                                                            return {
                                                                value: inventory.id, inventory: inventory, label: inventory.make_details.name + ' ' + inventory.model_details.name + " " + inventory.year
                                                            }
                                                        })
                                                    }
                                                    onChange={selectedOption => {
                                                        this.handleUpdateDeal(selectedOption.value, index, 'inventory')
                                                        this.setState({ open_inventory_dropdown: false })
                                                    }}
                                                    onBlur={() => {
                                                        this.setState({ open_inventory_dropdown: false })
                                                    }}
                                                    filterOption={createFilter({ ignoreAccents: false })}
                                                    // formatOptionLabel={inventoryFormat}
                                                    onInputChange={(query, { action }) => {
                                                        if (query != '' && action === "input-change") {
                                                            this.setState({ open_inventory_dropdown: true });
                                                        }
                                                        else if (query === '') {
                                                            this.setState({ open_inventory_dropdown: false });
                                                        }
                                                    }}
                                                    menuIsOpen={this.state.open_inventory_dropdown}
                                                    components={{ Option: CustomOption }}
                                                    placeholder='Choose Inventory' />
                                            </div>
                                            <div className="row">
                                                <div className='col-md-6'>
                                                    <div className='form-group'>
                                                        <label className="font-weight-semibold">{Lang().leads.purchased_price}:</label>
                                                        <NumberFormat
                                                            value={deal.price}
                                                            className={'form-control'}
                                                            placeholder='Enter Price'
                                                            thousandSeparator={true}
                                                            onValueChange={(values) => {
                                                                const { formattedValue, value } = values
                                                                this.handleUpdateDeal(value, index, 'price')
                                                            }} />
                                                    </div>

                                                </div>
                                                <div className='col-md-6'>
                                                    <div className='form-group'>
                                                        <label className="font-weight-semibold">&nbsp;</label>
                                                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                                            options={this.state.currencies.map((currency, index) => {
                                                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                                                            })}
                                                            onChange={selectedOption => {
                                                                this.handleUpdateDeal(selectedOption.value, index, 'currency')
                                                            }}
                                                            placeholder='Currency'
                                                            isClearable={true}
                                                        />
                                                    </div>
                                                </div>



                                            </div>
                                            <div className='form-group'>
                                                <label className="font-weight-semibold">{Lang().leads.user_who_managed_sale}:</label>
                                                <Select
                                                    isMulti
                                                    onChange={selectedOption => {
                                                        this.handleUpdateDeal(selectedOption, index, 'user')
                                                    }}
                                                    options={this.state.users.map((user, index) => {
                                                        return { label: user.name, value: user._id }
                                                    })} />
                                            </div>
                                            <div className='form-group'>
                                                <label className="font-weight-semibold">Location of sale:</label>
                                                <Select
                                                    isMulti
                                                    options={this.state.locations.map((location, index) => {
                                                        return { label: location.name, value: location.id }
                                                    })}
                                                    onChange={selectedOption => {
                                                        this.handleUpdateDeal(selectedOption, index, 'location')
                                                    }}
                                                />
                                            </div>

                                            <div className='form-group'>
                                                <label className="font-weight-semibold">{Lang().leads.notes}:</label>
                                                <CKEditor

                                                    editor={ClassicEditor}
                                                    data={deal.notes}
                                                    onInit={editor => {
                                                    }}
                                                    onChange={(event, editor) => {
                                                        let notes = editor.getData()
                                                        this.handleUpdateDeal(notes, index, 'notes')
                                                    }}
                                                    onBlur={editor => {

                                                    }}
                                                    onFocus={editor => {
                                                    }}
                                                />
                                            </div>
                                            <div className="form-group">
                                                <CheckBox
                                                    checkbox_type='inline'
                                                    checked={this.state.create_transaction}
                                                    onChange={() => { this.setState({ create_transaction: !this.state.create_transaction }) }}
                                                    name='create_transaction'
                                                    value={true}
                                                    label={'Create Transaction Automatically'}
                                                />
                                            </div>
                                        </div>
                                    })
                                }
                                {/* <div className="col-md-12 mt-1">
                                    <button type="button" className="btn btn-success btn-labeled btn-labeled-left btn-sm legitRipple" onClick={this.handleAddDeal}><b><i className="icon-plus3" /></b> Add New</button>
                                </div> */}
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-link" data-dismiss="modal">{Lang().leads.close}</button>
                                <button type="button" className="btn bg-primary" onClick={this.handleConvert}>{Lang().leads.convert}</button>
                            </div>
                        </div>
                    </div>
                </div>
            }
        </>
    }
}
