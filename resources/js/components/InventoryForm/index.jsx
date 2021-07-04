
import React, { Component } from 'react';
import AttachmentForm from './AttachmentForm';
import Axios from 'axios'
import RadioBox from '../../global_ui_components/RadioBox';
import countryList from 'react-select-country-list'
import CheckBox from '../../global_ui_components/CheckBox';
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Select from 'react-select';
import NumberFormat from 'react-number-format';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
const JSON_DATA = JSON.parse(document.getElementById('inventory_details').innerHTML)
import InputColor from 'react-input-color';
import Lang from "../../Lang/Lang";
import Autocomplete from 'react-google-autocomplete';
import {
    ComposableMap,
    ZoomableGroup,
    Geographies,
    Geography,
    Markers,
    Marker,
} from "react-simple-maps"
import { Motion, spring } from "react-motion"
import DatePicker from 'react-date-picker';

const wrapperStyles = {
    width: "100%",
    // maxWidth: 980,
    margin: "0 auto",
}

const cities = [
    { name: "Zurich", coordinates: [8.5417, 47.3769] },
    { name: "Singapore", coordinates: [103.8198, 1.3521] },
    { name: "San Francisco", coordinates: [-122.4194, 37.7749] },
    { name: "Sydney", coordinates: [151.2093, -33.8688] },
    { name: "Lagos", coordinates: [3.3792, 6.5244] },
    { name: "Buenos Aires", coordinates: [-58.3816, -34.6037] },
    { name: "Shanghai", coordinates: [121.4737, 31.2304] },
]

const color_list = [
    { value: "white", label: 'white' },
    { value: "black", label: 'black' },
    { value: "pink", label: 'pink' },
    { value: "purple", label: 'purple' },
    { value: "violet", label: 'violet' },
    { value: "indigo", label: 'indigo' },
    { value: "blue", label: 'blue' },
    { value: "teal", label: 'teal' },
    { value: "green", label: 'green' },
    { value: "orange", label: 'orange' },
    { value: "brown", label: 'brown' },
    { value: "grey", label: 'grey' },
    { value: "slate", label: 'slate' },
    { value: "beige", label: 'beige' },
    { value: "yellow", label: 'yellow' },
    { value: "red", label: 'red' },
]
const mileage_unit_options = [
    { label: "Kilometers", value: 'km' },
    { label: "Miles", value: 'mile' },
]
const steering_wheel_options = [
    { label: "Left", value: 'left' },
    { label: "Right", value: 'right' },
]

const conditions = [
    { label: "Very Good", value: 'very_good' },
    { label: "Good", value: 'good' },
    { label: "Average", value: 'average' },
    { label: "Bad", value: 'bad' },
    { label: "Very Bad", value: 'very_bad' },
]
export default class InventoryForm extends Component {
    constructor(props) {
        super(props)
        this.state = {
            // map data
            center: [0, 20],
            zoom: 1,
            // Inventory Details State
            id: null,
            photos: [],
            vehicle_type: null,
            make: null,
            model: null,
            generation: null,
            serie: null,
            trim: null,
            equipment: null,
            year: null,
            price: null,
            currency: null,
            negotiable: null,
            country: null,
            city: null,
            color: null,
            transmission: null,
            engine: null,
            fuel_type: null,
            body_type: null,
            location: null,
            status: null,
            finance: null,
            description: '',
            options: [],
            tags: [],
            mileage: null,
            latitude: null,
            longitude: null,
            mileage_unit: JSON_DATA['cur_mileage'],
            steering_wheel: null,
            chassis_number: null,
            reference: null,
            date_of_purchase: null,
            price_of_purchase: null,
            currency_of_purchase: null,
            number_of_seats: null,
            number_of_doors: null,
            cylinder: null,
            mechanical_condition: null,
            body_condition: null,
            // Form Params State
            currencies: JSON_DATA['currencies'],
            vehicle_types: JSON_DATA['vehicle_types'],
            fuel_types: JSON_DATA['fuel_types'],
            // body_types: JSON_DATA['body_types'],
            body_types: [
                { value: 'Bus' },
                { value: 'Cab Chassis' },
                { value: 'Convertible' },
                { value: 'Coupe' },
                { value: 'Hatch' },
                { value: 'Light Truck' },
                { value: 'People Mover' },
                { value: 'Sedan' },
                { value: 'SUV' },
                { value: 'Ute' },
                { value: 'Van' },
                { value: 'Wagon' }
            ],
            transmissions: JSON_DATA['transmissions'],
            makes: [],
            models: [],
            generations: [],
            series: [],
            trims: [],
            option_list: JSON_DATA['option_list'],
            equipments: [],
            locations: JSON_DATA['locations'],
            tag_list: JSON_DATA['tag_list'],
            status_list: JSON_DATA['status_list'],
            countries: countryList().getData(),

            // Page Params
            is_loading: false,
            select_vehicle_type: null,
            select_makes: null,
            select_model: null,
            select_generation: null,
            select_serie: null,
            select_trim: null,
            select_equipment: null,
            select_year: null,
            select_price: null,
            select_currency: JSON_DATA['cur_currency'],
            select_currency_of_purchase: JSON_DATA['cur_currency'],
            select_negotiable: null,
            select_country: null,
            select_city: null,
            select_color: null,
            select_transmission: null,
            select_engine: null,
            select_fuel_type: null,
            select_body_type: null,
            select_location: null,
            select_status: null,
            select_finance: null,
            select_mileage_unit: { label: JSON_DATA['cur_mileage']=='km' ? 'Kilometers' : 'Miles', value: JSON_DATA['cur_mileage']},
            select_steering_wheel: null,
            select_description: '',
            select_options: [],
            select_tags: [],
        }
        this.handleAddFiles = this.handleAddFiles.bind(this)
        this.handleRemovePhoto = this.handleRemovePhoto.bind(this)
        this.handleChangeInventoryForm = this.handleChangeInventoryForm.bind(this)
        this.getNextFormPrams = this.getNextFormPrams.bind(this)
        this.RenderYearSelect = this.RenderYearSelect.bind(this)
        this.handleChangeInventoryOptions = this.handleChangeInventoryOptions.bind(this)
        this.getLoadData = this.getLoadData.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.openSuccessModal = this.openSuccessModal.bind(this)
        this.handleSaveToDraft = this.handleSaveToDraft.bind(this)
        this.handleSave = this.handleSave.bind(this)
        this.handleZoomIn = this.handleZoomIn.bind(this)
        this.handleZoomOut = this.handleZoomOut.bind(this)
        this.handleCityClick = this.handleCityClick.bind(this)
        this.handleReset = this.handleReset.bind(this)
        this.handleCountrySelect = this.handleCountrySelect.bind(this)
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit') {
            this.getLoadData(urlParams.inventoryId)
        }
    }
    getLoadData(inventoryId) {
        this.setState({
            is_loading: true
        }, () => {
            Axios.get(`/inventories/ajax_load/${inventoryId}`)
                .then(response => {
                    this.setState({
                        ...response.data
                    }, () => {
                        if (this.state.city && this.state.longitude && this.state.latitude) {
                            this.handleCityClick({ name: this.state.city, coordinates: [this.state.longitude, this.state.latitude] })
                        }

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
    handleZoomIn() {
        this.setState({
            zoom: this.state.zoom * 2,
        })
    }
    handleZoomOut() {
        this.setState({
            zoom: this.state.zoom / 2,
        })
    }
    handleCityClick(city) {
        this.setState({
            zoom: 2,
            center: city.coordinates,
        })
    }
    handleReset() {
        this.setState({
            center: [0, 20],
            zoom: 1,
        })
    }
    handleCountrySelect(e) {
        this.setState({
            country: null
        }, () => {
            this.setState({
                country: e.properties.ISO_A2, city: '', latitude: null, longitude: null, select_country: { value: e.properties.ISO_A2 }
            })
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
                    Axios.post('/inventories/uploadphoto', formData, {
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
                            let plate_number = this.state.plate_number
                            if (response.data.status == 'success') {
                                let photos = this.state.photos
                                photos[index].photo_data = response.data.result
                                photos[index].status = 'uploaded'
                                photos[index].countries = response.data.country
                                photos[index].recorgnized_result = response.data.recorgnized_result
                                photos[index] = { ...photos[index], ...response.data.file }
                                // photos = {...photos, ...response.data.file}
                                if (response.data.api_result.results.length) {
                                    plate_number = response.data.api_result.results[0].plate
                                }
                                this.setState({
                                    photos: photos,
                                    plate_number: plate_number.toUpperCase()
                                })

                            }
                        })
                }
            })
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
    handleChangeInventoryForm(e) {
        let name = e.target.name
        let value = e.target.value
        if (name == 'vin') {
            value = value.toUpperCase()
        }
        this.setState({
            [name]: value
        }, () => {
            this.getNextFormPrams(name)
        })
    }
    handleChangeInventoryOptions(id, index) {
        let { options } = this.state
        if (options.includes(id)) {
            let optionIndex = this.state.options.indexOf(id)
            options.splice(optionIndex, 1)
        }
        else {
            options.push(id)
        }
        this.setState({
            options: options
        })

    }
    getNextFormPrams(name) {
        if (name == 'vehicle_type') {
            Axios.get(`/car2db/${this.state.vehicle_type}/makes`)
                .then(response => {
                    this.setState({
                        makes: response.data,
                        make: null,
                        select_make: null,

                        models: [],
                        model: null,
                        select_model: null,

                        generations: [],
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'make') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.make}/models`)
                .then(response => {
                    this.setState({
                        models: response.data,
                        model: null,
                        select_model: null,

                        generations: [],
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'model') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/generations`)
                .then(response => {
                    this.setState({
                        generations: response.data,
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'generation') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/${this.state.generation}/series`)
                .then(response => {
                    this.setState({
                        series: response.data,
                        serie: null,
                        select_serie: null,


                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'serie') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/${this.state.serie}/trims`)
                .then(response => {
                    this.setState({
                        trims: response.data,
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'trim') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.trim}/equipments`)
                .then(response => {
                    this.setState({
                        equipments: response.data,
                        equipment: null,
                        select_equipment: null
                    })
                })

        }
    }
    handleSaveToDraft() {
        this.handleSave(false)
    }
    handleSubmit(e) {
        e.preventDefault()
        this.handleSave(true)
    }
    handleSave(option) {
        let post_data = {
            id: this.state.id,
            photos: this.state.photos,
            latitude: this.state.latitude,
            longitude: this.state.longitude,
            vehicle_type: this.state.vehicle_type,
            make: this.state.make,
            model: this.state.model,
            generation: this.state.generation,
            serie: this.state.serie,
            trim: this.state.trim,
            equipment: this.state.equipment,
            year: this.state.year,
            price: this.state.price,
            currency: this.state.currency,
            negotiable: this.state.negotiable,
            country: this.state.country,
            city: this.state.city,
            color: this.state.color,
            transmission: this.state.transmission,
            engine: this.state.engine,
            mileage: this.state.mileage,
            vin: this.state.vin,
            plate_number: this.state.plate_number,
            chassis_number: this.state.chassis_number,
            fuel_type: this.state.fuel_type,
            body_type: this.state.body_type,
            location: this.state.location,
            status: this.state.status,
            finance: this.state.finance,
            description: this.state.description,
            options: this.state.options,
            tags: this.state.tags,
            reference: this.state.reference,
            date_of_purchase: this.state.date_of_purchase,
            price_of_purchase: this.state.price_of_purchase,
            currency_of_purchase: this.state.currency_of_purchase,
            number_of_seats: this.state.number_of_seats,
            number_of_doors: this.state.number_of_doors,
            cylinder: this.state.cylinder,
            mechanical_condition: this.state.mechanical_condition,
            body_condition: this.state.body_condition,
            draft: !option
        }
        Axios.post('/inventories/save', post_data)
            .then(response => {
                if (response.data.status == 'success') {


                    this.setState({
                        id: response.data.data.id
                    }, () => {
                        confirmAlert({
                            title: response.data.option == 'created' ? "You created a inventory successfully!" : "You updated a inventory successfully!",
                            message: response.data.option == 'created' ? 'Do you want to create new inventory continue?' : "Do you want to stay in this page?",
                            buttons: [
                                {
                                    label: 'Yes',
                                    onClick: () => response.data.option == 'created' ? location.href = "/inventories/create" : null
                                },
                                {
                                    label: 'No, Thanks',
                                    onClick: () => location.href = "/inventories/all"
                                }
                            ]
                        });
                    })


                }
            })
    }
    openSuccessModal(data) {
        if (data.option == 'created') {

        }
        else {

        }

    }
    RenderYearSelect(year) {
        let options = []
        for (let i = 1950; i < 2020; i++) {
            options.push(i)
        }
        return <Select menuContainerStyle={{ 'zIndex': 9999 }}
            value={this.state.select_year}
            options={options.map((year, index) => {
                return { value: year, label: year }
            })}
            isClearable={true}
            onChange={(selectedOption) => {
                this.setState({ year: selectedOption ? selectedOption.value : null, select_year: selectedOption }, () => {
                })
            }}
        />
    }
    render() {

        const countryFormat = ({ value, label }) => {
            return <div className='flex items-center px-1 py-1'>
                <img src={`https://www.countryflags.io/${value}/shiny/48.png`} className="mr-2"/>{countryList().getLabel(value)}
            </div>
        }
        const colorListFormat = ({ value, label }) => {
            return <div className='flex items-center px-1 py-1'>
                <span className={`w-5 h-5 rounded-full bg-${value} badge-pill`}>&nbsp;&nbsp;</span>&nbsp;{label}
            </div>
        }
        return (
            <form
                onSubmit={this.handleSubmit}
            >
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().inventories.vehicle_type}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            isClearable={true}
                            value={this.state.select_vehicle_type}
                            options={this.state.vehicle_types.map((vehicle_type, index) => {
                                return { value: vehicle_type.id_car_type, label: vehicle_type.name }
                            })}
                            onChange={(selectedOption) => {
                                this.setState({ vehicle_type: selectedOption ? selectedOption.value : null, select_vehicle_type: selectedOption }, () => {
                                    this.getNextFormPrams('vehicle_type')
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.make}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_make}
                            options={this.state.makes.map((make, index) => {
                                return { value: make.id_car_make, label: make.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type}
                            onChange={(selectedOption) => {
                                this.setState({ make: selectedOption ? selectedOption.value : null, select_make: selectedOption }, () => {
                                    this.getNextFormPrams('make')
                                })
                            }}
                        />

                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.make}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_model}
                            options={this.state.models.map((model, index) => {
                                return { value: model.id_car_model, label: model.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type || !this.state.makes.length || !this.state.make}
                            onChange={(selectedOption) => {
                                this.setState({ model: selectedOption ? selectedOption.value : null, select_model: selectedOption }, () => {
                                    this.getNextFormPrams('model')
                                })
                            }}
                            />
                    </div>
                    <div className="col-span-6 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label className="d-block font-weight-semibold">{Lang().inventories.generation}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_generation}
                            options={this.state.generations.map((generation, index) => {
                                return { value: generation.id_car_generation, label: generation.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type || !this.state.makes.length || !this.state.make || !this.state.models.length || !this.state.model}
                            onChange={(selectedOption) => {
                                this.setState({ generation: selectedOption ? selectedOption.value : null, select_generation: selectedOption }, () => {
                                    this.getNextFormPrams('generation')
                                })
                            }}
                        />

                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.serie}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_serie}
                            options={this.state.series.map((serie, index) => {
                                return { value: serie.id_car_serie, label: serie.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length || !this.state.generation}
                            onChange={(selectedOption) => {
                                this.setState({ serie: selectedOption ? selectedOption.value : null, select_serie: selectedOption }, () => {
                                    this.getNextFormPrams('serie')
                                })
                            }}
                        />

                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.trim}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_trim}
                            options={this.state.trims.map((trim, index) => {
                                return { value: trim.id_car_trim, label: trim.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length
                                || !this.state.generation || !this.state.series.length
                                || !this.state.serie}
                            onChange={(selectedOption) => {
                                this.setState({ trim: selectedOption ? selectedOption.value : null, select_trim: selectedOption }, () => {
                                    this.getNextFormPrams('trim')
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.equipment}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_equipment}
                            options={this.state.equipments.map((equipment, index) => {
                                return { value: equipment.id_car_equipment, label: equipment.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length
                                || !this.state.generation || !this.state.series.length
                                || !this.state.serie || !this.state.trims.length || !this.state.trim}
                            onChange={(selectedOption) => {
                                this.setState({ equipment: selectedOption ? selectedOption.value : null, select_equipment: selectedOption }, () => {
                                    this.getNextFormPrams('equipment')
                                })
                            }}
                        />

                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.year}:</label>
                        {
                            this.RenderYearSelect(this.state.year)
                        }
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.price}:</label>
                        <NumberFormat value={this.state.price} onValueChange={(values) => {
                                const { formattedValue, value } = values
                                this.setState({
                                    price: value
                                })
                            }} className={'input w-full border'} placeholder='Enter Price' thousandSeparator={true} />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.currency}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_currency}
                            options={this.state.currencies.map((currency, index) => {
                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                            })}
                            isClearable={true}

                            onChange={(selectedOption) => {
                                this.setState({ currency: selectedOption ? selectedOption.value : null, select_currency: selectedOption }, () => {
                                });
                            }}
                        />
                    </div>
                </div>
                
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.negotiable}:</label>
                        <div className="flex flex-col sm:flex-row mt-2">
                            <RadioBox
                                name='negotiable'
                                checked={this.state.negotiable == 'Yes'}
                                value='Yes'
                                label='Yes'
                                onChange={this.handleChangeInventoryForm}
                            />
                            <RadioBox
                                name='negotiable'
                                checked={this.state.negotiable == 'No'}
                                value='No'
                                label='No'
                                onChange={this.handleChangeInventoryForm}
                            />
                        </div>
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.finance}:</label>
                        <div className="flex flex-col sm:flex-row mt-2">
                            <RadioBox
                                name='finance'
                                checked={this.state.finance == 'Yes'}
                                value='Yes'
                                label='Yes'
                                onChange={this.handleChangeInventoryForm}
                            />
                            <RadioBox
                                name='finance'
                                checked={this.state.finance == 'No'}
                                value='No'
                                label='No'
                                onChange={this.handleChangeInventoryForm}
                            />
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                <label>{Lang().inventories.country}:</label>
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    value={this.state.select_country}
                                    options={this.state.countries.map((country, index) => {
                                        return { value: country.value, label: `${country.label}(${country.value})` }
                                    })}
                                    isClearable={true}
                                    formatOptionLabel={countryFormat}
                                    onChange={(selectedOption) => {
                                        this.setState({
                                            country: selectedOption ? selectedOption.value : null,
                                            select_country: selectedOption,
                                            city: '',
                                            latitude: null,
                                            longitude: null
                                        }, () => {
                                            this.handleReset()
                                        })
                                    }}
                                />
                            </div>
                        </div>
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                <label>{Lang().inventories.city}:</label>
                                <Autocomplete
                                    onPlaceSelected={(place) => {
                                        if (place.address_components) {
                                            let city = place.address_components[0] && place.address_components[0].short_name || ''
                                            this.setState({
                                                city: city,
                                                latitude: place.geometry.location.lat(),
                                                longitude: place.geometry.location.lng(),
                                            }, () => {
                                                this.handleCityClick({ name: this.state.city, coordinates: [this.state.longitude, this.state.latitude] })
                                            })
                                        }
                                    }}
                                    value={this.state.city || ''}
                                    name='city'
                                    onChange={this.handleChangeInventoryForm}
                                    className="input w-full border mt-2"
                                    // componentRestrictions={{country: this.state.country ? this.state.country.toLowerCase() : ''}}
                                    types={['(cities)']}
                                />
                            </div>
                        </div>
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <div style={wrapperStyles}>
                            {true && <Motion
                                defaultStyle={{
                                    zoom: 1,
                                    x: 0,
                                    y: 20,
                                }}
                                style={{
                                    zoom: spring(this.state.zoom, { stiffness: 210, damping: 20 }),
                                    x: spring(this.state.center[0], { stiffness: 210, damping: 20 }),
                                    y: spring(this.state.center[1], { stiffness: 210, damping: 20 }),
                                }}
                            >
                                {({ zoom, x, y }) => (
                                    <ComposableMap
                                        projectionConfig={{ scale: 205 }}
                                        width={980}
                                        height={551}
                                        style={{
                                            width: "100%",
                                            height: "auto",
                                        }}
                                    >
                                        <ZoomableGroup center={[x, y]} zoom={zoom}>
                                            <Geographies geography="/topojson-maps/world-110m.json"
                                                disableOptimization={true}>
                                                {(geographies, projection) => {

                                                    return geographies.map((geography, i) => {
                                                        return <Geography
                                                            key={i}
                                                            geography={geography}
                                                            projection={projection}
                                                            onClick={this.handleCountrySelect}
                                                            style={{
                                                                default: {
                                                                    fill: geography.properties.ISO_A2 == this.state.country ? "#7AC3E4" : "#ECEFF1",
                                                                    stroke: "#607D8B",
                                                                    strokeWidth: 0.75,
                                                                    outline: "none",
                                                                },
                                                                hover: {
                                                                    fill: "#CFD8DC",
                                                                    stroke: "#607D8B",
                                                                    strokeWidth: 0.75,
                                                                    outline: "none",
                                                                },
                                                                pressed: {
                                                                    fill: "#FF5722",
                                                                    stroke: "#607D8B",
                                                                    strokeWidth: 0.75,
                                                                    outline: "none",
                                                                },
                                                            }}
                                                        />
                                                    }
                                                    )
                                                }
                                                }
                                            </Geographies>
                                            <Markers>
                                                {
                                                    this.state.city && this.state.latitude && this.state.longitude ?
                                                        <Marker
                                                            marker={{ name: this.state.city, coordinates: [this.state.longitude, this.state.latitude] }}
                                                            onClick={this.handleCityClick}
                                                        >
                                                            <circle
                                                                cx={0}
                                                                cy={0}
                                                                r={6}
                                                                fill="#FF5722"
                                                                stroke="#DF3702"
                                                            />
                                                        </Marker> : null
                                                }

                                            </Markers>
                                        </ZoomableGroup>
                                    </ComposableMap>
                                )}
                            </Motion>
                            }

                        </div>
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().inventories.photos}:</label>
                        <AttachmentForm
                            photos={this.state.photos}
                            addFiles={this.handleAddFiles}
                            removeFile={this.handleRemovePhoto}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.color}:</label>
                        <div className="uniform-uploader">
                            <span className="m-0 p-0 w-100" >
                                <Select
                                    options={color_list}
                                    value={{ label: this.state.color ? this.state.color.charAt(0).toUpperCase() + this.state.color.slice(1) : '', value: this.state.color }}
                                    isClearable={true}
                                    formatOptionLabel={colorListFormat}
                                    onChange={(selectedOption) => {
                                        this.setState({ color: selectedOption ? selectedOption.value : '', select_color: selectedOption }, () => {
                                        })
                                    }}
                                />
                            </span>
                        </div>
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.transmission}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_transmission || ''}
                            options={this.state.transmissions.map((transmission, index) => {
                                return { value: transmission.id, label: `${transmission.transmission}` }
                            })}
                            isClearable={true}

                            onChange={(selectedOption) => {
                                this.setState({ transmission: selectedOption ? selectedOption.value : '', select_transmission: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.engine}:</label>
                        <input type="text" className="input w-full border" placeholder="Enter Engine" name="engine" value={this.state.engine || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.steering_wheel}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_steering_wheel || ''}
                            options={steering_wheel_options}
                            isClearable={true}
                            onChange={(selectedOption) => {
                                this.setState({ steering_wheel: selectedOption ? selectedOption.value : '', select_steering_wheel: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.vin}:</label>
                        <input type="text" className="input w-full border"
                            minLength={17}
                            maxLength={17}
                            placeholder="Enter VIN" name="vin" value={this.state.vin || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.plate_number}:</label>
                        <input type="text" className="input w-full border" placeholder="Enter Plate Number" name="plate_number" value={this.state.plate_number || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.chassis_number}:</label>
                        <input type="text" className="input w-full border" placeholder="Enter Chassis Number" name="chassis_number" value={this.state.chassis_number || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.number_of_seats}</label>
                        <input className="input w-full border" type="number" placeholder="Number of seats" name="number_of_seatch" value={this.state.number_of_seatch || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.number_of_doors}</label>
                        <input className="input w-full border" type="number" placeholder="Number of Doors" name="number_of_doors" value={this.state.number_of_doors || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                </div>


                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.cylinder}</label>
                        <input className="input w-full border" type="text" placeholder="Cylinder" name="cylinder" value={this.state.cylinder || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.mechanical_condition}</label>
                        <div>
                        <select className = 'input border mr-2 w-full' name = 'mechanical_condition' value = {this.state.mechanical_condition || ''} onChange = {this.handleChangeInventoryForm}>
                            <option value = "">{Lang().inventories.choose_condition}</option>
                            <option value = "very_good">{Lang().inventories.very_good}</option>
                            <option value = "good">{Lang().inventories.good}</option>
                            <option value = "average">{Lang().inventories.average}</option>
                            <option value = "bad">{Lang().inventories.bad}</option>
                            <option value = "very_bad">{Lang().inventories.very_bad}</option>
                        </select>
                        </div>
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.body_condition}</label>
                        <div>
                        <select className = 'input border mr-2 w-full' name = 'body_condition' value = {this.state.body_condition || ''} onChange = {this.handleChangeInventoryForm}>
                            <option value = "">{Lang().inventories.choose_condition}</option>
                            <option value = "very_good">{Lang().inventories.very_good}</option>
                            <option value = "good">{Lang().inventories.good}</option>
                            <option value = "average">{Lang().inventories.average}</option>
                            <option value = "bad">{Lang().inventories.bad}</option>
                            <option value = "very_bad">{Lang().inventories.very_bad}</option>
                        </select>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.mileage}:</label>
                        <NumberFormat
                            className='input w-full border'
                            placeholder='Enter Mileage'
                            thousandSeparator={true}
                            name="mileage" value={this.state.mileage}
                            onValueChange={(values) => {
                                const { formattedValue, value } = values
                                this.setState({
                                    mileage: value
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.unit}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_mileage_unit || ''}
                            options={mileage_unit_options}
                            isClearable={true}
                            onChange={(selectedOption) => {
                                this.setState({ mileage_unit: selectedOption ? selectedOption.value : '', select_mileage_unit: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.fuel_type}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_fuel_type || ''}
                            options={this.state.fuel_types.map((fuel_type, index) => {
                                return { value: fuel_type.value, label: `${fuel_type.value}` }
                            })}
                            isClearable={true}
                            onChange={(selectedOption) => {
                                this.setState({ fuel_type: selectedOption ? selectedOption.value : '', select_fuel_type: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().inventories.body_type}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_body_type || ''}
                            options={this.state.body_types.map((body_type, index) => {
                                return { value: body_type.value, label: `${body_type.value}` }
                            })}
                            isClearable={true}
                            onChange={(selectedOption) => {
                                this.setState({ body_type: selectedOption ? selectedOption.value : '', select_body_type: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().app.options}</label>
                        <div>
                            {
                                this.state.option_list.map((option, index) => {
                                    return <CheckBox
                                        checkbox_type='inline'
                                        key={index}
                                        checked={this.state.options.includes(option.id) || this.state.options.includes(`${option.id}`)}
                                        onChange={() => this.handleChangeInventoryOptions(option.id, index)}
                                        name='options[]'
                                        value={option.id}
                                        label={option.option_name}
                                    />
                                })
                            }
                        </div>
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().inventories.location}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_location || ''}
                            options={this.state.locations.map((location, index) => {
                                return { value: location.id, label: `${location.name}` }
                            })}
                            isClearable={true}
                            onChange={(selectedOption) => {
                                this.setState({
                                    location: selectedOption ? selectedOption.value : '',
                                    select_location: selectedOption
                                }, () => {
                                })
                            }}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                        <label>{Lang().app.description}:</label>
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
                        <label className="d-block font-weight-semibold">{Lang().inventories.reference}
                        </label>
                        <input className="input w-full border" name="reference" value={this.state.reference || ''} onChange={this.handleChangeInventoryForm} />
                    </div>
                </div>
                

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.date_of_purchase}</label>
                        <DatePicker
                            onChange={(date) => this.setState({ date_of_purchase: date })}
                            value={this.state.date_of_purchase}
                        />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>{Lang().inventories.price_of_purchase}</label>
                        <NumberFormat className='input w-full border'
                            placeholder='Enter Price'
                            thousandSeparator={true}
                            name="price_of_purchase" value={this.state.price_of_purchase}
                            onValueChange={(values) => {
                                const { formattedValue, value } = values
                                this.setState({
                                    price_of_purchase: value
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-12 sm:col-span-12 md:col-span-4 xxl:col-span-4">
                        <label>&nbsp;</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            options={this.state.currencies.map((currency, index) => {
                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                            })}
                            onChange={selectedOption => {
                                this.setState({ currency_of_purchase: selectedOption.value, select_currency_of_purchase: selectedOption })
                            }}
                            value={this.state.select_currency_of_purchase || ''}
                            placeholder='Currency'
                            isClearable={true}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-3 mt-5">
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().app.tags}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.tags || ''}
                            isMulti={true}
                            options={this.state.tag_list.map((tag, index) => {
                                return { value: tag.id, label: tag.tag_name }
                            })}
                            onChange={(selectedOption) => {
                                this.setState({ tags: selectedOption })
                            }}
                        />
                    </div>
                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                        <label>{Lang().app.status}:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            isSearchable={true}
                            value={this.state.status || ''}
                            options={this.state.status_list.map((status, index) => {
                                return { value: status.id, label: status.status_name }
                            })}
                            onChange={(selectedOption) => {
                                this.setState({ status: selectedOption })
                            }}
                        />
                    </div>
                </div>
                <div className="p-5">
                    <div className="preview flex flex-wrap">
                        <button type="submit" className="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                            <i data-feather="save" className="ml-2"></i>
                            {Lang().app.save}
                        </button>
                        <button type="button" onClick={this.handleSaveToDraft} className="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white">
                            <i data-feather="database" className="ml-2"></i>
                            Save To Draft
                        </button>
                    </div>
                </div>
            </form>
        );
    }
}
