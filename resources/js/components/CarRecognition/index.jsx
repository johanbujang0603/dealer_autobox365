
import React, { Component } from 'react'
import Axios from 'axios'
import Lang from "../../Lang/Lang"
import AttachmentForm from './AttachmentForm';
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css
import { Motion, spring } from "react-motion"

const wrapperStyles = {
    width: "100%",
    // maxWidth: 980,
    margin: "0 auto",
}

export default class CarRecognition extends Component {
    constructor(props) {
        super(props)
        this.state = {
            photos: [],
        }
        this.handleAddFiles = this.handleAddFiles.bind(this)
        this.handleRemovePhoto = this.handleRemovePhoto.bind(this)
    }
    componentDidMount() {
    }
    readUploadedPhotoPreview(imageFile) {
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
    async handleAddFiles(files) {
        let photos = this.state.photos
        for (let i = 0; i < files.length; i++) {
            if (files[i].type.includes('image')) {
                const photoPreview = await this.readUploadedPhotoPreview(files[i])
                let photo_data = {
                    image_src: photoPreview,
                    file_data: files[i],
                    status: 'uploading',
                    upload_progress: 0,
                }
                photos.push(photo_data)
            }
        }
        this.setState({
            photos: photos
        }, () => {
            this.state.photos.map((listing_photo, index) => {
                if (listing_photo.upload_progress == 0) {
                    let self = this
                    let formData = new FormData();
                    formData.append('file', listing_photo.file_data);
                    Axios.post('/inventories/uploadphoto', formData, {
                        onUploadProgress: function (progressEvent) {
                            let photos = self.state.photos
                            photos[index].upload_progress = (progressEvent.loaded / progressEvent.total) * 100
                            self.setState({
                                photos: photos
                            })
                            // Do whatever you want with the native progress event
                        },
                    })
                        .then(response => {
                            console.log(response);
                            let plate_number = this.state.plate_number
                            if (response.data.status == 'success') {
                                let photos = this.state.photos
                                photos[index].photo_data = response.data.result
                                photos[index].status = 'uploaded'
                                photos[index].countries = response.data.country
                                photos[index].extra_info = response.data.extra_info
                                photos[index].recorgnized_result = response.data.recorgnized_result
                                photos[index].plate_number = response.data.api_result.results[0].plate
                                photos[index] = { ...photos[index], ...response.data.file }
                                // photos = {...photos, ...response.data.file}
                                if (response.data.api_result.results.length) {
                                    plate_number = response.data.api_result.results[0].plate
                                }
                                this.setState({
                                    photos: photos,
                                    plate_number: plate_number
                                })

                            }
                        })
                }
            })
        })

    }
    handleRemovePhoto(index) {
        let { photos } = this.state
        let removeFile = photos[index]
        photos.splice(index, 1)
        this.setState({
            photos: photos
        })
    }
    render() {
        return (
            <div>
                <label className="font-medium">{Lang().inventories.photos}:</label>
                <AttachmentForm
                    photos={this.state.photos}
                    plate_number={this.state.plate_number}
                    addFiles={this.handleAddFiles}
                    removeFile={this.handleRemovePhoto}
                />
            </div>
        );
    }
}
