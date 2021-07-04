import React from 'react'

class RadioBox extends React.Component{
    constructor(props){
        super(props)
    }
    render(){
        return <div className="flex items-center text-gray-700 mr-2">
                    <input type="radio" className="input border mr-2" {...this.props} />
                    <label className="cursor-pointer select-none">{this.props.label}</label>
                </div>
    }
}


export default RadioBox
