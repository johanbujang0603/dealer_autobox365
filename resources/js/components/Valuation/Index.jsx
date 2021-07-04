import React from 'react'
import Select from 'react-select';
import InputRange from 'react-input-range';
import ReactPaginate from 'react-paginate';
import NumberFormat from 'react-number-format';
import 'react-input-range/lib/css/index.css'
import Lang from "../../Lang/Lang";
import Axios from 'axios';
export default class Valuation extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            current_page: null,
            currency_symbol: '$',
            data: [],
            first_page_url: null,
            from: null,
            last_page: null,
            last_page_url: null,
            next_page_url: null,
            path: null,
            per_page: null,
            prev_page_url: null,
            to: null,
            total: null,
            view_mode: 'table',
            filter_params: {
                makes: [],
                models: [],
                countries: [],
                cities: [],
                colors: [],
                years: [],
                mileage_max: 0,
                mileage_min: 100,
                fuel_types: [],
                transmissions: []
            },
            selectedMake: null,
            selectedModel: null
        }
        this.handleSearch = this.handleSearch.bind(this)
        this.handleMakeChange = this.handleMakeChange.bind(this)
        this.handleCountryChange = this.handleCountryChange.bind(this)
        this.handlePageClick = this.handlePageClick.bind(this)
    }
    componentDidMount() {
        Axios.get('/valuation/filter_params')
            .then(response => {
                this.setState({ mileage_min: response.data.mileage_min, mileage_max: response.data.mileage_max, filter_params: { ...response.data } })
            })
        this.handleSearch()
    }
    handlePageClick(data) {
        let selected = data.selected;
        // let offset = Math.ceil(selected * this.props.perPage);

        this.handleSearch(selected + 1)
    };
    handleSearch(page_number = null) {
        let search_filter = {
            make: this.state.selectedMake ? this.state.selectedMake.value : null,
            model: this.state.selectedModel ? this.state.selectedModel.value : null,
            country: this.state.selectedCountry ? this.state.selectedCountry.value : null,
            city: this.state.selectedCity ? this.state.selectedCity.value : null,
            color: this.state.selectedColor ? this.state.selectedColor.value : null,
            year: this.state.selectedYear ? this.state.selectedYear.value : null,
            fuel_type: this.state.selectedFuelType ? this.state.selectedFuelType.value : null,
            transmission: this.state.selectedTransmission ? this.state.selectedTransmission.value : null,
            mileage_min: this.state.mileage_min ? this.state.mileage_min : null,
            mileage_max: this.state.mileage_max ? this.state.mileage_max : null,
            page: page_number
        }
        Axios.get('/valuation/search?' + this.serialize(search_filter))
            .then(response => {
                this.setState({
                    current_page: response.data.current_page,
                    currency_symbol: response.data.currency_symbol,
                    data: response.data.data,
                    first_page_url: response.data.first_page_url,
                    from: response.data.from,
                    last_page: response.data.last_page,
                    last_page_url: response.data.last_page_url,
                    next_page_url: response.data.next_page_url,
                    path: response.data.path,
                    per_page: response.data.per_page,
                    prev_page_url: response.data.prev_page_url,
                    to: response.data.to,
                    total: response.data.total,
                    avg_price: response.data.avg_price,
                    maximum_price: response.data.maximum_price,
                    median_price: response.data.median_price,
                    minimum_price: response.data.minimum_price,
                })
            })
    }
    serialize(obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        return str.join("&");
    }
    handleMakeChange(selectedOption) {
        let make = selectedOption ? selectedOption.value : null
        Axios.get(`/valuation/${make}/models`)
            .then(response => {
                this.setState({ selectedMake: selectedOption, filter_params: { ...this.state.filter_params, models: response.data } })
            })
    }
    handleCountryChange() {
        Axios.get(`/valuation/${this.state.selectedCountry ? this.state.selectedCountry.value : 'null'}/cities`)
            .then(response => {
                this.setState({
                    selectedCity: null,
                    filter_params: { ...this.state.filter_params, cities: response.data }
                })
            })
    }
    render() {
        return <div className='grid grid-cols-12 gap-3 mt-5'>
            {/* Top filter component */}
            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                <div className="accordion intro-y box">
                    <div className="p-5 accordion__pane border-b border-gray-200 pb-4">
                        <div className="flex flex-col sm:flex-row items-center">
                            <a href="#" className="accordion__pane__toggle font-medium block">
                                {Lang().inventories.search_filter}</a> 
                        </div>
                        <div className="accordion__pane__content mt-3 text-gray-700 leading-relaxed">
                            <form action="#" onSubmit={(e) => { e.preventDefault(), this.handleSearch() }}>
                                <div className="grid grid-cols-12 gap-3 mt-5">
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.make}</label>
                                        <Select placeholder={'Choose Make'}
                                            isClearable
                                            options={this.state.filter_params.makes.map(make => {
                                                return { label: make, value: make }
                                            })}
                                            value={this.state.selectedMake}
                                            onChange={this.handleMakeChange}
                                        />
                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.model}</label>
                                        <Select placeholder={'Choose Model'} isClearable
                                            value={this.state.selectedModel}
                                            onChange={(selectedOption) => this.setState({ selectedModel: selectedOption })}
                                            options={this.state.filter_params.models.map(model => {
                                                return { label: model, value: model }
                                            })} />

                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.country}</label>
                                        <Select placeholder={'Choose Country'} isClearable
                                            value={this.state.selectedCountry}
                                            options={this.state.filter_params.countries.map(country => {
                                                return { label: country, value: country }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedCountry: selectedOption }, () => {
                                                this.handleCountryChange()
                                            })} />
                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.city}</label>
                                        <Select placeholder={'Choose City'} isClearable
                                            value={this.state.selectedCity}
                                            options={this.state.filter_params.cities.map(city => {
                                                return { label: city, value: city }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedCity: selectedOption })}
                                        />
                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.color}</label>
                                        <Select placeholder={'Choose Color'} isClearable
                                            value={this.state.selectedColor}

                                            options={this.state.filter_params.colors.map(color => {
                                                return { label: color, value: color }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedColor: selectedOption })}
                                        />
                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-4 xxl:col-span-2">
                                        <label>{Lang().inventories.year}</label>
                                        <Select placeholder={'Choose Year'} isClearable
                                            value={this.state.selectedYear}
                                            options={this.state.filter_params.years.map(year => {
                                                return { label: year, value: year }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedYear: selectedOption })}
                                        />
                                    </div>
                                </div>
                                <div className="grid grid-cols-12 gap-3 mt-5">
                                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                        <label>{Lang().inventories.mileage}(km)</label>
                                        {/* <input type="text" className="form-control ion-height-helper" id="ion-range" data-fouc /> */}
                                        <InputRange
                                            value={{ min: this.state.mileage_min, max: this.state.mileage_max }}
                                            maxValue={this.state.filter_params.mileage_max}
                                            minValue={this.state.filter_params.mileage_min}
                                            onChange={(value) => {
                                                this.setState({
                                                    mileage_min: value.min, mileage_max: value.max
                                                })
                                            }}
                                        />
                                    </div>
                                </div>

                                <div className="grid grid-cols-12 gap-3 mt-5">
                                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                        <label>{Lang().inventories.fuel_type}</label>
                                        <Select placeholder={'Choose Fuel Type'} isClearable
                                            value={this.state.selectedFuelType}
                                            options={this.state.filter_params.fuel_types.map(fuel_type => {
                                                return { label: fuel_type, value: fuel_type }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedFuelType: selectedOption })}
                                        />
                                    </div>
                                    <div className="col-span-12 sm:col-span-6 md:col-span-6 xxl:col-span-6">
                                        <label>{Lang().inventories.transmission}</label>
                                        <Select placeholder={'Choose Transmission'} isClearable
                                            value={this.state.selectedTransmission}
                                            options={this.state.filter_params.transmissions.map(transmission => {
                                                return { label: transmission, value: transmission }
                                            })}
                                            onChange={(selectedOption) => this.setState({ selectedTransmission: selectedOption })}
                                        />
                                    </div>
                                </div>
                                <div className="p-5">
                                    <button className="button w-24 rounded-full mr-1 mb-2 bg-theme-1 text-white">{Lang().inventories.filter}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {/* /Top filter component */}
            {/* Main content */}
            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                {/* Sidebars overview */}
                <div className="intro-y box">
                    <div className="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 className="font-medium text-base mr-auto">
                            {Lang().inventories.sidebars_overview}
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
                    <div className="grid grid-cols-12 gap-6 mt-5 p-5">
                        <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div className="report-box zoom-in">
                                <div className="box p-5">
                                    <div className="flex">
                                        <i className="icon-bubbles4 eport-box__icon text-theme-10" />
                                    </div>
                                    <div className="text-3xl font-bold leading-8 mt-6">
                                        <NumberFormat prefix={this.state.currency_symbol} value={Math.round(this.state.avg_price * 100) / 100} displayType='text' thousandSeparator={true} />
                                    </div>
                                    <div className="text-base text-gray-600 mt-1">
                                        {Lang().inventories.avg_price}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div className="report-box zoom-in">
                                <div className="box p-5">
                                    <div className="flex">
                                        <i className="icon-bag eport-box__icon text-theme-10" />
                                    </div>
                                    <div className="text-3xl font-bold leading-8 mt-6">
                                        <NumberFormat prefix={this.state.currency_symbol} value={Math.round(this.state.minimum_price * 100) / 100} displayType='text' thousandSeparator={true} />
                                    </div>
                                    <div className="text-base text-gray-600 mt-1">
                                        {Lang().inventories.min_price}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div className="report-box zoom-in">
                                <div className="box p-5">
                                    <div className="flex">
                                        <i className="icon-pointer eport-box__icon text-theme-10" />
                                    </div>
                                    <div className="text-3xl font-bold leading-8 mt-6">
                                        <NumberFormat prefix={this.state.currency_symbol} value={Math.round(this.state.maximum_price * 100) / 100} displayType='text' thousandSeparator={true} />
                                    </div>
                                    <div className="text-base text-gray-600 mt-1">
                                        {Lang().inventories.max_price}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div className="report-box zoom-in">
                                <div className="box p-5">
                                    <div className="flex">
                                        <i className="icon-enter6 eport-box__icon text-theme-10" />
                                    </div>
                                    <div className="text-3xl font-bold leading-8 mt-6">
                                        <NumberFormat prefix={this.state.currency_symbol} value={Math.round(this.state.median_price * 100) / 100} displayType='text' thousandSeparator={true} />
                                    </div>
                                    <div className="text-base text-gray-600 mt-1">
                                        {Lang().inventories.median_price}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="p-5 datatable-wrapper">
                        {
                            this.state.view_mode == 'table' && <table className="table table-striped media-library dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" rowSpan={1} colSpan={1} aria-label="Preview" style={{ width: '100px' }}></th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" tabIndex={0} aria-controls="DataTables_Table_0">{Lang().inventories.name}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" tabIndex={0} aria-controls="DataTables_Table_0" >{Lang().inventories.price}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" tabIndex={0} aria-controls="DataTables_Table_0">{Lang().inventories.color}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" tabIndex={0} aria-controls="DataTables_Table_0">{Lang().inventories.fuel_type}</th>
                                        <th className="text-center border-b-2 whitespace-no-wrap sorting_asc" aria-label="Actions" style={{ width: '90px' }}>{Lang().inventories.transmission}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.data.map((data, index) => {
                                            return <tr role="row" className={index % 2 == 0 ? "" : "odd"} key={index}>
                                                <td className="border-b sorting_1">
                                                    <img src={data.photo} alt="" className="img-preview rounded" width={80} />
                                                </td>
                                                <td className="border-b sorting_1"><a href="#">{data.make}-{data.model}-{data.year}</a><br />{data.country}/{data.city}</td>
                                                <td className="border-b sorting_1"><a href="#">{data.price_with_currency}</a></td>
                                                <td className="border-b sorting_1">{data.color}</td>
                                                <td className="border-b sorting_1">{data.fuel_type}</td>
                                                <td className="border-b sorting_1">{data.transmission}</td>
                                            </tr>

                                        })
                                    }
                                </tbody>
                            </table>
                        }
                        {
                            this.state.view_mode == 'grid' && <div className="intro-y grid grid-cols-12 gap-6 mt-5">
                                {
                                    this.state.data.map((data, index) => {
                                        return <div className="intro-y col-span-12 md:col-span-4 xl:col-span-3 box" key={index}>
                                            <div className="p-5">
                                                <div className="h-40 xxl:h-56 image-fit">
                                                    <img alt="Car" className="rounded-md" src={data.photo} />
                                                </div>
                                                <div className="block mt-5">
                                                    <div className="d-flex align-items-start flex-nowrap font-medium text-base">
                                                        {data.make} {data.model} {data.year}
                                                    </div>
                                                    <div className="text-gray-500 mt-2 text-xs">
                                                        {data.country}/{data.city}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    })
                                }
                            </div>
                        }
                        <div className="datatable-footer mt-4 flex flex-col flex-wrap sm:flex-row items-center p-5 border-b border-gray-200">
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
                                {/* <a className="paginate_button previous disabled" aria-controls="DataTables_Table_0" data-dt-idx={0} tabIndex={0} id="DataTables_Table_0_previous">←</a><span><a className="paginate_button current" aria-controls="DataTables_Table_0" data-dt-idx={1} tabIndex={0}>1</a></span><a className="paginate_button next disabled" aria-controls="DataTables_Table_0" data-dt-idx={2} tabIndex={0} id="DataTables_Table_0_next">→</a> */}
                            </div>
                        </div>
                    </div>
                </div>
                {/* /sidebars overview */}
            </div>
            {/* /right content */}
        </div >
    }
}
