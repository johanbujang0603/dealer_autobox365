
import React, { Component } from 'react';
import Lang from "../../Lang/Lang";
import Select from 'react-select';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
const currencies = [
    "CAD",   "HKD",   "ISK",   "PHP",   "DKK",   "HUF",   "CZK",   "GBP",   "RON",   "SEK",   "IDR",   "INR",   "BRL",   "RUB",   "HRK",   "JPY",   "THB",   "CHF",   "EUR",   "MYR",   "BGN",   "TRY",   "CNY",   "NOK",   "NZD",   "ZAR",   "USD",   "MXN",   "SGD",   "AUD",   "ILS",   "KRW",   "PLN"]

const divStyle = {
    'display': 'flex',
    'justify-content': 'center',
    'align-items': 'center'
};
export default class MoneyConversion extends Component {
    constructor(props) {
        super(props)
        this.state = {
            base_currency: "USD",
            target_currency: "USD",
            value: 0,
            base_value: 0
        }
    }
    componentDidMount() {
    }
    
    handleChange (e) {
        this.setState({base_value: e.target.value});
        this.calcualteCurrency();
    }
    calcualteCurrency() {
        fetch("https://api.exchangeratesapi.io/latest?base=" + this.state.base_currency + "&symbols=" + this.state.target_currency)
        .then(res => res.json())
        .then(
            (result) => {
                const r_v = this.state.base_value * result.rates[this.state.target_currency];
                this.setState({value: r_v.toFixed(2)});
            },
            (error) => {
                console.log(error)
            }
        )
    }
    render() {
        return (
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-12 lg:col-span-12">
                    <div className="grid grid-cols-12 gap-6 mt-5">
                        <div className="col-span-2 lg:col-span-2">
                            <input type="number" className="input border w-full" onChange={(e) => {this.handleChange(e)}} />
                        </div>
                        <div className="col-span-2 lg:col-span-2">     
                            <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                isClearable={true}
                                options={currencies.map((currency, index) => {
                                    return { value: currency, label: currency }
                                })}
                                onChange={(selectedOption) => {
                                    this.setState({ base_currency: selectedOption ? selectedOption.value : "USD" }, () => {
                                        this.calcualteCurrency()
                                    })
                                }}
                            />
                        </div>
                        <div className="col-span-1 lg:col-span-1">
                            <div className="flex items-center justify-center">
                                <i className="icon-exchange"></i>
                            </div>
                        </div>
                        <div className="col-span-2 lg:col-span-2">        
                            <Select menuContainerStyle={{ 'zIndex': 9999 }}
                                isClearable={true}
                                options={currencies.map((currency, index) => {
                                    return { value: currency, label: currency }
                                })}
                                onChange={(selectedOption) => {
                                    this.setState({ target_currency: selectedOption ? selectedOption.value : "USD" }, () => {
                                        this.calcualteCurrency()
                                    })
                                }}
                            />
                        </div>
                        <div className="col-span-2 lg:col-span-2">
                            <input type="number" readOnly value={this.state.value} className="input border w-full" />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
