import React from 'react'
import Lang from "../../Lang/Lang";
import DatePicker from 'react-date-picker';
import Select, { createFilter } from 'react-select';
import Axios from 'axios';
import NumberFormat from 'react-number-format';
import SignModal from 'react-responsive-modal';
import { confirmAlert } from 'react-confirm-alert'; // Import
import SignatureCanvas from 'react-signature-canvas'
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import 'react-responsive-modal/styles.css';

export default class InvoiceForm extends React.Component {
    constructor(props) {
        super(props)
        this.signPad = null
        this.state = {
            date: null,
            tax_price: 0,
            total_with_tax: null,
            total_price: null,
            customer: null,
            descriptions: [{}],
            customer_list: [],
            currencies: [],
            settings: {},
            cur_currency: [],
            is_sign_modal: false,
            customer_info: {
                name: '',
                address: '',
                phone_number_details: [{}],
                email_details: [{}],
            },
            total_details_price: 0
        }
        this.handleSubmit = this.handleSubmit.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleAddDetails = this.handleAddDetails.bind(this)
        this.handleRemoveDetails = this.handleRemoveDetails.bind(this)
        this.handleUpdateDetails = this.handleUpdateDetails.bind(this)
        this.sendInvoice = this.sendInvoice.bind(this);
        this.getCustomerInfo = this.getCustomerInfo.bind(this);
        this.getCurrencyLabel = this.getCurrencyLabel.bind(this);
    }
    handleChange(e) {
        this.setState({
            [e.target.name]: e.target.value
        })
    }
    componentWillMount() {
        Axios.get('/transactions/invoice/load_basic_data')
            .then(response => {
                this.setState({
                    ...this.state, ...response.data
                }, () => {
                })
            })
    }
    handleSubmit(e) {
        e.preventDefault()
        
    }
    handleAddDetails(e) {
        let descriptions = this.state.descriptions
        descriptions.push({})
        this.setState({
            descriptions: descriptions
        })
    }
    handleRemoveDetails(index) {
        let descriptions = this.state.descriptions
        descriptions.splice(index, 1)
        this.setState({
            descriptions: descriptions
        })
    }
    handleUpdateDetails(field, index, value) {
        let details = this.state.descriptions
        details[index][field] = value
        details[index]['total'] = details[index]['price'] * details[index]['quantity']
        

        this.setState({
            descriptions: details
        })

        let total_value = this.state.total_details_price;
        let data = {
            descriptions: this.state.descriptions
        }
        Axios.post('/transactions/invoice/get_price_with_currency', data)
            .then(response => {
                this.setState({total_details_price: response.data.total_details_price})
                this.setState({tax_price: response.data.tax_price})
                this.setState({total_with_tax: response.data.total_with_tax})
            })
    }
    getCurrencyLabel(id) {
        let currencies = this.state.currencies
        let currency_index = currencies.findIndex(currency => currency.id === id)
        if (currency_index != -1) {
            let currency = currencies[currency_index]
            return `${currency.currency}(${currency.symbol})`
        }
        else {
            return ""
        }
    }
    getCustomerInfo(customer) {
        this.setState({
            customer: customer
        }, () => {
            let post_data = {
                customer: customer,
            }
            Axios.post(`/transactions/invoice/get_customer_info`, post_data)
                .then(response => {
                    let customer_data = response.data
                    let customer_info = {
                        name: customer_data.name,
                        email_details: customer_data.email_details,
                        phone_number_details: customer_data.phone_number_details,
                        address: customer_data.address,
                    }
                    this.setState({customer_info: customer_info});
                })
        })
    }
    sendInvoice() {
        let post_data = {
            total_details_price: this.state.total_details_price,
            company_logo: this.state.company_logo,
            bank_name: this.state.bank_name,
            bank_country: this.state.bank_country,
            bank_city: this.state.bank_city,
            bank_address: this.state.bank_address,
            bank_iban: this.state.bank_iban,
            bank_swift: this.state.bank_swift,
            descriptions: this.state.descriptions,
            customer: this.state.customer,
            signature: this.state.signature,
            date: this.state.date,
            total_with_tax: this.state.total_with_tax,
            tax_price: this.state.tax_price
        }
        Axios.post(`/transactions/invoice/send_invoice`, post_data)
        .then(response => {
            let invoice_id = response.data
            confirmAlert({
                title: "Invoice Succeed!",
                message: 'Do you want to see it?',
                buttons: [
                    {
                        label: 'No',
                    },
                    {
                        label: 'Yes',
                        onClick: () => {
                            let path = `/invoices/${invoice_id}.pdf`;
                            window.open(path);
                        }
                    }
                ]
            });
        })
    }
    render() {
        return <>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="mt-5 col-span-6 sm:col-span-6">
                    <div className="mb-4">
                        <img src={this.state.company_logo} className="mb-3 mt-2" alt="" style={{ width: '120px' }} />
                        <ul className="list list-unstyled mb-0">
                            <li>{this.state.settings.company_street_number},{this.state.settings.company_route}</li>
                            <li>{this.state.settings.company_state},{this.state.settings.company_postal_code}</li>
                            <li>{this.state.settings.company_locality}, {this.state.settings.company_country}</li>
                            <li>{this.state.settings.company_phone_number}</li>
                        </ul>
                    </div>
                </div>
                <div className="mt-5 col-span-6 sm:col-span-6">
                    <div className="mb-4">
                        <div className="text-right">
                            <h4 className="text-theme-4 mb-2">Invoice #49029</h4>
                            <DatePicker
                                onChange={(date) => this.setState({ date: date })}
                                className='form-control'
                                value={this.state.date}
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="mt-5 col-span-6 sm:col-span-6">
                    <label>{Lang().transaction.invoice_to}:</label>
                    <Select
                        filterOption={createFilter({ ignoreAccents: false })}
                        options={
                            this.state.customer_list.map((customer, index) => {
                                return {
                                    value: customer.id, label: customer.name
                                }
                            })
                        }
                        onChange={selectedOption => {
                            this.getCustomerInfo(selectedOption.value)
                        }}
                        placeholder='Choose Customer' 
                    />
                    {
                        this.state.customer != null && <ul className="list list-unstyled mb-0">
                            <li><h5 className="my-2">{this.state.customer_info.name}</h5></li>
                            <li><span className="font-medium">{this.state.customer_info.address}</span></li>
                            {
                                this.state.customer_info.phone_number_details.map((info, index) => {
                                    return <li key={index}>
                                        {info.mobile_no}
                                    </li>
                                })
                            }
                            {
                                this.state.customer_info.email_details.map((info, index) => {
                                    return <li key={index}>
                                        <a href="#" className="text-theme-6">{info.email}</a>
                                    </li>
                                })
                            }
                        </ul>
                    }
                </div>
                <div className="mt-5 col-span-6 sm:col-span-6">
                    <label>{Lang().transaction.payment_details}:</label>
                    <div className="mt-5">
                        <label>{Lang().transaction.total_due}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="total_price" value={this.state.total_price} placeholder="Total Due" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>{Lang().transaction.bank_name}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_name" value={this.state.bank_name} placeholder="Bank name" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>{Lang().transaction.country}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_country" value={this.state.bank_country} placeholder="Country" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>{Lang().transaction.city}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_city" value={this.state.bank_city} placeholder="City" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>{Lang().transaction.address}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_address" value={this.state.bank_address} placeholder="Address" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>IBAN:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_iban" value={this.state.bank_iban} placeholder="IBAN" required />
                    </div>
                    
                    <div className="mt-5">
                        <label>{Lang().transaction.swift_code}:</label>
                        <input type="text" className="input border w-full" onChange={this.handleChange} name="bank_swift" value={this.state.bank_swift} placeholder="SWIFT code" required />
                    </div>
                </div>
            </div>

            <div className="overflow-x-auto">
                <table className="table">
                    <thead>
                        <tr>
                            <th className="border-b-2 whitespace-no-wrap"><button type="button" className="flex items-center justify-center w-8 h-8 rounded-full button border border-theme-4 text-theme-4"
                                onClick={this.handleAddDetails}><i className="icon-plus2" /></button></th>
                            <th className="border-b-2 whitespace-no-wrap">{Lang().transaction.detail}</th>
                            <th className="border-b-2 whitespace-no-wrap">{Lang().transaction.price}</th>
                            <th className="border-b-2 whitespace-no-wrap">{Lang().transaction.currency}</th>
                            <th className="border-b-2 whitespace-no-wrap">{Lang().transaction.quantity}</th>
                            <th className="border-b-2 whitespace-no-wrap">{Lang().transaction.total}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            this.state.descriptions.map((description, index) => {
                                return <tr key={index}>
                                    <td className="border-b">
                                        <button type="button" className="flex items-center justify-center w-8 h-8 rounded-full button border text-theme-6 border-theme-6"
                                            onClick={(e) => this.handleRemoveDetails(index)}
                                        ><i className="icon-cross2" /></button>
                                    </td>
                                    <td className="border-b">
                                        <input className="input border w-full mb-2" onChange={(e) => this.handleUpdateDetails('title', index, e.target.value)} placeholder="Title" />
                                        <input className="input border w-full" onChange={(e) => this.handleUpdateDetails('description', index, e.target.value)} placeholder="Description" />
                                    </td>
                                    <td className="border-b">
                                        <NumberFormat
                                            className={'input border w-full'}
                                            placeholder='Enter Price'
                                            thousandSeparator={true}
                                            onValueChange={(values) => {
                                                const { formattedValue, value } = values
                                                this.handleUpdateDetails('price', index, value)
                                            }} />
                                    </td>
                                    <td className="border-b">
                                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                            options={this.state.currencies.map((currency, index) => {
                                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                                            })}
                                            onChange={selectedOption => {
                                                this.handleUpdateDetails('currency', index, selectedOption.value)
                                            }}
                                            value={description.currency ? { value: description.currency, label: this.getCurrencyLabel(description.currency) } : this.state.cur_currency}
                                            placeholder='Currency'
                                            isClearable={true}
                                        />
                                    </td>
                                    <td className="border-b">
                                        <NumberFormat
                                            className={'input border w-full'}
                                            placeholder='Enter Qty'
                                            thousandSeparator={true}
                                            onValueChange={(values) => {
                                                const { formattedValue, value } = values
                                                this.handleUpdateDetails('quantity', index, value)
                                            }} />
                                        </td>
                                    <td className="border-b"><span className="font-medium">
                                        <NumberFormat
                                            displayType={'text'}
                                            thousandSeparator={true}
                                            value={description.total} />
                                    </span></td>
                                </tr>

                            })
                        }
                    </tbody>
                </table>
            </div>
            <div className="flex items-center flex-wrap">
                <div className="pt-2 mb-3">
                    <h3 className="mb-3 text-theme-1 text-lg">{Lang().transaction.authorized_person}</h3>
                    {
                        this.state.signature == null && <div>
                            <button className="button bg-theme-1 text-white" onClick={() => { this.setState({ is_sign_modal: true }) }}>{Lang().transaction.add_signature}</button>
                        </div>
                    }
                    {
                        this.state.signature != null && <div><img width={150} src={this.state.signature} /></div>
                    }
                    {
                        this.state.signature != null && <div>
                            <button className="button bg-theme-6 text-white mt-3" onClick={() => { this.setState({ signature: null }) }}>{Lang().transaction.clear}</button>
                        </div>
                    }
                    <ul className="">
                        <li>{this.state.settings.company_street_number},{this.state.settings.company_route}</li>
                        <li>{this.state.settings.company_state},{this.state.settings.company_postal_code}</li>
                        <li>{this.state.settings.company_locality}, {this.state.settings.company_country}</li>
                        <li>{this.state.settings.company_phone_number}</li>
                    </ul>
                </div>
                <div className="pt-2 mb-3 ml-auto w-64">
                    <h3 className="mb-3 text-theme-1 text-lg">{Lang().transaction.total_due}</h3>
                    <div className="overflow-x-auto">
                        <table className="table">
                            <tbody>
                                <tr className="border-b-2 whitespace-no-wrap">
                                    <th>{Lang().transaction.sub_total}:</th>
                                    <td className="text-right">{this.state.total_details_price}</td>
                                </tr>
                                {
                                    this.state.settings.company_tax > 0 && <tr className="border-b-2 whitespace-no-wrap">
                                        <th>{Lang().transaction.tax}: <span className="font-weight-normal">{this.state.settings.company_tax}%</span></th>
                                        <td className="text-right">{this.state.tax_price}</td>
                                    </tr>
                                }

                                <tr className="border-b-2 whitespace-no-wrap">
                                    <th>{Lang().transaction.total}:</th>
                                    <td className="text-right text-theme-1">
                                        <h5 className="font-medium">
                                            {this.state.total_with_tax}
                                        </h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className="text-right mt-3">
                        <button type="button" onClick={this.sendInvoice} className="button bg-theme-1 text-white flex items-center justify-center"><b><i className="icon-paperplane mr-2" /></b> Send invoice</button>
                    </div>
                </div>
            </div>
            <SignModal open={this.state.is_sign_modal}
                onClose={() => { this.setState({ is_sign_modal: false }) }} center>
                <SignatureCanvas penColor='green'
                    canvasProps={{ width: 600, height: 200, border: 1, className: 'sigCanvas border-b-2 text-theme-2' }}
                    ref={(ref) => { this.signPad = ref }} />
                <div className="flex items-center flex-wrap mt-5">
                    <button className="button bg-theme-4 text-white mr-2" onClick={() => {
                        this.setState({
                            is_sign_modal: false,
                            signature: this.signPad.getTrimmedCanvas().toDataURL('image/png')
                        })
                    }}>{Lang().transaction.add}</button>
                    <button className="button bg-theme-6 text-white" onClick={() => { this.signPad.clear() }}>{Lang().transaction.clear}</button>
                </div>
            </SignModal>
        </>
    }
}
