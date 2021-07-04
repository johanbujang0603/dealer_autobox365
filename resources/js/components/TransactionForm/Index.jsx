import React from 'react'
import Lang from "../../Lang/Lang";
import countryList from 'react-select-country-list'
import Select, { createFilter } from 'react-select';
import DatePicker from 'react-date-picker';
import NumberFormat from 'react-number-format';
import Axios from 'axios';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import RadioBox from '../../global_ui_components/RadioBox';
import DocumentsUpload from '../../global_ui_components/DocumentsUpload';

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

export default class TransactionForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            transaction_details: {
                date_of_sale: new Date(),
                date_of_estimate_delivery: null,
                price: null,
                inventory: null,
                notes: null,
                user: null,
                location: null,
                currency: JSON_DATA['user_currency_id'],
                lead: null,
                finance: null,
                financial_institution_name: null
            },
            documents: [],
            //page details
            currencies: JSON_DATA['currencies'],
            user_currency_id: JSON_DATA['user_currency_id'],
            inventories: JSON_DATA['inventories'],
            locations: JSON_DATA['locations'],
            users: JSON_DATA['users'],
            leads: JSON_DATA['leads'],
            countries: countryList().getData(),
        }

        this.handleUpdateTransactionDetails = this.handleUpdateTransactionDetails.bind(this)

        this.handleCreateTransaction = this.handleCreateTransaction.bind(this)
        this.handleAddDocuments = this.handleAddDocuments.bind(this)
        this.handleRemoveDocument = this.handleRemoveDocument.bind(this)
        this.loadData = this.loadData.bind(this)
        this.getInventoryLabel = this.getInventoryLabel.bind(this)
        this.getLeadName = this.getLeadName.bind(this)
        this.getCurrencyLabel = this.getCurrencyLabel.bind(this)
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit') {
            this.loadData(urlParams.transactionId)
        }
    }
    loadData(id) {
        let self = this
        Axios.get(`/transactions/load_details/${id}`)
            .then(response => {
                this.setState({
                    transaction_details: {
                        ...this.state.transaction_details,
                        ...response.data.transaction,
                        inventory: response.data.transaction.inventory_id,
                        date_of_sale: new Date(response.data.transaction.date_of_sale),
                        date_of_estimate_delivery: new Date(response.data.transaction.date_of_estimate_delivery),
                    },
                    documents: response.data.documents
                }, () => {
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
    readUploadedDocumentPreview(imageFile) {
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
    handleRemoveDocument(index) {
        let { documents } = this.state
        let removeFile = documents[index]
        documents.splice(index, 1)
        this.setState({
            documents: documents
        })
    }
    // async handleAddDocuments(files) {
    handleAddDocuments(files) {
        let documents = this.state.documents
        for (let i = 0; i < files.length; i++) {
            // const documentPreview = await this.readUploadedDocumentPreview(files[i])
            const documentPreview = null
            let document_data = {
                preview_image: documentPreview,
                original_name: files[i].name,
                filesize: files[i].size,
                file_data: files[i],
                status: 'uploading',
                upload_progress: 0,
            }
            documents.push(document_data)
        }
        this.setState({
            documents: documents
        }, () => {
            this.state.documents.map((listing_document, index) => {
                if (listing_document.upload_progress == 0) {
                    let self = this
                    let formData = new FormData();
                    formData.append('file', listing_document.file_data);
                    Axios.post('/transactions/documents/upload', formData, {
                        onUploadProgress: function (progressEvent) {
                            let documents = self.state.documents
                            documents[index].upload_progress = (progressEvent.loaded / progressEvent.total) * 100
                            self.setState({
                                documents: documents
                            })
                            // Do whatever you want with the native progress event
                        },
                    })
                        .then(response => {
                            let plate_number = this.state.plate_number
                            if (response.data.status == 'success') {
                                let documents = this.state.documents
                                documents[index].status = 'uploaded'
                                documents[index] = { ...documents[index], ...response.data.file }

                                this.setState({
                                    documents: documents,
                                })

                            }
                        })
                }
            })
        })

    }

    handleUpdateTransactionDetails(value, field) {
        let transaction_details = this.state.transaction_details
        transaction_details[field] = value
        this.setState({
            transaction_details: transaction_details
        })
    }

    handleCreateTransaction() {
        let post_data = {
            ...this.state.transaction_details, documents: this.state.documents
        }
        Axios.post('/transactions/save', post_data)
            .then(response => {
            })
    }
    getInventoryLabel(id) {
        let inventories = this.state.inventories
        let inventory_index = inventories.findIndex(inventory => inventory.id === id)
        if (inventory_index != -1) {
            return inventories[inventory_index].inventory_name
        }
        else {
            return ""
        }
    }
    getLeadName(id) {
        let leads = this.state.leads
        let lead_index = leads.findIndex(lead => lead.id === id)
        if (lead_index != -1) {
            return leads[lead_index].name
        }
        else {
            return ""
        }
    }
    getUserName(id) {
        let users = this.state.users
        let user_index = users.findIndex(user => user.id === id)
        if (user_index != -1) {
            return users[user_index].name
        }
        else {
            return ""
        }
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
    render() {
        let { transaction_details } = this.state
        return <>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.date_of_sale}:</label>
                    <DatePicker
                        onChange={(date) => this.handleUpdateTransactionDetails(date, 'date_of_sale')}
                        className='input w-full'
                        value={transaction_details.date_of_sale}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.inventory_item_purchased}:</label>
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        filterOption={createFilter({ ignoreAccents: false })}
                        options={
                            this.state.inventories.map((inventory, index) => {
                                return {
                                    value: inventory.id, inventory: inventory, label: inventory.make_details.name + ' ' + inventory.model_details.name + " " + inventory.year
                                }
                            })
                        }
                        onChange={selectedOption => {
                            this.handleUpdateTransactionDetails(selectedOption.value, 'inventory')
                        }}
                        value={this.state.transaction_details.inventory ? { value: this.state.transaction_details.inventory, label: this.getInventoryLabel(this.state.transaction_details.inventory) } : null}
                        // formatOptionLabel={inventoryFormat}
                        components={{ Option: CustomOption }}
                        placeholder='Choose Inventory' />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.lead_or_customer}:</label>
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        filterOption={createFilter({ ignoreAccents: false })}
                        options={
                            this.state.leads.map((lead, index) => {
                                return {
                                    value: lead.id, label: lead.name
                                }
                            })
                        }
                        onChange={selectedOption => {
                            this.handleUpdateTransactionDetails(selectedOption.value, 'lead')
                        }}
                        value={this.state.transaction_details.lead ? { value: this.state.transaction_details.lead, label: this.getLeadName(this.state.transaction_details.lead) } : null}
                        placeholder='Choose Lead or Customer' />
                </div>
            </div>
            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.user_who_managed_sale}:</label>
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        isMulti
                        onChange={selectedOption => {
                            this.handleUpdateTransactionDetails(selectedOption, 'user')
                        }}
                        value={this.state.transaction_details.user}
                        options={this.state.users.map((user, index) => {
                            return { label: user.name, value: user._id }
                        })} />
                </div>
            </div>
            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.location_of_sale}:</label>
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        isMulti
                        options={this.state.locations.map((location, index) => {
                            return { label: location.name, value: location.id }
                        })}
                        value={this.state.transaction_details.location}
                        onChange={selectedOption => {
                            this.handleUpdateTransactionDetails(selectedOption, 'location')
                        }}
                    />
                </div>
            </div>
            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-4 lg:col-span-4">
                    <label className="font-medium">{Lang().transaction.price_of_sale}:</label>
                    <NumberFormat
                        value={transaction_details.price}
                        className={'input border w-full'}
                        placeholder='Enter Price'
                        thousandSeparator={true}
                        onValueChange={(values) => {
                            const { formattedValue, value } = values
                            this.handleUpdateTransactionDetails(value, 'price')
                        }} />
                </div>
                <div className="col-span-4 lg:col-span-4">
                    <label className="font-medium">{Lang().transaction.downpayment}:</label>
                    <NumberFormat
                        value={transaction_details.down_payment_price}
                        className={'input border w-full'}
                        placeholder='Enter Price'
                        thousandSeparator={true}
                        onValueChange={(values) => {
                            const { formattedValue, value } = values
                            this.handleUpdateTransactionDetails(value, 'down_payment_price')
                        }} />
                </div>
                <div className="col-span-4 lg:col-span-4">
                    <label className="font-medium">&nbsp;</label>
                    <Select menuContainerStyle={{ 'zIndex': 9999 }}
                        options={this.state.currencies.map((currency, index) => {
                            return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                        })}
                        onChange={selectedOption => {
                            this.handleUpdateTransactionDetails(selectedOption.value, 'currency')
                        }}
                        value={{ value: this.state.transaction_details.currency, label: this.getCurrencyLabel(this.state.transaction_details.currency) }}
                        placeholder='Currency'
                        isClearable={true}
                    />
                </div>
            </div>

            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-12 lg:col-span-12">
                    <label className="d-block">Finance:</label>
                    <div className="flex items-center flex-wrap">
                        <RadioBox
                            name='finance'
                            checked={this.state.transaction_details.finance == 'Yes'}
                            value='Yes'
                            label='Yes'
                            onChange={(e) => {
                                this.handleUpdateTransactionDetails(e.target.value, 'finance')
                            }}
                        />
                        <RadioBox
                            name='finance'
                            checked={this.state.transaction_details.finance == 'No'}
                            value='No'
                            label='No'
                            onChange={(e) => {
                                this.handleUpdateTransactionDetails(e.target.value, 'finance')
                            }}
                        />
                    </div>
                </div>
            </div>
            {
                this.state.transaction_details.finance == 'Yes' &&  <div className='grid grid-cols-12 gap-6 mt-5'>
                    <div className="col-span-12 lg:col-span-12">
                        <label className="font-medium">{Lang().transaction.financial_institution_name}</label>
                        <input name="financial_institution_name" className='input border w-full' value={this.state.transaction_details.financial_institution_name || ''} onChange={(e) => { this.handleUpdateTransactionDetails(e.target.value, 'financial_institution_name') }} />
                    </div>
                </div>
            }

            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.date_of_estimate_delivery}:</label>
                    <DatePicker
                        onChange={(date) => this.handleUpdateTransactionDetails(date, 'date_of_estimate_delivery')}
                        className='input w-full'
                        value={transaction_details.date_of_estimate_delivery}
                    />
                </div>
            </div>
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.documents}:</label>
                    <DocumentsUpload
                        files={this.state.documents}
                        addFiles={this.handleAddDocuments}
                        removeFile={this.handleRemoveDocument} />
                </div>
            </div>


            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-12 lg:col-span-12">
                    <label className="font-medium">{Lang().transaction.notes}:</label>
                    <CKEditor

                        editor={ClassicEditor}
                        data={transaction_details.notes}
                        onInit={editor => {
                        }}
                        onChange={(event, editor) => {
                            let notes = editor.getData()
                            this.handleUpdateTransactionDetails(notes, 'notes')
                        }}
                        onBlur={editor => {

                        }}
                        onFocus={editor => {
                        }}
                    />
                </div>
            </div>

            <div className='grid grid-cols-12 gap-6 mt-5'>
                <div className="col-span-12 lg:col-span-12">
                    <button type="button" className="button bg-theme-4 text-white" onClick={this.handleCreateTransaction}>{
                        this.state.transaction_details.id != null ? Lang().transaction.save : Lang().transaction.create
                    }</button>
                </div>
            </div>
        </>

    }
}
