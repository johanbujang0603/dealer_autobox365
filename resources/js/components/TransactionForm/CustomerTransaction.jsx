import React from 'react'
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
    return <div className='dropdown-item p-0 mb-1'>
        <div className="d-flex align-items-center">
            <div className="mr-3">
                <a href="javascript:;">
                    <img src={inventory.make_details.brand_logo} className="rounded-circle" width={60} height={60} alt="" />
                </a>
                {
                    inventory.photo_details.length > 0 && <a href="javascript:;">
                        <img src={inventory.photo_details[0].image_src} className="car_photo" id={9} width={100} height={60} alt="" />
                    </a>
                }
            </div>
            <div>
                <a href="javascript:;" className="text-default font-weight-semibold">{inventory.make_details.name} {inventory.model_details.name} {inventory.year}</a>
                <div className="text-muted font-size-sm">
                    {inventory.generation_details ? inventory.generation_details.name : ''} - {inventory.serie_details ? inventory.serie_details.name : ''} - {inventory.trim_details ? inventory.trim_details.name : ''} - {inventory.equipment_details ? inventory.equipment_details.name : ''}
                </div>
                <div className="text-muted font-size-sm">
                    {inventory.location_details ? inventory.location_details.name : ''}
                </div>
            </div>
        </div>
    </div>
}

export default class CustomerTransaction extends React.Component {
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
                currency: null,
                lead: null,
                finance: null,
                financial_institution_name: null
            },
            documents: [],
            //page details
            currencies: [],
            inventories: [],
            locations: [],
            users: [],
            leads: [],
            countries: countryList().getData(),
            open_inventory_dropdown: false
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
        if (urlParams.customerID) {
            this.handleUpdateTransactionDetails(urlParams.customerID, 'customer_id')
        }
        this.loadData()
    }
    loadData(id) {
        let self = this
        Axios.get('/transactions/basicdata')
            .then(response => { return response.data })
            .then(result => {
                console.log(result)
                this.setState({
                    ...this.state, ...result
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
                if (document.getElementById('create_transaction_modal')) {
                    $("#create_transaction_modal").modal('hide')
                }
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
        return <><div className="p-5">
                <div className="preview mb-5">
                    <label className="font-medium">Date of sale:</label>
                    <DatePicker
                        onChange={(date) => this.handleUpdateTransactionDetails(date, 'date_of_sale')}
                        className='form-control'
                        value={transaction_details.date_of_sale}
                    />
                </div>
                <div className="preview mb-5">
                    <label className="font-medium">Inventory item purchased:</label>
                    <Select
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
                            this.setState({ open_inventory_dropdown: false })
                        }}
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
                        value={this.state.transaction_details.inventory ? { value: this.state.transaction_details.inventory, label: this.getInventoryLabel(this.state.transaction_details.inventory) } : null}
                        // formatOptionLabel={inventoryFormat}
                        // formatOptionLabel={inventoryFormat}
                        filterOption={createFilter({ ignoreAccents: false })}
                        components={{ Option: CustomOption }}
                        placeholder='Choose Inventory' />
                </div>
                <div className='preview mb-5'>
                    <label className="font-weight-semibold">User who managed sale:</label>
                    <Select
                        isMulti
                        onChange={selectedOption => {
                            console.log(selectedOption)
                            this.handleUpdateTransactionDetails(selectedOption, 'user')
                        }}
                        value={this.state.transaction_details.user}
                        options={this.state.users.map((user, index) => {
                            return { label: user.name, value: user._id }
                        })} />
                </div>
                <div className='preview mb-5'>
                    <label className="font-weight-semibold">Location of sale:</label>
                    <Select
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
                <div className="grid grid-cols-12 gap-6 mb-5">
                    <div className='col-span-12 lg:col-span-4 xxl:col-span-4'>
                        <label className="font-medium">Price of Sale:</label>
                        <NumberFormat
                            value={transaction_details.price}
                            className={'input w-full border'}
                            placeholder='Enter Price'
                            thousandSeparator={true}
                            onValueChange={(values) => {
                                const { formattedValue, value } = values
                                this.handleUpdateTransactionDetails(value, 'price')
                            }} />

                    </div>
                    <div className='col-span-12 lg:col-span-4 xxl:col-span-4'>
                        <label className="font-medium">DownPayment:</label>
                        <NumberFormat
                            value={transaction_details.down_payment_price}
                            className={'input w-full border'}
                            placeholder='Enter Price'
                            thousandSeparator={true}
                            onValueChange={(values) => {
                                const { formattedValue, value } = values
                                this.handleUpdateTransactionDetails(value, 'down_payment_price')
                            }} />

                    </div>
                    <div className='col-span-12 lg:col-span-4 xxl:col-span-4'>
                        <label className="font-medium">&nbsp;</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            options={this.state.currencies.map((currency, index) => {
                                return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                            })}
                            onChange={selectedOption => {
                                this.handleUpdateTransactionDetails(selectedOption.value, 'currency')
                            }}
                            value={this.state.transaction_details.currency ? { value: this.state.currency, label: this.getCurrencyLabel(this.state.currency) } : null}
                            placeholder='Currency'
                            isClearable={true}
                        />
                    </div>
                </div>

                <div className="preview mb-5">
                    <label>Finance:</label>
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
                {
                    this.state.transaction_details.finance == 'Yes' && <div className="preview mb-5">
                        <label className="font-medium">Financial Institution Name</label>
                        <input name="financial_institution_name" className='input w-full border' value={this.state.transaction_details.financial_institution_name} onChange={(e) => { this.handleUpdateTransactionDetails(e.target.value, 'financial_institution_name') }} />
                    </div>
                }

                <div className="preview mb-5">
                    <label className="font-medium">Date of Estimate Delivery:</label>
                    <DatePicker
                        onChange={(date) => this.handleUpdateTransactionDetails(date, 'date_of_estimate_delivery')}
                        className='input border w-full'
                        value={transaction_details.date_of_estimate_delivery}
                    />
                </div>
                <div className="preview mb-5">
                    <label className="font-medium">Documents:</label>
                    <DocumentsUpload
                        files={this.state.documents}
                        addFiles={this.handleAddDocuments}
                        removeFile={this.handleRemoveDocument} />
                </div>
                <div className='preview mb-5'>
                    <label className="font-medium">Notes:</label>
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

            <div className="p-5">
                <button type="button" className="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" onClick={this.handleCreateTransaction}>{
                    this.state.transaction_details.id != null ? "Save" : "Create"
                }</button>
            </div>
        </>
    }
}
