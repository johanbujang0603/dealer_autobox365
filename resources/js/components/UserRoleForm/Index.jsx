import React from 'react'
import Lang from "../../Lang/Lang";
import CheckBox from '../../global_ui_components/CheckBox';
const JSON_DATA = JSON.parse(document.getElementById('role_form_data').innerHTML)
import Switch from "react-switch";
import Axios from 'axios';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
export default class Index extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            id: null,
            role_name: '',
            permissions: [],
            permission_apps: JSON_DATA['permission_apps']
        }

        this.handleSwitch = this.handleSwitch.bind(this)
        this.hasPermission = this.hasPermission.bind(this)
        this.handleCheckCol = this.handleCheckCol.bind(this)
        this.handleUnCheckCol = this.handleUnCheckCol.bind(this)
        this.hasPermissionCol = this.hasPermissionCol.bind(this)
        this.handleCheckRow = this.handleCheckRow.bind(this)
        this.handleUnCheckRow = this.handleUnCheckRow.bind(this)
        this.hasPermissionRow = this.hasPermissionRow.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getLoadData = this.getLoadData.bind(this)
    }
    componentDidMount() {
        let urlParams = this.getParams(document.currentScript.src)
        if (urlParams.mode == 'edit') {
            this.getLoadData(urlParams.roleId)
        }
    }
    getLoadData(roleId) {
        this.setState({
            is_loading: true
        }, () => {
            Axios.get(`/users/roles/ajax_load/${roleId}`)
                .then(response => {
                    this.setState({
                        ...this.state,
                        ...response.data
                    })
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
    handleSwitch(app_id, checked, permission) {
        let permissions = this.state.permissions
        let permission_index = permissions.findIndex(element => {
            return element.app_id == app_id
        })
        if (permission_index == -1) {
            permissions.push({
                app_id: app_id,
                read: false,
                write: false,
                delete: false,
                [permission]: checked
            })
        }
        else {
            permissions[permission_index][permission] = checked
        }
        this.setState({
            permissions: permissions
        })
    }
    hasPermission(app_id, permission) {
        let permissions = this.state.permissions
        let permission_index = permissions.findIndex(element => {
            return element.app_id == app_id
        })
        if (permission_index != -1) {
            return permissions[permission_index][permission]
        }
        else {
            return false
        }
    }
    hasPermissionCol(permission) {
        let hasPermission = true
        let permission_apps = this.state.permission_apps
        let permissions = this.state.permissions
        permission_apps.map((app, index) => {
            console.log(index)
            let permission_index = permissions.findIndex(element => {
                return element.app_id == app.id
            })
            console.log(permission_index)
            if (permission_index == -1) {
                hasPermission = false
            }
            else {
                if (permissions[permission_index][permission] == false) {
                    hasPermission = false
                }
            }
        })
        console.log(permission, hasPermission)
        return hasPermission
    }
    handleCheckCol(permission) {
        this.state.permission_apps.map((permission_app) => {
            this.handleSwitch(permission_app.id, true, permission)
        })
    }
    handleUnCheckCol(permission) {
        this.state.permission_apps.map((permission_app) => {
            this.handleSwitch(permission_app.id, false, permission)
        })
    }
    hasPermissionRow(app_id) {
        let hasPermission = true
        let permissions = this.state.permissions

        let permission_index = permissions.findIndex(element => {
            return element.app_id == app_id
        })
        console.log(permission_index)
        if (permission_index == -1) {
            hasPermission = false
        }
        else {
            if (permissions[permission_index].read == false || permissions[permission_index].write == false || permissions[permission_index].delete == false) {
                hasPermission = false
            }
        }

        return hasPermission
    }
    handleUnCheckRow(app_id) {
        this.handleSwitch(app_id, false, 'read')
        this.handleSwitch(app_id, false, 'write')
        this.handleSwitch(app_id, false, 'delete')
    }
    handleCheckRow(app_id) {
        this.handleSwitch(app_id, true, 'read')
        this.handleSwitch(app_id, true, 'write')
        this.handleSwitch(app_id, true, 'delete')
    }
    handleSubmit(e) {
        e.preventDefault()
        let post_data = {
            role_name: this.state.role_name,
            permissions: this.state.permissions,
            id: this.state.id
        }
        Axios.post('/users/roles/save', post_data)
            .then((response) => {
                console.log(response.data)
                if (response.data.status == 'success') {
                    this.setState({
                        id: response.data.role_id
                    }, () => {
                        confirmAlert({
                            title: response.data.option == 'created' ? "You created a role successfully!" : "You updated a role successfully!",
                            message: response.data.option == 'created' ? 'Do you want to create new role continue?' : "Do you want to stay in this page?",
                            buttons: [
                                {
                                    label: 'Yes',
                                    onClick: () => response.data.option == 'created' ? location.href = "/users/roles/create" : null
                                },
                                {
                                    label: 'No, Thanks',
                                    onClick: () => location.href = "/users/roles/index"
                                }
                            ]
                        });
                    })

                }
            })
        console.log('submitting', this.state.permissions)
    }
    render() {
        return <form onSubmit={this.handleSubmit}>
            <div className="mt-5">
                <label className="font-medium">{Lang().user.name}:</label>
                <input type="text" className="input border w-full" placeholder="Enter Role Name" value={this.state.role_name} onChange={(e) => {
                    this.setState({ role_name: e.target.value })
                }} />
            </div>
            <div className="overflow-x-auto mt-5">
                <label className="font-medium">{Lang().user.permission}:</label>

                <table className="table">
                    <thead>
                        <tr>
                            <th className='border-b-2 whitespace-no-wrap'>#</th>
                            <th className='border-b-2 whitespace-no-wrap'>Apps</th>
                            <th className='border-b-2 whitespace-no-wrap text-center'><label className=''>{Lang().user.read}</label>
                                <div>
                                    {
                                        this.hasPermissionCol('read') == false ?
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleCheckCol('read')}><i className='mi-check'></i>{Lang().user.check_all}</a>
                                            :
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleUnCheckCol('read')}><i className='mi-close'></i>{Lang().user.uncheck_all}</a>
                                    }
                                </div>
                            </th>
                            <th className='border-b-2 whitespace-no-wrap text-center'><label className=''>{Lang().user.write}</label>
                                <div>
                                    {
                                        this.hasPermissionCol('write') == false ?
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleCheckCol('write')}><i className='mi-check'></i>{Lang().user.check_all}</a>
                                            :
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleUnCheckCol('write')}><i className='mi-close'></i>{Lang().user.uncheck_all}</a>
                                    }
                                </div>
                            </th>
                            <th className='border-b-2 whitespace-no-wrap text-center'><label className=''>{Lang().user.delete}</label>
                                <div>
                                    {
                                        this.hasPermissionCol('delete') == false ?
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleCheckCol('delete')}><i className='mi-check'></i>{Lang().user.check_all}</a>
                                            :
                                            <a className='text-theme-4 cursor-pointer' onClick={() => this.handleUnCheckCol('delete')}><i className='mi-close'></i>{Lang().user.uncheck_all}</a>
                                    }
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            this.state.permission_apps.map((permission_app, index) => {
                                return <tr key={index} className=''>
                                    <td style={{ width: 20 }}>
                                        {
                                            this.hasPermissionRow(permission_app.id) == false ? <a className='text-theme-4 cursor-pointer' onClick={() => this.handleCheckRow(permission_app.id)}><i className='mi-check'></i></a>
                                                : <a className='text-theme-4 cursor-pointer' onClick={() => this.handleUnCheckRow(permission_app.id)}><i className='mi-close'></i></a>
                                        }
                                    </td>
                                    <td className='text-left'>{permission_app.app_name}</td>
                                    <td className='text-center'>
                                        <Switch offColor={'#D32929'} onChange={(checked) => {
                                            this.handleSwitch(permission_app.id, checked, 'read')
                                        }} checked={this.hasPermission(permission_app.id, 'read')} />
                                    </td>
                                    <td className='text-center'>
                                        <Switch offColor={'#D32929'} onChange={(checked) => {
                                            this.handleSwitch(permission_app.id, checked, 'write')
                                        }} checked={this.hasPermission(permission_app.id, 'write')} />
                                    </td>
                                    <td className='text-center'>
                                        <Switch offColor={'#D32929'} onChange={(checked) => {
                                            this.handleSwitch(permission_app.id, checked, 'delete')
                                        }} checked={this.hasPermission(permission_app.id, 'delete')} />
                                    </td>
                                </tr>
                            })
                        }
                    </tbody>
                </table>
            </div>
            <div className="mt-2 flex justify-center">
                <button type="submit" className="button bg-theme-1 text-white">{Lang().user.save}</button>
            </div>
        </form>
    }
}
