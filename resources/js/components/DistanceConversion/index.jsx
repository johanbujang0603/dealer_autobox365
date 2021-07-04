
import React, { Component } from 'react';
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

export default class DistanceConversion extends Component {
    constructor(props) {
        super(props)
        this.state = {
            mile: 0,
            kilo: 0,
            conversion_rate: 1.69034
        }
    }
    componentDidMount() {
    }
    
    handleKChange (e) {
        this.setState({mile: e.target.value / this.state.conversion_rate, kilo: e.target.value})
    }
    handleMChange (e) {
        this.setState({kilo: e.target.value * this.state.conversion_rate, mile: e.target.value})
    }
    render() {
        return (
            <div className="grid grid-cols-12 gap-6 mt-5">
                <div className="col-span-6 lg:col-span-6">
                    <label className="font-medium">Kilometers</label>
                    <input type="number" value={this.state.kilo} className="input border w-full" onChange={(e) => {this.handleKChange(e)}}/>
                </div>
                <div className="col-span-6 lg:col-span-6">
                    <label className="font-medium">Mile</label>
                    <input type="number" value={this.state.mile} className="input border w-full" onChange={(e) => {this.handleMChange(e)}}/>
                </div>
            </div>
        );
    }
}
