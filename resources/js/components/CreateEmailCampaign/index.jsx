import React from 'react'
import Lang from "../../Lang/Lang";
import CheckBox from "../../global_ui_components/CheckBox";

import EmailEditor from 'react-email-editor'
import LeadFilterForm from '../../global_ui_components/LeadFilterForm';
import CustomerFilterForm from '../../global_ui_components/CustomerFilterForm';
import Axios from 'axios';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
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
    return <div className='dropdown-item p-0'>
        <span className={`badge bg-${value} badge-pill`}>&nbsp;&nbsp;</span>&nbsp;{label}
    </div>
}
export default class CreateEmailCampaign extends React.Component {
    constructor(props) {
        super(props)
        this.editor = null;
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
            emails: null
        }
        this.handleChooseReceiver = this.handleChooseReceiver.bind(this)
        this.handleSendEmail = this.handleSendEmail.bind(this)

    }
    componentWillMount() {

    }
    handleSendEmail(e) {
        let leads = []
        let customers = []
        if (this.state.receivers.includes('lead')) {
            leads = this.leadForm.getLeads()
        }
        if (this.state.receivers.includes('customer')) {
            customers == this.customerForm.getCustomers()
        }
        let emailHtml = null
        console.log('html', emailHtml)
        this.editor.exportHtml(data => {
            const { design, html } = data
            console.log('exportHtml', html)
            // emailHtml = html
            let post_data = {
                html: html,
                emails: this.state.emails,
                leads: leads,
                customers: customers
            }

            Axios.post('/marketings/email_campaigns/send', post_data)
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
        })
        // console.log('html', emailHtml)

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

    render() {
        return (
            <>
                <div className="mt-5">
                    <label>{Lang().marketing.edit_email}</label>
                    <EmailEditor
                        ref={editor => this.editor = editor}
                    />
                </div>
                <div className="mt-5">
                    <label>{Lang().marketing.bulk_email_list}</label>
                    <textarea
                        className="input border w-full" rows={5}
                        onChange={(e) => { this.setState({ emails: e.target.value }) }}
                    />
                </div>
                <div className="mt-5">
            
                    <div className="flex items-center flex-wrap">
                        <CheckBox
                            label={'To Leads'}
                            checked={this.state.receivers.includes('lead')}
                            value={'lead'}
                            onChange={(event) => this.handleChooseReceiver(event)}
                            checkbox_type='inline' />
                        <CheckBox
                            label={'To Customers'}
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
                    <button className='button bg-theme-4 text-white' onClick={this.handleSendEmail}>{Lang().marketing.send_email}</button>
                </div>
            </>
        )
    }
}
