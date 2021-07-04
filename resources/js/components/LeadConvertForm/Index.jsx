import React, { createRef } from 'react'
import Lang from "../../Lang/Lang";
import countryList from 'react-select-country-list'
import Select, { createFilter } from 'react-select';
import DatePicker from 'react-date-picker';
import NumberFormat from 'react-number-format';
import Axios from 'axios';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import CKEditor from '@ckeditor/ckeditor5-react';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import DocumentsUpload from '../../global_ui_components/DocumentsUpload';

const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)

const CustomOption = (option) =>

    <div {...option.innerProps}>{
        inventoryFormat(option.data)
    }{/* your component internals */}</div>

const dropzoneRef = createRef();

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

export default class LeadConvertForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {

            deals: [{
                date: new Date(),
                price: null,
                inventory: null,
                notes: null,
                user: null,
                location: null,
                currency: null,
                documents: [],
            }],
            //page details
            messaging_app_types: [
                'WhatsApp', 'Viber', 'Telegram', 'Line'
            ],
            currencies: JSON_DATA['currencies'],
            inventories: JSON_DATA['inventories'],
            locations: JSON_DATA['locations'],
            users: JSON_DATA['users'],
            tags: JSON_DATA['tags'],
            cur_currency: JSON_DATA['cur_currency'],
            countries: countryList().getData(),
            is_convertmodal_show: false
        }
        this.handleAddDeal = this.handleAddDeal.bind(this)
        this.handleUpdateDeal = this.handleUpdateDeal.bind(this)
        this.handleRemoveDeal = this.handleRemoveDeal.bind(this)
        this.handleConvert = this.handleConvert.bind(this)
        this.handleAddDocuments = this.handleAddDocuments.bind(this)
        this.handleRemoveDocument = this.handleRemoveDocument.bind(this)
        
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        this.setState({
            id: urlParams.leadId
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
    handleAddDeal() {
        let deals = this.state.deals
        deals.push({
            date: new Date(),
            price: null,
            inventory: null,
            notes: null,
            user: null,
            location: null,
            currency: null
        })
        this.setState({
            deals: deals
        })
    }

    handleUpdateDeal(value, index, field) {
        let deals = this.state.deals
        deals[index][field] = value
        this.setState({
            deals: deals
        })
    }
    handleRemoveDeal(index) {
        let { deals } = this.state
        deals.splice(index, 1)
        this.setState({
            deals: deals
        })

    }
    handleConvert() {
        let convert_data = {
            deals: this.state.deals,
            id: this.state.id
        }

        Axios.post('/leads/convert', convert_data)
            .then(response => {
                console.log(response)
                if (response.data.status == 'success') {
                    location.href = "/customers/all"
                }
            })
    }
    handleAddDocuments(files) {
        console.log(files)
        let documents = this.state.deals[0].documents
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
        let { deals } = this.state
        deals[0].documents = documents;
        this.setState({
            deals: deals
        }, () => {
            
            this.state.deals[0].documents.map((listing_document, index) => {
                if (listing_document.upload_progress == 0) {
                    let self = this
                    let formData = new FormData();
                    formData.append('file', listing_document.file_data);
                    formData.append('id', this.state.id);
                    Axios.post('/leads/documents/convert_upload', formData, {
                        onUploadProgress: function (progressEvent) {
                            let documents = self.state.deals[0].documents
                            documents[index].upload_progress = (progressEvent.loaded / progressEvent.total) * 100
                            let {deals} = self.state
                            deals[0].documents = documents
                            self.setState({
                                deals: deals
                            })
                            // Do whatever you want with the native progress event
                        },
                    })
                        .then(response => {
                            let plate_number = this.state.plate_number
                            if (response.data.status == 'success') {
                                let documents = this.state.deals[0].documents
                                documents[index].status = 'uploaded'
                                documents[index] = { ...documents[index], ...response.data.file }
                                let {deals} = self.state
                                deals[0].documents = documents
                                this.setState({
                                    deals: deals,
                                })

                            }
                        })
                }
            })
        })
    }
    handleRemoveDocument(index) {
        let { deals } = this.state
        let removeFile = deals[0].documents[index]
        console.log(removeFile)
        deals[0].documents.splice(index, 1)
        this.setState({
            deals: deals
        })
    }
    render() {
        return <div className="modal__content modal__content--xl px-5 py-10">
            <div className="text-center mb-3">
                <h5>Convert as Customer</h5>
            </div>
            {
                this.state.deals.map((deal, index) => {
                    return <div className="form" key={index}>
                        <div className="mt-5">
                            <label className="font-medium">Date of conversion:</label>
                            <DatePicker
                                onChange={(date) => this.handleUpdateDeal(date, index, 'date')}
                                className='form-control'
                                value={deal.date}
                            />
                        </div>
                        <div className="mt-5">
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
                                    this.handleUpdateDeal(selectedOption.value, index, 'inventory')
                                }}
                                // formatOptionLabel={inventoryFormat}
                                components={{ Option: CustomOption }}
                                placeholder='Choose Inventory' />
                        </div>
                        <div className="grid grid-cols-12 gap-6 mt-5">
                            <div className='col-span-12 lg:col-span-6 xxl:col-span-6'>
                                <label className="font-medium">Purchased Price:</label>
                                <NumberFormat
                                    value={deal.price}
                                    className={'input border w-full'}
                                    placeholder='Enter Price'
                                    thousandSeparator={true}
                                    onValueChange={(values) => {
                                        const { formattedValue, value } = values
                                        this.handleUpdateDeal(value, index, 'price')
                                    }} />

                            </div>
                            <div className='col-span-12 lg:col-span-6 xxl:col-span-6'>
                                <label className="font-medium">&nbsp;</label>
                                <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                    options={this.state.currencies.map((currency, index) => {
                                        return { value: currency.id, label: `${currency.currency}(${currency.symbol})` }
                                    })}
                                    onChange={selectedOption => {
                                        this.handleUpdateDeal(selectedOption.value, index, 'currency')
                                    }}
                                    value={this.state.cur_currency}
                                    placeholder='Currency'
                                    isClearable={true}
                                />
                            </div>
                        </div>
                        <div className='mt-5'>
                            <label className="font-medium">User who managed sale:</label>
                            <Select
                                onChange={selectedOption => {
                                    this.handleUpdateDeal(selectedOption.value, index, 'user')
                                }}
                                options={this.state.users.map((user, index) => {
                                    return { label: user.name, value: user.id }
                                })} />
                        </div>
                        <div className='mt-5'>
                            <label className="font-medium">Location of sale:</label>
                            <Select
                                options={this.state.locations.map((location, index) => {
                                    return { label: location.name, value: location.id }
                                })}
                                onChange={selectedOption => {
                                    this.handleUpdateDeal(selectedOption.value, index, 'location')
                                }}
                            />
                        </div>
                        <div className='mt-5'>
                            <label className="font-medium">Notes:</label>
                            <CKEditor

                                editor={ClassicEditor}
                                data={deal.notes}
                                onInit={editor => {
                                }}
                                onChange={(event, editor) => {
                                    let notes = editor.getData()
                                    this.handleUpdateDeal(notes, index, 'notes')
                                }}
                                onBlur={editor => {

                                }}
                                onFocus={editor => {
                                }}
                            />
                        </div>
                        
                        <div className='mt-5'>
                            <label className="font-medium">Documents:</label>
                            <DocumentsUpload
                                files={this.state.deals[index].documents}
                                addFiles={this.handleAddDocuments}
                                removeFile={this.handleRemoveDocument} />
                        </div>
                    </div>
                })
            }
            <div className="flex items-center flex-wrap mt-5">
                <button type="button" className="button bg-theme-6 text-white mr-5" data-dismiss="modal">Close</button>
                <button type="button" className="button bg-theme-4 text-white" onClick={this.handleConvert}>Convert</button>
            </div>
        </div>
    }
}
