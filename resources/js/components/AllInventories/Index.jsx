import React from 'react'
import InputRange from 'react-input-range';
import ReactPaginate from 'react-paginate';
import NumberFormat from 'react-number-format';
import 'react-input-range/lib/css/index.css'
import Axios from 'axios';
import Select from 'react-select';
import countryList from 'react-select-country-list'
import { confirmAlert } from 'react-confirm-alert'; // Import
import Lang from "../../Lang/Lang";
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)

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
]
const colorListFormat = ({ value, label }) => {
    return <div className='flex items-center px-1 py-1'>
        <span className={`w-5 h-5 rounded-full bg-${value} badge-pill`}>&nbsp;&nbsp;</span>&nbsp;{label}
    </div>
}
const countryFormat = ({ value, label }) => {
    return <div className='flex items-center px-1 py-1'>
        <img src={`https://www.countryflags.io/${value}/shiny/48.png`} className="mr-2"/>{countryList().getLabel(value)}
    </div>
}
export default class AllInventories extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            receivers: [],
            vehicle_types: [],
            makes: [],
            models: [],
            generations: [],
            series: [],
            trims: [],
            equipments: [],
            transmissions: [],
            price_range: { min: 0, max: 15000 },
            mileage_range: { min: 0, max: 1000 },
            countries: countryList().getData(),
            cities: [],
            view_mode: 'table',
            locations: [],
            data: [],
            last_page: 1,
            // filter params
            price_min: 0,
            price_max: 1000,
            price_value: 0,
            mileage_value: 0,
            mileage_min: 0,
            mileage_max: 1000,
            search_key: null,
            search_data: [],
            permission: JSON_DATA['permission'],

            filter_params: {}
        }
        this.getNextFormPrams = this.getNextFormPrams.bind(this)
        this.handleFilterInventories = this.handleFilterInventories.bind(this)
        this.handlePageClick = this.handlePageClick.bind(this)
        this.handleApplyFilter = this.handleApplyFilter.bind(this)
        this.searchInventories = this.searchInventories.bind(this);
    }
    componentDidMount() {
        Axios.get(`/car2db/vehicle_types`)
            .then(Response => {
                return Response.data
            }).then(data => {
                this.setState({
                    vehicle_types: data
                })
            })
        Axios.get(`/car2db/transmissions`)
            .then(Response => {
                return Response.data
            }).then(data => {
                this.setState({
                    transmissions: data
                })
            })
        Axios.get('/locations/all')
            .then(response => {
                this.setState({ locations: response.data })
            })
        this.handleFilterInventories()
        Axios.get('/inventories/params')
            .then(response => {
                this.setState({ ...this.state, ...response.data })
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
        else if (name == 'country') {
            let country = this.state.country
            Axios.get(`/inventories/cities/${country}`)
                .then(response => {
                    this.setState({ cities: response.data })
                })
        }

    }
    handlePageClick(data) {
        this.handleFilterInventories(data.selected + 1)
    }
    confirmRemove(profileImage, name, id) {
        confirmAlert({
            title: "Are you sure to remove this lead?",
            message: ``,
            buttons: [
                {
                    label: 'No',
                },
                {
                    label: 'Yes',
                    onClick: () => location.href = `/leads/delete/${id}`
                }
            ],
            customUI: ({ onClose }) => {
                return (
                    <div className="react-confirm-alert-body">
                        <h1>Are you sure to remove this inventory?</h1>
                        <div className="react-confirm-alert-info">
                            <img src={profileImage} alt="" width="100%"/>
                            <span>{name}</span>
                        </div>
                        <div className="react-confirm-alert-button-group">
                            <button onClick={onClose}>No</button>
                            <button
                                onClick={() => {
                                    location.href = `/inventories/delete/${id}`
                                }}
                            >
                                Yes, Delete it!
                            </button>
                        </div>
                    </div>
                );
            }
        });
    }
    handleApplyFilter() {
        let filter_params = {
            vehicle_type: this.state.vehicle_type,
            make: this.state.make,
            model: this.state.model,
            generation: this.state.generation,
            serie: this.state.serie,
            trim: this.state.trim,
            equipment: this.state.equipment,
            transmission: this.state.transmission,
            color: this.state.color,
            price_min: this.state.price_min,
            mileage_min: this.state.mileage_min,
            price_max: this.state.price_max,
            mileage_max: this.state.mileage_max,
            from_year: this.state.from_year,
            to_year: this.state.to_year,
            location: this.state.location,
            country: this.state.country,
            city: this.state.city
        }
        this.setState({
            filter_params: filter_params
        }, () => {
            this.handleFilterInventories(1)
        })
    }
    handleFilterInventories(page = 1) {
        let search_params = {
            ...this.state.filter_params,
            page: page
        }

        Axios.get('/inventories/filter', { params: search_params })
            .then((response => {
                this.setState({ ...this.state, ...response.data })
            }))
    }
    searchInventories(event) {
        let searchKey = event.target.value;
        this.setState({ search_key: searchKey }, () => {
        })
    }

    getYearOptions() {
        let options = []
        for (let i = 1950; i < 2020; i++) {
            options.push({ label: i, value: i })
        }
        return options
    }

    render() {
        return <div className='grid grid-cols-12 gap-3 mt-5'>

            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                <div className="accordion intro-y box">
                    <div className="p-5 accordion__pane border-b border-gray-200 pb-4">
                        <div className="flex flex-col sm:flex-row items-center">
                            <a href="#" className="accordion__pane__toggle font-medium block">
                                {Lang().inventories.search_filter}</a> 
                        </div>
                        <div className="accordion__pane__content mt-3 text-gray-700 leading-relaxed">
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                    <label>{Lang().inventories.vehicle_type}:</label>
                                    <Select
                                        options={
                                            this.state.vehicle_types.map((type, index) => {
                                                return { label: type.name, value: type.id_car_type }
                                            })
                                        }
                                        onChange={(selectedOption) => {
                                            this.setState({ vehicle_type: selectedOption ? selectedOption.value : null, select_vehicle_type: selectedOption }, () => {
                                                this.getNextFormPrams('vehicle_type')
                                            })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
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
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
                                    <label className="d-block font-weight-semibold">{Lang().inventories.model}:</label>
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

                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
                                    <label>{Lang().inventories.generation}:</label>
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
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
                                    <label className="d-block font-weight-semibold">{Lang().inventories.serie}:</label>
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
                            </div>

                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
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
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
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

                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
                                    <label>{Lang().inventories.transmission}:</label>
                                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                        value={this.state.select_transmission}
                                        options={this.state.transmissions.map((transmission, index) => {
                                            return { value: transmission.id, label: `${transmission.transmission}` }
                                        })}
                                        isClearable={true}

                                        onChange={(selectedOption) => {
                                            this.setState({ transmission: selectedOption ? selectedOption.value : null, select_transmission: selectedOption }, () => {
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 sm:col-span-3 md:col-span-3 xxl:col-span-3">
                                    <label>{Lang().inventories.color}:</label>

                                    <div className="uniform-uploader">
                                        <span className="m-0 p-0 w-100" >
                                            <Select
                                                options={color_list}
                                                value={{ label: this.state.color ? this.state.color.charAt(0).toUpperCase() + this.state.color.slice(1) : null, value: this.state.color }}
                                                isClearable={true}
                                                formatOptionLabel={colorListFormat}
                                                onChange={(selectedOption) => {
                                                    this.setState({ color: selectedOption ? selectedOption.value : null, select_color: selectedOption }, () => {
                                                    })
                                                }}
                                            />

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.country}:</label>
                                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                        value={this.state.select_country}
                                        options={this.state.countries.map((country, index) => {
                                            return { value: country.value, label: `${country.label}(${country.value})` }
                                        })}
                                        isClearable={true}
                                        formatOptionLabel={countryFormat}
                                        onChange={(selectedOption) => {
                                            this.setState({ country: selectedOption.value }, () => {
                                                this.getNextFormPrams('country')
                                            })

                                        }}
                                    />
                                </div>
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.city}:</label>
                                    <Select
                                        options={this.state.cities.map((city) => {
                                            return { label: city, value: city }
                                        })}
                                        onChange={(selectedOption) => {
                                            this.setState({ city: selectedOption.value })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.price}:</label>
                                    <InputRange
                                        draggableTrack
                                        value={this.state.price_value}
                                        maxValue={parseFloat(this.state.price_range.max)}
                                        minValue={parseFloat(this.state.price_range.min)}
                                        onChange={(value) => {
                                            this.setState({
                                                price_value: value
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.mileage}:</label>
                                    <InputRange
                                        draggableTrack
                                        value={this.state.mileage_value}
                                        maxValue={parseFloat(this.state.mileage_range.max)}
                                        minValue={parseFloat(this.state.mileage_range.min)}
                                        onChange={(value) => {
                                            this.setState({
                                                mileage_value: value
                                            })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.year}:</label>
                                    <div className="grid grid-cols-12 gap-3">
                                        <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                            <Select placeholder="From"
                                                options={this.getYearOptions()}
                                                onChange={(selectedOption) => {
                                                    this.setState({ from_year: selectedOption.value })
                                                }}
                                            />
                                        </div>
                                        <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                            <Select placeholder="To"
                                                options={this.getYearOptions()}
                                                onChange={(selectedOption) => {
                                                    this.setState({ to_year: selectedOption.value })
                                                }}
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                    <label>{Lang().inventories.location}:</label>
                                    <Select
                                        options={this.state.locations.map((location, index) => {
                                            return { label: location.name, value: location.id }
                                        })}
                                        onChange={(selectedOption) => {
                                            this.setState({ location: selectedOption.value })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="p-5">
                                <button className="button w-24 rounded-full mr-1 mb-2 bg-theme-1 text-white" onClick={() => this.handleApplyFilter(1)}>{Lang().inventories.filter}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                <div className="intro-y box">
                    <div className="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 className="font-medium text-base mr-auto">
                            Inventories
                        </h2>
                        <div className="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
                            <button type="button" onClick={() => { this.setState({ view_mode: 'grid' }) }} className={`button w-24 inline-block mr-1 mb-2 ${this.state.view_mode == 'grid' ? 'bg-theme-1 text-white' : 'text-gray-700 border'}`} >
                                <i className="icon-grid"></i>
                            </button>
                            <button type="button" onClick={() => { this.setState({ view_mode: 'table' }) }} className={`button w-24 inline-block mr-1 mb-2 ${this.state.view_mode == 'table' ? 'bg-theme-1 text-white' : 'text-gray-700 border'}`} >
                                <i className="icon-list"></i>
                            </button>
                        </div>
                    </div>
                    <div className="p-5 datatable-wrapper overflow-x-auto">
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-5 md:col-span-5 xxl:col-span-5">
                                <label>{Lang().inventories.search}: </label>
                                <input type="search" className="search__input input border placeholder-theme-1" name="q" onChange={this.searchInventories} />
                            </div>
                        </div>
                        {
                            this.state.view_mode == 'table' &&  <div className="grid grid-cols-12 gap-3 mt-5"><div className="col-span-12 sm:col-span-12 overflow-x-auto"><table className="table table-striped media-library dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" style={{ width: '100px' }}></th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" style={{ width: '100px' }}></th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc">{Lang().inventories.name}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc text-center">{Lang().inventories.price}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc text-center">{Lang().inventories.transmission}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc text-center">{Lang().inventories.leads}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc text-center" aria-label="Actions" style={{ width: '90px' }}>{Lang().inventories.action}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.data.map((data, index) => {
                                            let searchKey = null;
                                            if (this.state.search_key != null)
                                                searchKey = this.state.search_key.toLowerCase()
                                            if (data.inventory_name.toLowerCase().includes(searchKey) || searchKey == null) {
                                                let is_deleted = 0;
                                                if (typeof data.is_deleted !== 'undefined')
                                                    is_deleted = data.is_deleted;
                                                return <tr role="row" key={index}>
                                                    <td className="border-b sorting_1">
                                                        {
                                                            is_deleted == 0 && data.is_draft == 0 && <span className="text-xs text-theme-9 font-medium flex items-center"><i className="icon-checkmark2 mr-2" />{Lang().published}</span>
                                                        } 
                                                        {
                                                            is_deleted == 1 && <span className="text-xs text-theme-6 font-medium flex items-center"><i className="icon-cross mr-2" />{Lang().deleted}</span>
                                                        }
                                                        {
                                                            data.is_draft == 1 && <span className="text-xs text-theme-4 font-medium flex items-center"><i className="icon-database mr-2" />{Lang().draft}</span>
                                                        }
                                                        
                                                    </td>
                                                    <td className="border-b sorting_1 p-1">
                                                        <div className="flex sm:justify-center">
                                                            <a href="#" className="intro-x w-10 h-10 image-fit">
                                                                <img src={data.brand_logo} className="rounded-full" width={60} height={60} alt=""/>
                                                            </a>
                                                            <a href="#" className="intro-x w-10 h-10 image-fit">
                                                                <img src={data.featured_photo} alt="" className="rounded-full" width={80} />
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td className="border-b sorting_1"><a href="#">{data.inventory_name}</a>
                                                        <br />{data.country ? countryList().getLabel(data.country) : ""}/{data.city}</td>
                                                    <td className="border-b sorting_1 text-center"><a href="#">{data.price_with_currency}</a></td>
                                                    <td className="border-b sorting_1 text-center">{data.transmission_name}</td>
                                                    <td className="border-b sorting_1 text-center">{data.leads_count}</td>
                                                    <td className="border-b sorting_1">
                                                        <div className="flex sm:justify-center items-center">
                                                            <a href={`/inventory/share/${data.id}`} className="mr-2 p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md" data-popup="tooltip" data-trigger="hover" data-original-title="Share"><i className="icon-share3" /></a>
                                                            {
                                                                this.state.permission['write'] && <a href={`/inventories/edit/${data.id}`} className="mr-2 p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md" data-popup="tooltip" data-trigger="hover" data-original-title="Edit"><i className="icon-pencil" /></a>
                                                            }
                                                            
                                                            {
                                                                this.state.permission['read'] && <a href={`/inventories/view/${data.id}`} className="mr-2 p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md" data-popup="tooltip" data-trigger="hover" data-original-title="View"><i className="icon-eye" /></a>
                                                            }
                                                            
                                                            {
                                                                this.state.permission['delete'] && <a onClick={() => {this.confirmRemove(data.featured_photo, data.inventory_name, data.id)}} className="p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md mr-2" data-popup="tooltip" data-trigger="hover" data-original-title="View"><i className="icon-trash" /></a>
                                                            }
                                                        </div>
                                                    </td>
                                                </tr>
                                            }

                                        })
                                    }
                                </tbody>
                            </table></div></div>
                        }
                        {
                            this.state.view_mode == 'grid' && <div className="intro-y grid grid-cols-12 gap-6 mt-5">
                                {
                                    this.state.data.map((data, index) => {
                                        return <div className="intro-y col-span-12 md:col-span-4 xl:col-span-3 box" key={index}>
                                            <div className="p-5">
                                                <div className="h-40 xxl:h-56 image-fit">
                                                    <img alt="Car" className="rounded-md" src={data.featured_photo} />
                                                </div>
                                                <div className="block mt-5">
                                                    <div className="d-flex align-items-start flex-nowrap font-medium text-base">
                                                        {data.inventory_name}
                                                    </div>
                                                    <div className="text-gray-500 mt-2 text-xs">
                                                        {data.country ? countryList().getLabel(data.country) : ""}/{data.city}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    })
                                }
                            </div>
                        }
                        <div className="datatable-footer mt-4 flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                            <div className="font-medium text-base mr-auto" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing {this.state.from} to {this.state.to} of {this.state.total} inventories</div>
                            <div className="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0 dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <ReactPaginate
                                    previousLabel={'←'}
                                    nextLabel={'→'}
                                    breakLabel={'...'}
                                    breakClassName={'my-auto'}
                                    pageCount={this.state.last_page}
                                    marginPagesDisplayed={2}
                                    pageRangeDisplayed={5}
                                    onPageChange={this.handlePageClick}
                                    containerClassName={'pagination'}
                                    pageLinkClassName="paginate_button"
                                    nextLinkClassName="paginate_button"
                                    previousLinkClassName="paginate_button"
                                    subContainerClassName={'pages pagination'}
                                    activeClassName={'active'}
                                />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div >
    }
}
