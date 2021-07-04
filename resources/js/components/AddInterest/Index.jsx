import React from 'react'
import countryList from 'react-select-country-list'
import Select from 'react-select';
import Axios from 'axios';
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import NumberFormat from 'react-number-format';
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
const channel_list = [
    { value: 'Web', label: 'Web', icon: 'fab fa-chrome' },
    { value: 'Mobile', label: 'Mobile', icon: 'fas fa-mobile-alt' },
    { value: 'Sms', label: 'Sms', icon: 'mi-textsms' },
    { value: 'Email', label: 'Email', icon: 'far fa-envelope' },
]
const mileage_unit_options = [
    { label: "Kilometers", value: 'km' },
    { label: "Miles", value: 'mile' },
]
const colorListFormat = ({ value, label }) => {
    return <div className='flex items-center px-1 py-1'>
        <span className={`w-5 h-5 rounded-full bg-${value} badge-pill`}>&nbsp;&nbsp;</span>&nbsp;{label}
    </div>
}
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

const looking_to_options = [
    { label: 'Day(s)', value: 'Days' },
    { label: 'Week(s)', value: 'Weeks' },
    { label: 'Month(s)', value: 'Months' },
]
const channelFormat = ({ value, label, icon }) => {
    return <div className='dropdown-item p-0'>
        <i className={icon}></i>{label}
    </div>
}
const steering_wheel_options = [
    { label: "Left", value: 'left' },
    { label: "Right", value: 'right' },
]
export default class AddInterestForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            interest: {
                price: null,
                inventory: null,
                notes: null,
                user: null,
                location: null,
                currency: null,
                choose_item: 'inventory',
            },
            makes: [],
            models: [],
            generations: [],
            series: [],
            trims: [],
            equipments: [],
            price_range: { min: 0, max: 50000 },
            countries: countryList().getData(),
            currencies: JSON_DATA['currencies'],
            lead_details: JSON_DATA['lead'],
            inventories: JSON_DATA['inventories'],
            locations: JSON_DATA['locations'],
            users: JSON_DATA['users'],
            vehicle_types: JSON_DATA['vehicle_types'],
            fuel_types: JSON_DATA['fuel_types'],
            body_types: JSON_DATA['body_types'],
            transmissions: JSON_DATA['transmissions'],
            select_currency: JSON_DATA['cur_currency'],
            select_mileage_unit: mileage_unit_options[0]
        }
        this.handleUpdateInterest = this.handleUpdateInterest.bind(this)
        this.getNextFormPrams = this.getNextFormPrams.bind(this)
        this.handleAddInterest = this.handleAddInterest.bind(this)
    }
    componentDidMount() {
        let self = this
        $('#add_interests_modal').on('show.bs.modal', function () {
            // do something…
            let leadId = $(this).attr('data-id')
            if (leadId) {
                Axios.get(`/leads/interests/load_edit_detail/${leadId}`)
                    .then(response => {
                        self.setState({
                            ...self.state,
                            id: response.data.id,
                            interest: {
                                price: null,
                                inventory: response.data.inventory_id,
                                notes: null,
                                user: null,
                                location: null,
                                currency: null,
                                choose_item: response.data.item_option,
                            },
                            makes: [],
                            models: [],
                            generations: [],
                            series: [],
                            trims: [],
                            equipments: [],
                            ...response.data
                        })
                    })
            }
            else {
                self.setState({
                    ...self.state,
                    interest: {
                        price: null,
                        inventory: null,
                        notes: null,
                        user: null,
                        location: null,
                        currency: null,
                        choose_item: 'inventory',
                    },
                    makes: [],
                    models: [],
                    generations: [],
                    series: [],
                    trims: [],
                    equipments: [],
                    make: null,
                    model: null,
                    generation: null,
                    serie: null,
                    trim: null,
                    equipment: null,
                    color: null,
                    engine: null,
                    steering_wheel: null,
                    mileage_from: null,
                    mileage_to: null,
                    mileage_unit: null,
                    price_from: null,
                    price_to: null,
                    price_currency: null,
                    looking_to: null,
                    looking_to_option: null,
                })
            }
        })
        $('#add_interests_modal').on('hidden.bs.modal', function (e) {
            // do something...
            $(this).attr('data-id', null)
        })
    }
    handleUpdateInterest(value, field) {
        let interest = this.state.interest
        interest[field] = value
        this.setState({
            interest: interest,
            [field]: value
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
    handleAddInterest() {
        let post_data = {
            ...this.state.interest,
            id: this.state.id,
            make: this.state.make,
            model: this.state.model,
            generation: this.state.generation,
            serie: this.state.serie,
            trim: this.state.trim,
            equipment: this.state.equipment,
            color: this.state.color,
            engine: this.state.engine,
            steering_wheel: this.state.steering_wheel,
            mileage_from: this.state.mileage_from,
            mileage_to: this.state.mileage_to,
            mileage_unit: this.state.mileage_unit,
            price_from: this.state.price_from,
            price_to: this.state.price_to,
            price_currency: this.state.price_currency,
            looking_to: this.state.looking_to,
            looking_to_option: this.state.looking_to_option,
        }
        Axios.post(`/leads/interests/add/${this.state.lead_details.id}`, post_data)
            .then(response => {
                $('#add_interests_modal').attr('data-id', null);
                $('.btn-close-modal').click();
                $("#interests_list").load($("#interests_list").attr('data-url'));
            })
    }
    render() {
        let { interest } = this.state
        return <>
            <div className="text-center">
                <h3 className="font-medium text-theme-4">Add Interests</h3>
            </div>
            <div className="form mt-5" >
                <div className="">
                    <label className="font-weight-semibold">Interested Item:</label>
                    
                    <div className="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start">
                        <a data-toggle="tab" 
                            data-target="#from_inventory"
                            className={`py-4 sm:mr-8 cursor-pointer ${this.state.interest.choose_item == 'inventory' ? 'active' : ''}`}
                            onClick={() => this.handleUpdateInterest('inventory', 'choose_item')}>
                                Vehicle in Inventory
                        </a>
                        <a data-toggle="tab" 
                            data-target="#from_anothers"
                            className={`py-4 sm:mr-8 cursor-pointer ${this.state.interest.choose_item == 'another' ? 'active' : ''}`}
                            onClick={() => this.handleUpdateInterest('another', 'choose_item')}>
                                Vehicle Not in Inventory
                        </a>
                    </div>

                    <div className="introy-y tab-content mt-5">
                        <div className={`tab-content__pane ${this.state.interest.choose_item == 'inventory' ? 'active' : ''}`} id="from_inventory">
                            <Select
                                options={
                                    this.state.inventories.map((inventory, index) => {
                                        return {
                                            value: inventory.id, inventory: inventory, label: inventory.make_details.name + ' ' + inventory.model_details.name + " " + inventory.year
                                        }
                                    })
                                }
                                value={this.state.select_inventory}
                                onChange={selectedOption => {
                                    this.setState({ select_inventory: selectedOption })
                                    this.handleUpdateInterest(selectedOption.value, 'inventory')
                                }}
                                components={{ Option: CustomOption }}
                                placeholder='Choose Inventory' />
                        </div>
                        <div className={`tab-content__pane  ${this.state.interest.choose_item == 'another' ? 'active' : ''}`} id="from_anothers">
                            <div className="mt-5">
                                <label className="font-medium">Vehicle Type:</label>
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    isClearable={true}
                                    value={this.state.select_vehicle_type}
                                    options={this.state.vehicle_types.map((vehicle_type, index) => {
                                        return { value: vehicle_type.id_car_type, label: vehicle_type.name }
                                    })}
                                    onChange={(selectedOption) => {
                                        this.setState({ vehicle_type: selectedOption ? selectedOption.value : null, select_vehicle_type: selectedOption }, () => {
                                            this.getNextFormPrams('vehicle_type')
                                            this.handleUpdateInterest(selectedOption.value, 'vehicle_type')
                                        })
                                    }}
                                />
                            </div>

                            <div className='grid grid-cols-12 gap-6 mt-5'>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Make:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'make')
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Model:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'model')
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Generation:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'generation')
                                            })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className='grid grid-cols-12 gap-6 mt-5'>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Serie:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'serie')
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Trim:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'trim')
                                            })
                                        }}
                                    />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Equipment:</label>
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
                                                this.handleUpdateInterest(selectedOption.value, 'equipment')
                                            })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className="mt-5">
                                <label className="font-medium">Color:</label>
                                <Select
                                    options={color_list}
                                    value={{ label: this.state.color ? this.state.color.charAt(0).toUpperCase() + this.state.color.slice(1) : null, value: this.state.color }}
                                    isClearable={true}
                                    formatOptionLabel={colorListFormat}
                                    onChange={(selectedOption) => {
                                        this.setState({ color: selectedOption ? selectedOption.value : null, select_color: selectedOption }, () => {
                                            this.handleUpdateInterest(selectedOption.value, 'color')
                                        })
                                    }}
                                />
                            </div>
                            <div className="mt-5">
                                <label className="font-medium">Transmission:</label>
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    value={this.state.select_transmission}
                                    options={this.state.transmissions.map((transmission, index) => {
                                        return { value: transmission.id, label: `${transmission.transmission}` }
                                    })}
                                    isClearable={true}

                                    onChange={(selectedOption) => {
                                        this.setState({ transmission: selectedOption ? selectedOption.value : null, select_transmission: selectedOption }, () => {
                                            this.handleUpdateInterest(selectedOption.value, 'transmission')
                                        })
                                    }}
                                />
                            </div>
                            
                            <div className='grid grid-cols-12 gap-6 mt-5'>
                                <div className="col-span-12 lg:col-span-12 xxl:col-span-12">
                                    <label className="font-medium">Steering Wheel:</label>
                                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                        value={this.state.select_steering_wheel}
                                        options={steering_wheel_options}
                                        isClearable={true}
                                        onChange={(selectedOption) => {
                                            this.setState({ steering_wheel: selectedOption ? selectedOption.value : null, select_steering_wheel: selectedOption }, () => {
                                                this.handleUpdateInterest(selectedOption.value, 'steering_wheel')
                                            })
                                        }}
                                    />
                                </div>
                            </div>
                            <div className='grid grid-cols-12 gap-6 mt-5'>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Mileage From:</label>

                                    <NumberFormat value={this.state.mileage_from} onValueChange={(values) => {
                                        const { formattedValue, value } = values
                                        this.handleUpdateInterest(value, 'mileage_from')
                                    }} className={'input border w-full'} placeholder='Enter Mileage' thousandSeparator={true} />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">To:</label>

                                    <NumberFormat value={this.state.mileage_to} onValueChange={(values) => {
                                        const { formattedValue, value } = values
                                        this.handleUpdateInterest(value, 'mileage_to')
                                    }} className={'input border w-full'} placeholder='Enter Mileage' thousandSeparator={true} />
                                </div>
                                <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                                    <label className="font-medium">Unit:</label>
                                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                        options={mileage_unit_options}
                                        isClearable={true}
                                        value={this.state.select_mileage_unit}
                                        onChange={(selectedOption) => {
                                            this.handleUpdateInterest(selectedOption.value, 'mileage_unit')
                                        }}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* price range   */}
                <div className='grid grid-cols-12 gap-6 mt-5'>
                    <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                        <label className="font-medium">Price from:</label>
                        <NumberFormat value={this.state.price_from ? this.state.price_from : ''} onValueChange={(values) => {
                            const { formattedValue, value } = values
                            this.handleUpdateInterest(value, 'price_from')
                        }} className={'input border w-full'} placeholder='Enter Min Price' thousandSeparator={true} />
                    </div>
                    <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                        <label className="font-medium">to:</label>

                        <NumberFormat value={this.state.price_to ? this.state.price_to : ''} onValueChange={(values) => {
                            const { formattedValue, value } = values
                            this.handleUpdateInterest(value, 'price_to')
                        }} className={'input border w-full'} placeholder='Enter Price' thousandSeparator={true} />

                    </div>
                    <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                        <label className="font-medium">Currency:</label>
                        <Select
                            options={this.state.currencies.map((currency, index) => {
                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                            })}
                            value={this.state.select_currency}
                            onChange={(selectedOption) => {
                                this.setState({ select_currency: selectedOption })
                                this.handleUpdateInterest(selectedOption.value, 'price_currency')
                            }} />
                    </div>
                </div>
                <div className='grid grid-cols-12 gap-6 mt-5'>
                    <div className="col-span-12 lg:col-span-8 xxl:col-span-8">
                        <label className="font-medium">Looking to purchase in the next:</label>

                        <NumberFormat value={this.state.looking_to ? this.state.looking_to : ''} onValueChange={(values) => {
                            const { formattedValue, value } = values
                            this.handleUpdateInterest(value, 'looking_to')
                        }} className={'input border w-full'} placeholder='Enter Max Price' thousandSeparator={true} />

                    </div>
                    <div className="col-span-12 lg:col-span-4 xxl:col-span-4">
                        <label className="font-medium">&nbsp;</label>
                        <Select
                            onChange={(selectedOption) => {
                                this.handleUpdateInterest(selectedOption.value, 'looking_to_option')
                            }}
                            value={this.state.looking_to_option ? { label: this.state.looking_to_option, value: this.state.looking_to_option } : null}
                            options={looking_to_options} />
                    </div>
                </div>
                {/* channel  */}
                <div className='mt-5'>
                    <label className='font-medium'>Channel</label>
                    <Select
                        options={channel_list}
                        formatOptionLabel={channelFormat}
                        onChange={(selectedOption) => {
                            this.handleUpdateInterest(selectedOption.value, 'channel')
                        }}
                    />
                </div>
                {/* notes */}
                <div className='mt-5'>
                    <label className="font-medium">Notes:</label>
                    <CKEditor

                        editor={ClassicEditor}
                        data={interest.notes}
                        onInit={editor => {
                        }}
                        onChange={(event, editor) => {
                            let notes = editor.getData()
                            this.handleUpdateInterest(notes, 'notes')
                        }}
                        onBlur={editor => {

                        }}
                        onFocus={editor => {
                        }}
                    />
                </div>
            </div>

            <div className="mt-5 flex items-center">
                <button type="button" className="button bg-theme-6 text-white mr-5 btn-close-modal" data-dismiss="modal">Close</button>
                <button type="button" className="button bg-theme-4 text-white" onClick={this.handleAddInterest}>Save changes</button>
            </div>
        </>
    }
}
