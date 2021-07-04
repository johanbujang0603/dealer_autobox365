import React from 'react'
import Axios from 'axios';
import Select from 'react-select';
import countryList from 'react-select-country-list'

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
    return <div className='flex items-center px-1 py-1'>
        <span className={`w-5 h-5 rounded-full bg-${value} badge-pill`}>&nbsp;&nbsp;</span>&nbsp;{label}
    </div>
}
export default class LeadFilterForm extends React.Component {
    constructor(props) {
        super(props)
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
            leads: []
        }
        this.getNextFormPrams = this.getNextFormPrams.bind(this)
        this.handleFilterLeads = this.handleFilterLeads.bind(this)
        this.handleRemoveLead = this.handleRemoveLead.bind(this)
        this.getLeads = this.getLeads.bind(this)
    }
    componentDidMount() {
        Axios.get(`/car2db/vehicle_types`)
            .then(Response => {
                return Response.data
            }).then(data => {
                this.setState({
                    vehicle_types: data
                })
            })
        Axios.get(`/car2db/transmissions`)
            .then(Response => {
                return Response.data
            }).then(data => {
                this.setState({
                    transmissions: data
                })
            })
    }

    getNextFormPrams(name) {
        if (name == 'vehicle_type') {
            Axios.get(`/car2db/${this.state.vehicle_type}/makes`)
                .then(response => {
                    this.setState({
                        makes: response.data,
                        make: null,
                        select_make: null,

                        models: [],
                        model: null,
                        select_model: null,

                        generations: [],
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'make') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.make}/models`)
                .then(response => {
                    this.setState({
                        models: response.data,
                        model: null,
                        select_model: null,

                        generations: [],
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'model') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/generations`)
                .then(response => {
                    this.setState({
                        generations: response.data,
                        generation: null,
                        select_generation: null,

                        series: [],
                        serie: null,
                        select_serie: null,

                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'generation') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/${this.state.generation}/series`)
                .then(response => {
                    this.setState({
                        series: response.data,
                        serie: null,
                        select_serie: null,


                        trims: [],
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'serie') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.model}/${this.state.serie}/trims`)
                .then(response => {
                    this.setState({
                        trims: response.data,
                        trim: null,
                        select_trim: null,

                        equipments: [],
                        equipment: null,
                        select_equipment: null
                    })
                })
        }
        else if (name == 'trim') {
            Axios.get(`/car2db/${this.state.vehicle_type}/${this.state.trim}/equipments`)
                .then(response => {
                    this.setState({
                        equipments: response.data,
                        equipment: null,
                        select_equipment: null
                    })
                })

        }
    }
    handleFilterLeads() {
        let search_params = {
            vehicle_type: this.state.vehicle_type,
            make: this.state.make,
            model: this.state.model,
            generation: this.state.generation,
            serie: this.state.serie,
            trim: this.state.trim,
            equipment: this.state.equipment,
            transmission: this.state.transmission,
            color: this.state.color,
        }
        // console.log(search_params)
        Axios.post(`/leads/getbyinterested`, search_params)
            .then(response => {
                return response.data
            }).then(result => {
                this.setState({
                    leads: result
                })
            })

    }
    getLeads() {
        return this.state.leads.map(lead => {
            return lead._id
        })
    }
    handleRemoveLead(index) {
        let { leads } = this.state
        leads.splice(index, 1)
        this.setState({ leads: leads })
    }
    render() {
        return <div className="grid grid-cols-12 gap-6 mt-5">
            <div className="col-span-6 lg:col-span-6">
                <h5 className="mb-5 font-medium text-theme-4">Interested in:</h5>
                <div>
                    <label>Vechicle Type</label>
                    <Select
                        options={
                            this.state.vehicle_types.map((type, index) => {
                                return { label: type.name, value: type.id_car_type }
                            })
                        }
                        onChange={(selectedOption) => {
                            this.setState({ vehicle_type: selectedOption ? selectedOption.value : null, select_vehicle_type: selectedOption }, () => {
                                this.getNextFormPrams('vehicle_type')
                            })
                        }}
                    />
                </div>
                <div className="grid grid-cols-12 gap-6 mt-5">
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Make:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_make}
                            options={this.state.makes.map((make, index) => {
                                return { value: make.id_car_make, label: make.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type}
                            onChange={(selectedOption) => {
                                this.setState({ make: selectedOption ? selectedOption.value : null, select_make: selectedOption }, () => {
                                    this.getNextFormPrams('make')
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Model:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_model}
                            options={this.state.models.map((model, index) => {
                                return { value: model.id_car_model, label: model.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type || !this.state.makes.length || !this.state.make}
                            onChange={(selectedOption) => {
                                this.setState({ model: selectedOption ? selectedOption.value : null, select_model: selectedOption }, () => {
                                    this.getNextFormPrams('model')
                                })
                            }}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-6 mt-5">
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Generation:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_generation}
                            options={this.state.generations.map((generation, index) => {
                                return { value: generation.id_car_generation, label: generation.name }
                            })}
                            isClearable={true}
                            isDisabled={!this.state.vehicle_type || !this.state.makes.length || !this.state.make || !this.state.models.length || !this.state.model}
                            onChange={(selectedOption) => {
                                this.setState({ generation: selectedOption ? selectedOption.value : null, select_generation: selectedOption }, () => {
                                    this.getNextFormPrams('generation')
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Serie:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_serie}
                            options={this.state.series.map((serie, index) => {
                                return { value: serie.id_car_serie, label: serie.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length || !this.state.generation}
                            onChange={(selectedOption) => {
                                this.setState({ serie: selectedOption ? selectedOption.value : null, select_serie: selectedOption }, () => {
                                    this.getNextFormPrams('serie')
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="grid grid-cols-12 gap-6 mt-5">
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Trim:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_trim}
                            options={this.state.trims.map((trim, index) => {
                                return { value: trim.id_car_trim, label: trim.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length
                                || !this.state.generation || !this.state.series.length
                                || !this.state.serie}
                            onChange={(selectedOption) => {
                                this.setState({ trim: selectedOption ? selectedOption.value : null, select_trim: selectedOption }, () => {
                                    this.getNextFormPrams('trim')
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Equipment:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_equipment}
                            options={this.state.equipments.map((equipment, index) => {
                                return { value: equipment.id_car_equipment, label: equipment.name }
                            })}
                            isClearable={true}
                            isDisabled={
                                !this.state.vehicle_type || !this.state.makes.length
                                || !this.state.make || !this.state.models.length
                                || !this.state.model || !this.state.generations.length
                                || !this.state.generation || !this.state.series.length
                                || !this.state.serie || !this.state.trims.length || !this.state.trim}
                            onChange={(selectedOption) => {
                                this.setState({ equipment: selectedOption ? selectedOption.value : null, select_equipment: selectedOption }, () => {
                                    this.getNextFormPrams('equipment')
                                })
                            }}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-12 gap-6 mt-5">
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Transmission:</label>
                        <Select menuContainerStyle={{ 'zIndex': 9999 }}
                            value={this.state.select_transmission}
                            options={this.state.transmissions.map((transmission, index) => {
                                return { value: transmission.id, label: `${transmission.transmission}` }
                            })}
                            isClearable={true}

                            onChange={(selectedOption) => {
                                this.setState({ transmission: selectedOption ? selectedOption.value : null, select_transmission: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                    <div className="col-span-6 lg:col-span-6">
                        <label className="font-medium">Color:</label>
                        <Select
                            options={color_list}
                            value={{ label: this.state.color ? this.state.color.charAt(0).toUpperCase() + this.state.color.slice(1) : null, value: this.state.color }}
                            isClearable={true}
                            formatOptionLabel={colorListFormat}
                            onChange={(selectedOption) => {
                                this.setState({ color: selectedOption ? selectedOption.value : null, select_color: selectedOption }, () => {
                                })
                            }}
                        />
                    </div>
                </div>

                <div className="mt-5 mb-5">
                    <button className="button bg-theme-4 text-white" onClick={this.handleFilterLeads}>Filter</button>
                </div>
            </div>
            <div className="col-span-6 lg:col-span-6">
                <ul>
                    <li className="bg-theme-2 fond-medium p-2">Team leaders</li>
                    {
                        this.state.leads.map((lead, index) => {
                            return <li key={index}>
                                <a href="javascript:;" className="media">
                                    <div className="mr-3 align-self-center text-nowrap"><i className="icon-trash" onClick={() => this.handleRemoveLead(index)} /></div>
                                    <div className="mr-3"><img src={lead.profile_image_src} className="rounded-circle" width={40} height={40} alt="" /></div>
                                    <div className="media-body">
                                        <div className="media-title fond-medium">{lead.name}</div>
                                        <span className="text-muted">
                                            {
                                                lead.country_base_residence != null && <div>
                                                    <img src={`https://www.countryflags.io/${lead.country_base_residence}/shiny/24.png`} />{countryList().getLabel(lead.country_base_residence)}
                                                </div>
                                            }
                                        </span>
                                    </div>

                                </a>
                            </li>
                        })
                    }
                </ul>
            </div>
        </div>
    }
}
