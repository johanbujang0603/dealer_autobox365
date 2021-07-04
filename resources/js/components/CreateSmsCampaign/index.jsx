import React from 'react'
import Lang from "../../Lang/Lang";
import CheckBox from "../../global_ui_components/CheckBox";

import LeadFilterForm from '../../global_ui_components/LeadFilterForm';
import CustomerFilterForm from '../../global_ui_components/CustomerFilterForm';
import Axios from 'axios';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
export default class CreateSmsCampaign extends React.Component {
    constructor(props) {
        super(props)
        this.leadForm = null;
        this.customerForm = null;
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
            leads: [],
            phones: null,
            sms: null,
        }
        this.handleChooseReceiver = this.handleChooseReceiver.bind(this)
        this.handleSendSMS = this.handleSendSMS.bind(this)

    }
    componentWillMount() {

    }

    handleChooseReceiver(e) {
        let receivers = this.state.receivers

        if (receivers.includes(e.target.value)) {
            let index_of_receive = receivers.indexOf(e.target.value)
            receivers.splice(index_of_receive, 1)
        }
        else {
            receivers.push(e.target.value)
        }
        this.setState({
            receivers: receivers
        })
    }
    handleSendSMS() {
        let leads = []
        let customers = []
        if (this.state.receivers.includes('lead')) {
            leads = this.leadForm.getLeads()
        }
        if (this.state.receivers.includes('customer')) {
            customers == this.customerForm.getCustomers()
        }
        let post_data = {
            sms: this.state.sms,
            phones: this.state.phones,
            leads: leads,
            customers: customers
        }

        Axios.post('/marketings/sms_campaigns/send', post_data)
            .then(response => {
                confirmAlert({
                    title: "Thank you!",
                    message: "You have sent email campaign to your email list.",
                    buttons: [
                        {
                            label: 'Okay',
                            onClick: () => 1
                        }
                    ]
                });
            })
        // console.log('html', emailHtml)
    }
    render() {
        return (
            <>
                <div className="mt-5">
                    <label>{Lang().marketing.text_message}</label>
                    <textarea className="input border w-full" placeholder="Enter marketing SMS"
                        onChange={(e) => { this.setState({ sms: e.target.value }) }} />
                </div>
                <div className="mt-5">
                    <label>{Lang().marketing.bulk_sms_list}</label>
                    <textarea
                        className="input border w-full" rows={5}
                        onChange={(e) => { this.setState({ phones: e.target.value }) }}
                    />
                </div>
                <div className="mt-5">
                    <div className="flex items-center flex-wrap">
                        <CheckBox
                            label={'Include Leads'}
                            checked={this.state.receivers.includes('lead')}
                            value={'lead'}
                            onChange={(event) => this.handleChooseReceiver(event)}
                            checkbox_type='inline' />
                        <CheckBox
                            label={'Include Customers'}
                            checked={this.state.receivers.includes('customer')}
                            value={'customer'}
                            onChange={(event) => this.handleChooseReceiver(event)}
                            checkbox_type='inline' />
                    </div>

                </div>

                {
                    this.state.receivers.includes('lead') && <LeadFilterForm ref={leadForm => this.leadForm = leadForm} />
                }
                {
                    this.state.receivers.includes('customer') && <CustomerFilterForm ref={customerForm => this.customerForm = customerForm} />
                }
                <hr />
                <div className="mt-5">
                    <button className='button bg-theme-4 text-white' onClick={this.handleSendSMS}>{Lang().marketing.send_sms}</button>
                </div>
            </>
        )
    }
}
