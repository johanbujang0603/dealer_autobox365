import React from 'react'

class CheckBox extends React.Component {
    constructor(props) {
        super(props)
    }
    render() {
        return <div className="flex items-center text-gray-700 mr-2">
            <input type="checkbox" className="input border mr-2" {...this.props} checked={this.props.checked ? 'checked' : ''} />
            <label className="cursor-pointer select-none">{this.props.label}</label>
        </div >
    }
}


export default CheckBox
