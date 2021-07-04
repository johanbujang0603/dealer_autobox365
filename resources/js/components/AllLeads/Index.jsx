import React from 'react'
import Axios from 'axios'
import ReactPaginate from 'react-paginate';
import countryList from 'react-select-country-list'
import Select, { createFilter } from 'react-select';
import AsyncSelect from 'react-select/Async'
import RadioBox from "../../global_ui_components/RadioBox";
import Lang from "../../Lang/Lang";
import { format } from "react-phone-input-auto-format";
import DateRangePicker from '@wojtekmaj/react-daterange-picker';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)

export default class AllLeads extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            last_page: 1,
            view_mode: 'table',
            data: [],
            locations: [],
            inventories: [],
            filters: {},
            open_inventory_dropdown: null,
            search_key: null,
            permission: JSON_DATA['permission'],
            inventory_keywords: null
        }
        this.handleSearch = this.handleSearch.bind(this)
        this.handlePageClick = this.handlePageClick.bind(this)
        this.loadBasicData = this.loadBasicData.bind(this)
        this.handleChangeFilter = this.handleChangeFilter.bind(this)
        this.handleTypeInventory = this.handleTypeInventory.bind(this)
        this.searchLeads = this.searchLeads.bind(this);
    }
    componentDidMount() {
        this.loadBasicData()
        this.handleSearch()
    }
    async loadBasicData() {
        let locations = []
        await Axios.get('/locations/all').then(response => {
            locations = response.data
        })
        this.setState({
            locations: locations
        })
    }
    handleChangeFilter(field, value) {
        let filters = this.state.filters
        filters[field] = value
        this.setState({
            filters: filters
        })
    }
    handleSearch(page = 1) {
        let search_params = {
            page_number: page
        }
        Axios.get("/leads/filter", { params: search_params })
            .then(response => {
                this.setState({ ...this.state, ...response.data })
            })
    }
    handlePageClick(data) {
        this.handleSearch(data.selected + 1)
    }
    handleTypeInventory(value) {
        this.setState({ inventory_keywords: value })
    }
    getInventoryOptions() {
        let data = Axios.get(`/inventories/search`, {
            params: {
                searchKey: this.state.inventory_keywords
            }
        }).then(response => {
            let options = response.data.map((inventory) => {
                return { label: inventory.inventory_name, value: inventory.id, inventory: inventory }
            })
            return options
        })
        return data

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
                        <h1>Are you sure to remove this lead?</h1>
                        <div className="react-confirm-alert-info">
                            <img src={profileImage} alt="" />
                            <span>{name}</span>
                        </div>
                        <div className="react-confirm-alert-button-group">
                            <button onClick={onClose}>No</button>
                            <button
                                onClick={() => {
                                    location.href = `/leads/delete/${id}`
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
    searchLeads(e) {
        let searchKey = event.target.value;
        this.setState({ search_key: searchKey }, () => {
        })
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
                    </div>
                    <div className="accordion__pane__content p-5 mt-3 text-gray-700 leading-relaxed">
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-6 md:col-span-6 xl:col-span-3 xxl:col-span-3">
                                <label>{Lang().leads.location}:</label>
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    value={this.state.select_location}
                                    options={this.state.locations.map((location, index) => {
                                        return { value: location.id, label: `${location.name}` }
                                    })}
                                    isClearable={true}
                                    onChange={(selectedOption) => {
                                        this.setState({
                                            location: selectedOption ? selectedOption.value : null,
                                            select_location: selectedOption
                                        }, () => {
                                        })
                                    }}
                                />
                            </div>
                            <div className="col-span-12 sm:col-span-6 md:col-span-6 xl:col-span-3 xxl:col-span-3">
                                <label>{Lang().user.gender}:</label>
                                <div className="flex items-center text-gray-700 mr-2">
                                    <RadioBox
                                        name='gender'
                                        checked={this.state.filters.gender == '' || this.state.filters.gender == null}
                                        value=''
                                        label='All'
                                        onChange={(e) => this.handleChangeFilter('gender', '')}
                                        className="mr-2"
                                    />
                                    <RadioBox
                                        name='gender'
                                        checked={this.state.filters.gender == 'Male'}
                                        value='Male'
                                        label='Male'
                                        onChange={(e) => this.handleChangeFilter('gender', 'Male')}
                                        className="mr-2"
                                    />
                                    <RadioBox
                                        name='gender'
                                        checked={this.state.filters.gender == 'Female'}
                                        value='Female'
                                        label='Female'
                                        onChange={(e) => this.handleChangeFilter('gender', 'Female')}
                                        className="mr-2"
                                    />
                                </div>
                            </div>
                            <div className="col-span-12 sm:col-span-6 md:col-span-6 xl:col-span-3 xxl:col-span-3">
                                <label>{Lang().date_of_addition}</label>
                                <DateRangePicker
                                    onChange={(date) => this.handleChangeFilter('date_of_addition', date)}
                                    className='w-100'
                                    value={this.state.filters.date_of_addition}
                                />
                            </div>
                            <div className="col-span-12 sm:col-span-6 md:col-span-6 xl:col-span-3 xxl:col-span-3">
                                <label>{Lang().leads.inventory_linked}:</label>
                                <AsyncSelect
                                    cacheOptions
                                    onInputChange={this.handleTypeInventory}
                                    loadOptions={this.getInventoryOptions.bind(this)}
                                    placeholder='Choose Inventory' />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12" style={{'zIndex': '0'}}>
                <div className="intro-y box">
                    <div className="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 className="font-medium text-base mr-auto">
                            {Lang().leads.text}
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
                    <div className="p-5 datatable-wrapper">
                        <div className="grid grid-cols-12 gap-3 mt-5">
                            <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                                <label>Search:<input type="search" className="ml-2 search__input input border placeholder-theme-1" placeholder="" onChange={this.searchLeads}/></label>
                            </div>
                        </div>
                        
                        {
                            this.state.view_mode == 'table' && <div className="grid grid-cols-12 gap-3 mt-5"><div className="col-span-12 sm:col-span-12 overflow-x-auto"><table className="table table-striped media-library dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                <thead>
                                    <tr role="row">
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc" style={{ width: 50 }}></th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc">{Lang().leads.name}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc">{Lang().leads.phone_numbers}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc">{Lang().leads.emails}</th>
                                        <th className="border-b-2 whitespace-no-wrap sorting_asc">{Lang().leads.tags}</th>
                                        <th className="text-center border-b-2 whitespace-no-wrap sorting_asc" aria-label="Actions" style={{ width: '90px' }}>{Lang().app.action}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        this.state.data.map((data, index) => {
                                            let searchKey = null;
                                            if (this.state.search_key != null)
                                                searchKey = this.state.search_key.toLowerCase()
                                            if (data.name.toLowerCase().includes(searchKey) || searchKey == null) {
                                                return <tr role="row" className={index % 2 == 0 ? "" : "odd"} key={index}>
                                                    <td className="border-b w-32"><img className="rounded-full m-auto"
                                                             src={data.profile_image_src} /></td>
                                                    <td className="border-b">{data.name}</td>
                                                    <td className="border-b"><ul>
                                                        {
                                                            data.phone_number_details.map((phone_number_detail, index) => {
                                                                return <li key={index} style={{ listStyle: 'none' }}>{phone_number_detail.intl_formmated_number}</li>
                                                            })
                                                        }
                                                    </ul>
                                                    </td>
                                                    <td className="border-b"><ul>
                                                        {
                                                            data.email_details.map((email, index) => {
                                                                return <li key={index} style={{ listStyle: 'none' }}>{email.email}</li>
                                                            })
                                                        }
                                                    </ul></td>
                                                    <td className="border-b">
                                                        {
                                                            data.tags_array.map((tag, index) => {
                                                                return <span key={index} className={`truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-${tag.color}`}>{tag.tag_name}</span>
                                                            })
                                                        }
                                                    </td>
                                                    <td className="border-b">
                                                        <div className="flex items-center">
                                                            {
                                                                this.state.permission['write'] && <a href={`/leads/edit/${data.id}`} className="block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md tooltip" title="Edit"><i className="icon-pencil" /></a>
                                                            }
                                                            {
                                                                this.state.permission['read'] && <a href={`/leads/view/${data.id}`} className="block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md tooltip" title="View"><i className="icon-eye" /></a>
                                                            }
                                                            {
                                                                this.state.permission['delete'] && <a href='#!' onClick={() => {this.confirmRemove(data.profile_image_src, data.name, data.id)}} className="block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md tooltip" title="Delete"><i className="icon-bin" /></a>
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
                                                    <img className="rounded-md" src={data.profile_image_src} alt="" />
                                                </div>
                                                <div className="block mt-5">
                                                    <div className="d-flex align-items-start flex-nowrap font-medium text-base">
                                                        {data.name}
                                                    </div>
                                                    <div className="text-gray-500 mt-2 text-xs">
                                                        {data.country_base_residence ? countryList().getLabel(data.country_base_residence) : ""}/{data.city}
                                                    </div>
                                                    
                                                    <div className="flex sm:justify-center items-center mt-5">
                                                        <a href="#" className="mr-2" data-popup="tooltip" title="Google Drive" data-container="body"><i className="icon-google-drive" /></a>
                                                        <a href="#" className="mr-2" data-popup="tooltip" title="Twitter" data-container="body"><i className="icon-twitter" /></a>
                                                        <a href="#" className="mr-2" data-popup="tooltip" title="Github" data-container="body"><i className="icon-github" /></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    })
                                }
                            </div>
                        }
                        <div className="datatable-footer mt-4 flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
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

        </div>
    }
}
