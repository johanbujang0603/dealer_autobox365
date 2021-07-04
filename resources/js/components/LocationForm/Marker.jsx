import React from 'react'

export default class Marker extends React.Component{
    constructor(props){
        super(props)
    }
    render(){
        return <div>
            <img src={`https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png`}
                style = {{
                    position: 'absolute',
                    left: -13.5,
                    top: -43
                 }}
            />
        </div>
    }
}
