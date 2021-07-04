import React from 'react'
import Lang from "../../Lang/Lang";
import FullCalendar from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from "@fullcalendar/interaction"; // needed for dayClick
import { Modal } from 'react-responsive-modal';
import Axios from 'axios';
import RadioBox from "../../global_ui_components/RadioBox";
import DateTimePicker from 'react-datetime-picker';
import Select, { createFilter } from 'react-select';
import '@fullcalendar/core/main.css';
import 'react-responsive-modal/styles.css';

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

export default class CalendarEvents extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            is_open_create_modal: false,
            open_inventory_dropdown: false,
            type_list: [],
            users: [],
            events: [],
            leads: [],
            customers: [],
            locations: [],
            inventories: [],
            new_event_date: null,
            new_event_title: null,
            new_event_type: null,
            new_event_inventory: null,
            new_event_users: null,
            new_event_locations: null,
            new_event_leads: null,
            new_event_customers: null,
            new_event_descriptions: null,
            is_modal: false,
            text_string: "Hello world"
        }
        this.handleDateClick = this.handleDateClick.bind(this)
        this.handleCreateEvent = this.handleCreateEvent.bind(this)
        this.handleEventClick = this.handleEventClick.bind(this)
        this.onCloseModal = this.onCloseModal(this)
    }
    componentWillMount() {
        Axios.get('/calendar/events/load_basic_data')
            .then(response => {
                console.log(response);
                this.setState({
                    ...this.state, ...response.data
                }, () => {
                    console.log(this.state);
                })
            })
    }
    handleDateClick(info) {
        this.setState({
            new_event_date: info.date,
            is_modal: true,
        })
    }
    handleCreateEvent(e) {
        e.preventDefault()
        let post_data = {
            date: this.state.new_event_date,
            title: this.state.new_event_title,
            type: this.state.new_event_type,
            inventory: this.state.new_event_inventory,
            users: this.state.new_event_users,
            locations: this.state.new_event_locations,
            leads: this.state.new_event_leads,
            customers: this.state.new_event_customers,
            notes: this.state.new_event_descriptions,
            kinds: 'inventory',
        }
        let self = this
        Axios.post('/calendar/events/save', post_data)
            .then(response => {
                let events = [...this.state.events]
                events.push(response.data)
                self.setState({ events: events, is_modal: false }, () => {v
                })

            })
    }
    handleEventClick(info) {
        let self = this
    }
    onCloseModal() {
        console.log("xxx");
        this.setState({is_modal: false});
    }
    render() {
        return <>
            <Modal open={this.state.is_modal} onClose={() => this.setState({is_modal: false})} center>
                <h2 className="text-primary-4 font-medium">{Lang().calendar.create_new_event}</h2>
                <div className="intro-y mt-5">
                    <form onSubmit={this.handleCreateEvent} className="p-5">
                        <div className="mb-5">
                            <input className="input border w-full"
                                onChange={(e) => { this.setState({ new_event_title: e.target.value }) }}
                                placeholder="Enter Title" />
                        </div>
                        <div className="flex flex-wrap mb-5">
                            {
                                this.state.type_list.map((type, index) => {
                                    return <RadioBox
                                        name='new_event_type'
                                        checked={this.state.new_event_type === type._id}
                                        value={type._id}
                                        key={type._id}
                                        label={type.type_name}
                                        onChange={(e) => { this.setState({ new_event_type: e.target.value }) }}
                                    />
                                })
                            }
                        </div>
                        <div className="relative mb-5">
                            <DateTimePicker
                                onChange={(date) => this.setState({ new_event_date: date })}
                                value={this.state.new_event_date}
                            />
                        </div>
                        <div className="relative mb-5">
                            <Select
                                className='w-full border select2'
                                options={
                                    this.state.inventories.map((inventory, index) => {
                                        return {
                                            value: inventory.id,
                                            inventory: inventory,
                                            label: inventory.make_details.name + ' ' + inventory.model_details.name + " " + inventory.year
                                        }
                                    })
                                }
                                onChange={selectedOption => {
                                    this.setState({ open_inventory_dropdown: false, new_event_inventory: selectedOption.value })
                                }}
                                onBlur={() => {
                                    this.setState({ open_inventory_dropdown: false })
                                }}
                                filterOption={createFilter({ ignoreAccents: false })}
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
                                placeholder='Add Inventory'
                            />
                        </div>
                        <div className="relative mb-5">
                            <Select
                                className='form-control material select2'
                                placeholder='Add Users'
                                isMulti
                                onChange={(selectedOption) => {
                                    this.setState({
                                        new_event_users: selectedOption
                                    })
                                }}
                                options={this.state.users.map((user, index) => {
                                    return { label: user.full_name, value: user._id }
                                })}
                            />
                        </div>

                        <div className="relative mb-5">
                            <Select
                                className='form-control material select2'
                                placeholder='Add Locations'
                                isMulti
                                onChange={(selectedOption) => {
                                    this.setState({
                                        new_event_locations: selectedOption
                                    })
                                }}
                                options={this.state.locations.map((location, index) => {
                                    return { label: location.name, value: location.id }
                                })}
                            />
                        </div>

                        <div className="relative mb-5">
                            <Select
                                className='form-control material select2'
                                placeholder='Add Leads'
                                isMulti
                                onChange={(selectedOption) => {
                                    this.setState({
                                        new_event_leads: selectedOption
                                    })
                                }}
                                options={this.state.leads.map((lead, index) => {
                                    return { label: lead.name, value: lead.id }
                                })}
                            />
                        </div>

                        <div className="relative mb-5">
                            <Select
                                className='form-control material select2'
                                placeholder='Add Customers'
                                isMulti
                                onChange={(selectedOption) => {
                                    this.setState({
                                        new_event_customers: selectedOption
                                    })
                                }}
                                options={this.state.customers.map((customer, index) => {
                                    return { label: customer.name, value: customer.id }
                                })}
                            />
                        </div>

                        <div className="relative mb-5">
                            <textarea className="input border w-full" placeholder="Add Description" onChange={(e) => { this.setState({ new_event_descriptions: e.target.value }) }} />
                        </div>
                        
                        <div className="relative mb-5">
                            <button className="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">{Lang().calendar.save}</button>
                        </div>
                    </form>
                </div>
            </Modal>
            <FullCalendar defaultView={'dayGridMonth'}
                header={
                    {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listWeek'
                    }
                }
                events={this.state.events}
                dateClick={this.handleDateClick}
                eventClick={this.handleEventClick}
                eventRender={(info) => {
                    let self = this
                    $(info.el).popover({
                        title: 'Notes',
                        content: `<div>${info.event.extendedProps.notes}</div>`,
                        trigger: 'hover',
                        html: true
                    });

                }}
                plugins={[dayGridPlugin, listPlugin, interactionPlugin]} />
        </>
    }
}
