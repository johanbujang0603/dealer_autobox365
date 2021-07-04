import React, { createRef } from 'react';
import Dropzone from 'react-dropzone';
const dropzoneRef = createRef();
const openDialog = () => {
    // Note that the ref is set async,
    // so it might be null at some point
    if (dropzoneRef.current) {
        dropzoneRef.current.open()
    }
};


export default class AttachmentForm extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            preview_files: []
        }
        this.handleDrop = this.handleDrop.bind(this)
    }

    handleDrop(files) {
        this.props.addFiles(files)
    }
    componentWillReceiveProps(nextProps) {
    }
    render() {
        return <Dropzone ref={dropzoneRef} noKeyboard
            onDrop={this.handleDrop}
        >
            {({ getRootProps, getInputProps, acceptedFiles }) => {
                return (
                    <div className="grid grid-cols-12 gap-3 mt-5">
                        <div {...getRootProps({ className: 'col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 box' })}>
                            <input {...getInputProps()} />
                            <div className="dropzone border-gray-200 border-dashed dz-clickable">
                                <div className="dz-message">
                                    <h4 className="text-lg font-medium">Drag 'n' drop some files here</h4>
                                    <p className="text-gray-600">You can drag and drop files here or click below button to upload multiple files!</p>
                                    <h4 className="text-lg font-medium">
                                        Click to open files!
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <aside className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12">
                            <div className="grid grid-cols-12 gap-3 mt-5">
                                {this.props.photos.map((file, index) => (
                                    <div className="intro-y blog col-span-12 md:col-span-2 box" key={index}>
                                        <div className={`px-2 py-2 zoom-in ${file.status && file.status == 'uploading' && 'blog__preview'}`}>
                                            
                                            <img className="card-img-top img-fluid" src={file.image_src} alt="" />

                                            {
                                                file.status && file.status == 'uploading' && (
                                                    <div className="w-full h-4 bg-gray-400 rounded mt-3">
                                                        <div className="h-full bg-theme-1 rounded text-center text-xs text-white animation-progress-bar" style={{ width: `${file.upload_progress}%`}}>{`${file.upload_progress}`}% Complete</div>
                                                    </div>
                                                )
                                            }
                                            
                                            <div className="absolute w-full flex items-center px-5 pt-6 z-10" style={{top: 0}}>
                                                <div className="ml-3 text-white mr-auto">
                                                {
                                                    file.status && file.status == 'uploading' && <><i data-loading-icon="spinning-circles" className="w-8 h-8 mr-2" /> Processing... </>
                                                }
                                                </div>
                                            </div>
                                            {/* <div className="card-img-actions-overlay card-img-top">
                                                <a onClick={() => this.props.removeFile(index)} className="btn btn-outline bg-white text-white border-white border-2 ml-2">
                                                    Delete
                                                </a>
                                            </div> */}
                                            {
                                                file.countries != null && file.countries.length > 0 && <p className="text-danger">This plates number available in {file.countries.join()}</p>
                                            }
                                            {
                                                file.recorgnized_result != null && file.recorgnized_result.detections.length && file.recorgnized_result.detections[0].mmg.length > 0 &&
                                                <div><p className="text-danger">Make: {file.recorgnized_result.detections[0].mmg[0].make_name.toUpperCase()}</p>
                                                    <p className="text-danger">Model: {file.recorgnized_result.detections[0].mmg[0].model_name.toUpperCase()}</p>
                                                    <p className="text-danger">Year: {file.recorgnized_result.detections[0].mmg[0].gen_years}</p>
                                                </div>
                                            }
                                            
                                        </div>

                                    </div>
                                ))}
                            </div>
                        </aside>
                    </div>
                );
            }}
        </Dropzone>
    }
}

// Disable click and keydown behavior on the <Dropzone>
