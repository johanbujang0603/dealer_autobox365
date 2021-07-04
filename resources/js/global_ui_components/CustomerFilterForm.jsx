import React from 'react'
import Axios from 'axios'
import Select, { createFilter } from 'react-select';
import DateRangePicker from '@wojtekmaj/react-daterange-picker';
import countryList from 'react-select-country-list'

const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)

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

export default class CustomerFilterForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            open_inventory_dropdown: false,
            inventory: null,
            daterange: null,
            inventories: JSON_DATA['inventories'],
            customers: []
        }
        this.handleFilter = this.handleFilter.bind(this)
        this.handleRemoveCustomer = this.handleRemoveCustomer.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
    }
    componentDidMount() {

    }
    handleFilter() {
        let filter_params = {
            inventory: this.state.inventory,
            date_range: this.state.daterange
        }
        let self = this
        Axios.post('/customers/filter', filter_params)
            .then(Response => { return Response.data })
            .then(result => {
                self.setState({ customers: result })
                console.log(result)
            })
    }
    handleRemoveCustomer(index) {
        let { customers } = this.state
        customers.splice(index, 1)
        this.setState({ customers: customers })
    }
    getCustomers() {
        return this.state.customers.map((customer) => {
            return customer._id
        })
    }
    render() {
        return <>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-5 lg:col-span-5">
                    <label>Purchased Inventory:</label>
                    <Select
                        isClearable={true}
                        filterOption={createFilter({ ignoreAccents: false })}
                        options={
                            this.state.inventories.map((inventory, index) => {
                                return {
                                    value: inventory.id, inventory: inventory, label: inventory.make_details.name + ' ' + inventory.model_details.name + " " + inventory.year
                                }
                            })
                        }
                        onChange={selectedOption => {

                            this.setState({ open_inventory_dropdown: false, inventory: selectedOption })
                        }}
                        value={this.state.inventory}
                        onBlur={() => {
                            this.setState({ open_inventory_dropdown: false })
                        }}
                        filterOption={createFilter({ ignoreAccents: false })}
                        formatOptionLabel={inventoryFormat}
                        onInputChange={(query, { action }) => {
                            console.log(query)
                            if (query != '' && action === "input-change") {
                                this.setState({ open_inventory_dropdown: true });
                            }
                            else if (query === '') {
                                this.setState({ open_inventory_dropdown: false });
                            }
                        }}
                        menuIsOpen={this.state.open_inventory_dropdown}
                        // formatOptionLabel={inventoryFormat}
                        components={{ Option: CustomOption }}
                        placeholder='Choose Inventory' />
                </div>
                <div className="col-span-5 lg:col-span-5">
                    <label>Purchaed in:</label>
                    <DateRangePicker
                        className='input w-full'
                        onChange={(date) => {
                            console.log(date)
                            this.setState({ daterange: date })
                        }}
                        value={this.state.daterange}
                    />
                </div>
                <div className="col-span-2 lg:col-span-2">
                    <label className="mr-2 font-medium">Action</label>
                    <div>
                        <button className='button bg-theme-4 text-white' onClick={this.handleFilter}>Filter</button>
                    </div>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <ul>
                        <li className="bg-theme-2 font-medium p-2">Customers</li>
                        {
                            this.state.customers.map((customer, index) => {
                                return <li key={index}>
                                    <a href="javascript:;" className="media">
                                        <div className="mr-3 align-self-center text-nowrap">
                                            <i className="icon-trash" onClick={() => this.handleRemoveCustomer(index)} /></div>
                                        <div className="mr-3">
                                            <img src={customer.profile_image_src} className="rounded-circle" width={40} height={40} alt="" />
                                        </div>
                                        <div className="media-body">
                                            <div className="media-title font-medium">{customer.name}</div>
                                            <span className="text-muted">
                                                <div>
                                                    <img src={`https://www.countryflags.io/${customer.country_base_residence}/shiny/24.png`} />
                                                    {countryList().getLabel(customer.country_base_residence)}
                                                </div>
                                            </span>
                                        </div>

                                    </a>
                                </li>
                            })
                        }
                    </ul>
                </div>
            </div>
        </>
    }
}
