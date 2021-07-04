import React from 'react'
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'
import Autocomplete from 'react-google-autocomplete';
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import AttachmentForm from '../InventoryForm/AttachmentForm';
import Axios from 'axios'
import Select from 'react-select';
import Dropify from '../../global_ui_components/Dropify';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
const JSON_DATA = JSON.parse(document.getElementById('location_form_data').innerHTML)
import GoogleMapReact from 'google-map-react';
import Marker from './Marker';
import Geocode from "react-geocode";
import { formatPhoneNumberIntl } from 'react-phone-number-input'
const GoogleMapApiKey = "AIzaSyA34nBk3rPJKXaNQaSX4YiLFoabX3DhkXs";
Geocode.setApiKey(GoogleMapApiKey);
import Lang from "../../Lang/Lang";
import config from '../../config';
export default class LocationForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            id: null,
            phone: '',
            name: '',
            address: '',
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
                    intl_formmated_number: "",
                    line_type: ""
                }
            ],
            email: '',
            social_medias: [
                {
                    social_url: ''
                }
            ],
            photos: [],
            website: '',
            type: null,
            description: null,
            logo: null,
            logo_url: null,
            latitude: 0,
            longitude: null,
            // form data
            type_list: JSON_DATA['type_list'],
            // page data
            select_type: null,
            countryCode: null,
            email_verify: {}
        }
        this.handleSubmit = this.handleSubmit.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleAddFiles = this.handleAddFiles.bind(this)
        this.handleRemovePhoto = this.handleRemovePhoto.bind(this)
        this.updatePhoneNumber = this.updatePhoneNumber.bind(this)
        this.handleAddNewPhone = this.handleAddNewPhone.bind(this)
        this.handleRemovePhone = this.handleRemovePhone.bind(this)
        this.handleAddSocialMedia = this.handleAddSocialMedia.bind(this)
        this.handleUpdateSocialMedia = this.handleUpdateSocialMedia.bind(this)
        this.handleremoveSocialMedia = this.handleremoveSocialMedia.bind(this)
        this.handleAddLogo = this.handleAddLogo.bind(this)
        this.handleRemoveLogo = this.handleRemoveLogo.bind(this)
        this.handleSaveToDraft = this.handleSaveToDraft.bind(this)
        this.handleSave = this.handleSave.bind(this)
        this.getMyLocation = this.getMyLocation.bind(this)
        this.handleMapChange = this.handleMapChange.bind(this)
        this.verifyPhoneNumber = this.verifyPhoneNumber.bind(this)
        this.verifyEmail = this.verifyEmail.bind(this)
    }

    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit') {
            this.getLoadData(urlParams.locationId)
        }
        else {
            this.getMyLocation()
        }
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
    getLoadData(locationId) {
        this.setState({
            is_loading: true
        }, () => {
            Axios.get(`/locations/ajax_load/${locationId}`)
                .then(response => {
                    this.setState({
                        ...this.state,
                        ...response.data
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
    getElementText(response, elementName) {
        return response.getElementsByTagName(elementName)[0].innerHTML;
    }
    handleSubmit(e) {
        e.preventDefault()
        this.handleSave(false)
    }
    handleSaveToDraft() {
        this.handleSave(true)
    }
    handleSave(isDraft) {
        let post_data = {
            is_draft: isDraft,
            _id: this.state._id,
            name: this.state.name,
            address: this.state.address,
            email: this.state.email,
            website: this.state.website,
            type: this.state.type,
            description: this.state.description,
            photos: this.state.photos,
            social_medias: this.state.social_medias,
            phone_numbers: this.state.phone_numbers,
        }
        let formData = new FormData();
        if (this.state.logo != null) {
            formData.append('logo', this.state.logo)
        }
        formData.append('post_data', JSON.stringify(post_data))
        Axios.post('/locations/save', formData)
            .then(response => {
                if (response.data.status == 'success') {
                    this.setState({
                        _id: response.data.data._id
                    }, () => {
                        confirmAlert({
                            title: response.data.option == 'created' ? "You created a location successfully!" : "You updated a location successfully!",
                            message: response.data.option == 'created' ? 'Do you want to create new location continue?' : "Do you want to stay in this page?",
                            buttons: [
                                {
                                    label: 'Yes',
                                    onClick: () => response.data.option == 'created' ? location.href = "/locations/create" : null
                                },
                                {
                                    label: 'No, Thanks',
                                    onClick: () => location.href = "/locations"
                                }
                            ]
                        });
                    })
                }
            })
    }
    async handleAddLogo(file) {
        /*   let formData = new FormData();
          formData.append('logo', file)
          Axios.post('/locations/logoUpload', formData)
          .then(response => {
              this.setState({logo: response.data})
          }) */
        const photoPreview = await this.readUploadedPhotoPreview(file)
        this.setState({ logo: file, logo_url: photoPreview })
    }
    handleRemoveLogo() {
        this.setState({ logo: null, logo_url: null })
    }
    handleChange(e) {
        this.setState({
            [e.target.name]: e.target.value
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
    handleRemovePhoto(index) {
        let { photos } = this.state
        let removeFile = photos[index]
        photos.splice(index, 1)
        this.setState({
            photos: photos
        })
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
    handleAddSocialMedia() {
        let { social_medias } = this.state
        social_medias.push({
            social_url: ''
        })
        this.setState({
            social_medias: social_medias
        })
    }
    handleUpdateSocialMedia(event, index) {
        let { social_medias } = this.state
        social_medias[index].social_url = event.target.value
        this.setState({
            social_medias: social_medias
        })
    }
    handleremoveSocialMedia(index) {
        let { social_medias } = this.state
        social_medias.splice(index, 1)
        this.setState({
            social_medias: social_medias
        })
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
    async handleAddFiles(files) {
        let photos = this.state.photos
        for (let i = 0; i < files.length; i++) {
            if (files[i].type.includes('image')) {
                const photoPreview = await this.readUploadedPhotoPreview(files[i])
                let photo_data = {
                    image_src: photoPreview,
                    file_data: files[i],
                    status: 'uploading',
                    upload_progress: 0,
                }
                photos.push(photo_data)
            }
        }
        this.setState({
            photos: photos
        }, () => {
            this.state.photos.map((listing_photo, index) => {
                if (listing_photo.upload_progress == 0) {
                    let self = this
                    let formData = new FormData();
                    formData.append('file', listing_photo.file_data);
                    Axios.post('/locations/uploadphoto', formData, {
                        onUploadProgress: function (progressEvent) {
                            let photos = self.state.photos
                            photos[index].upload_progress = (progressEvent.loaded / progressEvent.total) * 100
                            self.setState({
                                photos: photos
                            })
                            // Do whatever you want with the native progress event
                        },
                    })
                        .then(response => {
                            if (response.data.status == 'success') {
                                let photos = this.state.photos
                                photos[index].photo_data = response.data.result
                                photos[index].status = 'uploaded'
                                photos[index] = { ...photos[index], ...response.data.file }
                                // photos = {...photos, ...response.data.file}
                                this.setState({
                                    photos: photos
                                })

                            }
                        })
                }
            })
        })

    }

    async getMyLocation() {
        await navigator.geolocation.getCurrentPosition(position => {
            this.setState({
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            })
        })
    }

    handleMapChange(map_data) {


    }
    render() {
        return <form
            onSubmit={this.handleSubmit}
        >
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="intro-y col-span-12 lg:col-span-6 sm:col-span-6">
                    <div class="mt-5">
                        <label className="font-medium">{Lang().location.name}:</label>
                        <input type="text" className="input border w-full" name="name"
                            placeholder='Enter Location Name'
                            value={this.state.name} onChange={this.handleChange} />
                    </div>
                    <div class="mt-5">
                        <label className="font-medium">{Lang().location.address}:</label>
                        <Autocomplete
                            onPlaceSelected={(place) => {
                                this.setState({
                                    address: place.formatted_address,
                                    latitude: place.geometry.location.lat(),
                                    longitude: place.geometry.location.lng(),
                                })
                            }}
                            value={this.state.address}
                            name='address'
                            onChange={this.handleChange}
                            className='input border w-full'
                            types={[]}
                        />
                    </div>
                </div>
                <div className='intro-y col-span-12 lg:col-span-6 sm:col-span-6' style={{ height: 500 }}>
                    <GoogleMapReact
                        onChange={this.handleMapChange}
                        onClick={(data) => {
                            this.setState({
                                latitude: data.lat,
                                longitude: data.lng,
                            })
                            Geocode.fromLatLng(data.lat, data.lng).then(
                                response => {
                                    const address = response.results[0].formatted_address;
                                    this.setState({
                                        latitude: data.lat,
                                        longitude: data.lng,
                                        address: address
                                    })
                                },
                                error => {
                                    console.error(error);
                                }
                            );
                        }}
                        bootstrapURLKeys={{ key: GoogleMapApiKey }}
                        center={{
                            lat: this.state.latitude ? this.state.latitude : 0,
                            lng: this.state.longitude ? this.state.longitude : 0
                        }}
                        defaultZoom={11}
                    >
                        <Marker
                            lat={this.state.latitude ? this.state.latitude : 0}
                            lng={this.state.longitude ? this.state.longitude : 0}
                            text="My Marker"
                        />
                    </GoogleMapReact>
                </div>
            </div>
            <div className={'grid grid-cols-12 gap-6 mt-5'}>
                <div className={'intro-y col-span-12 lg:col-span-12 sm: col-span-12'}>
                <label className="font-medium">{Lang().location.phone}:</label>
                {
                    this.state.phone_numbers.map((phone_number, index) => {
                        return <div className="grid grid-cols-12 gap-3 mt-5" key={index}>
                            <div className="w-full col-span-6 sm:col-span-12 relative md:col-span-12 xxl:col-span-12">
                                <ReactPhoneInput
                                    inputClass={'input border w-full'}
                                    containerClass='react-tel-input col-md-12'
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
                                <span className="form-text text-danger pl-2">{Lang().location.invalid_phone_number}</span>
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
                    <label className="font-medium">{Lang().location.email}:</label>
                    <div className="relative">
                        <input type="text" className="input border w-full" placeholder='Enter Email' name="email"
                            onBlur={this.verifyEmail}
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
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().location.social_media}:</label>
                    {
                        this.state.social_medias.map((social_media, index) => {
                            return <div class="flex items-center mt-5">
                                <input type="text" className="input border w-full" placeholder="Enter Social Media" value={social_media.social_url} onChange={(e) => this.handleUpdateSocialMedia(e, index)} />
                                <button type="button" className="button bg-theme-6 text-white" onClick={() => this.handleremoveSocialMedia(index)}><i className="icon-cross" /></button>
                            </div>
                        })
                    }
                </div>
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 mt-1">
                    <button type="button" className="button bg-theme-4 text-white" onClick={this.handleAddSocialMedia}><i className="icon-plus3" /></button>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().location.website_address}:</label>
                    <input type="text" className="input border w-full" name="website" value={this.state.website} onChange={this.handleChange} />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().location.location_type}:</label>
                    <Select
                        value={this.state.select_type}
                        options={this.state.type_list.map((type, index) => {
                            return { value: type._id, label: type.type_name }
                        })}
                        isClearable={true}
                        onChange={(selectedOption) => {
                            this.setState({ type: selectedOption ? selectedOption.value : null, select_type: selectedOption }, () => {
                            })
                        }}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className="font-medium">{Lang().location.description}:</label>

                    <CKEditor
                        editor={ClassicEditor}
                        data={this.state.description}
                        onInit={editor => {
                            // You can store the "editor" and use when it is needed.
                        }}
                        onChange={(event, editor) => {
                            const data = editor.getData();
                            this.setState({
                                description: editor.getData()
                            })
                        }}
                        onBlur={editor => {

                        }}
                        onFocus={editor => {
                        }}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className='font-medium'>{Lang().location.photos}:</label>
                    <AttachmentForm
                        addFiles={this.handleAddFiles}
                        removeFile={this.handleRemovePhoto}
                        photos={this.state.photos}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <label className='font-medium'>{Lang().location.logo}:</label>
                    <Dropify
                        title={Lang().location.logo_upload}
                        subtitle={Lang().app.dropify_subtitle}
                        defaultImage={this.state.logo_url}
                        onRemove={this.handleRemoveLogo}
                        onChange={this.handleAddLogo} />
                </div>
            </div>

            <div className="grid grid-cols-12 gap-3 mt-5">
                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                    <div className="flex items-center">
                        <button type="submit" className="button flex items-center justify-center bg-theme-4 text-white mr-5">Save <i className="icon-floppy-disk ml-2" /></button>
                        <button type="button" onClick={this.handleSaveToDraft} className="button flex items-center justify-center bg-theme-6 text-white">{Lang().location.save_to_draft} <i className="icon-database4 ml-2" /></button>
                    </div>
                </div>
            </div>
        </form>
    }
}
