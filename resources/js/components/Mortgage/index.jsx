import React, { Component } from 'react';
import Lang from "../../Lang/Lang";
import NumberFormat from 'react-number-format';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

export default class Mortgage extends Component {
    constructor(props) {
        super(props)
        this.state = {
            vehicle_price: 0,
            deposit: 0,
            interest_rate: 1,
            loan_terms: 1,
            monthly_payment: 0,
            weekly_payment: 0,
            fortnightly_payment: 0,
        }
        this.handleCalculation = this.handleCalculation.bind(this)
    }
    componentDidMount() {
    }
    
    handleCalculation() {

        let monthly_payment = 0
        let weekly_payment = 0
        let fortnightly_payment = 0
        let N = this.state.loan_terms * 12;
        let r = this.state.interest_rate / 12 / 100;
        monthly_payment = r * (this.state.vehicle_price - this.state.deposit) / (1 - (Math.pow((1 + r) , ( 0 - N))))
        r = this.state.interest_rate / 52 / 100;
        N = this.state.loan_terms * 52;
        weekly_payment = r * (this.state.vehicle_price - this.state.deposit) / (1 - (Math.pow((1 + r) , ( 0 - N))))
        r = this.state.interest_rate / 26 / 100;
        N = this.state.loan_terms * 26;
        fortnightly_payment = r * (this.state.vehicle_price - this.state.deposit) / (1 - (Math.pow((1 + r) , ( 0 - N))))
        this.setState({monthly_payment: monthly_payment.toFixed(2), weekly_payment: weekly_payment.toFixed(2), fortnightly_payment: fortnightly_payment.toFixed(2)})
        
    }
    render() {
        return (
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-6 lg:col-span-6">
                    <div className="grid grid-cols-12 gap-6 mt-5">
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.vehicle_price}</label>
                            <NumberFormat
                                className={'input border w-full'}
                                placeholder='Enter Vehicle Price'
                                prefix={'$'}
                                thousandSeparator={true}
                                onValueChange={(values) => {
                                    const { formattedValue, value } = values
                                    this.setState({ vehicle_price: value })
                                }} />
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.deposit}</label>  
                            <NumberFormat
                                className={'input border w-full'}
                                placeholder='Enter Deposit'
                                prefix={'$'}
                                thousandSeparator={true}
                                onValueChange={(values) => {
                                    const { formattedValue, value } = values
                                    this.setState({ deposit: value })
                                }} />
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.loan_amount}</label>  
                            <NumberFormat
                                className={'input border w-full'}
                                placeholder='Loan Amount'
                                prefix={'$'}
                                thousandSeparator={true}
                                value={this.state.vehicle_price - this.state.deposit}
                                readOnly />       
                        </div>
                    </div>
                    
                    <div className="grid grid-cols-12 gap-6 mt-5">
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.interest_rate}</label>
                            <NumberFormat
                                className={'input border w-full'}
                                placeholder='Interest Rate'
                                suffix={' %'}
                                thousandSeparator={false} 
                                value={this.state.interest_rate}
                                onValueChange={(values) => {
                                    const { formattedValue, value } = values
                                    this.setState({ interest_rate: value })
                                }} />
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <label>Loan Term</label>  
                            <NumberFormat
                                className={'input border w-full'}
                                placeholder='Loan term'
                                suffix={' years'}
                                value={this.state.loan_terms}
                                thousandSeparator={false}
                                onValueChange={(values) => {
                                    const { formattedValue, value } = values
                                    this.setState({ loan_terms: value })
                                }} />   
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <div className="text-right">
                                <button type='button' className="button bg-theme-4 text-white" onClick={this.handleCalculation}>{Lang().mortgage.calculate}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-span-6 lg:col-span-6">
                    <div className="grid grid-cols-12 gap-6 mt-5">
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.monthly_payment}</label>
                            <NumberFormat
                                className={'input border w-full'}
                                prefix={'$ '}
                                value={this.state.monthly_payment}
                                thousandSeparator={false}
                                readOnly />
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.weekly_payment}</label>
                            <NumberFormat
                                className={'input border w-full'}
                                prefix={'$ '}
                                value={this.state.weekly_payment}
                                thousandSeparator={false}
                                readOnly /> 
                        </div>
                        <div className="col-span-4 lg:col-span-4">
                            <label>{Lang().mortgage.fortnightly_payment}</label>
                            <NumberFormat
                                className={'input border w-full'}
                                prefix={'$ '}
                                value={this.state.fortnightly_payment}
                                thousandSeparator={false}
                                readOnly /> 
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
