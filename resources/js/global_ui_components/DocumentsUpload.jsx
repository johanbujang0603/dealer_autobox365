import React, { createRef } from 'react';
import Dropzone from 'react-dropzone';
import FileIcon, { defaultStyles } from 'react-file-icon';
import filesize from 'filesize'
import path from 'path'

const dropzoneRef = createRef();
const openDialog = () => {
    // Note that the ref is set async,
    // so it might be null at some point
    if (dropzoneRef.current) {
        dropzoneRef.current.open()
    }
};


export default class DocumentsUpload extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            preview_files: []
        }
        this.handleDrop = this.handleDrop.bind(this)
    }

    handleDrop(files) {
        console.log(files)
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
                            <div className="p-5">
                                {this.props.files.map((file, index) => {
                                    var ext = path.extname(file.original_name);
                                    ext = (ext.replace(/\./g, ''));
                                    return <div className="flex items-start flex-wrap mb-5" key={index}>
                                        <div className="mr-3">
                                            <FileIcon extension={ext} size={40} {...defaultStyles[ext]} />
                                        </div>
                                        <div className="flex-1">
                                            <div className="media-title"><span className="font-weight-semibold">{file.original_name}</span></div>
                                            <span className="text-muted">{filesize(parseFloat(file.filesize))}</span>
                                            {file.status && file.status == 'uploading' && <div className="animation-progress-bar h-full bg-theme-1 rounded text-center text-xs text-white" style={{ width: `${file.upload_progress}%` }}>
                                                {`${parseInt(file.upload_progress)}`}%
                                            </div>}
                                            {
                                                file.status && file.status == 'uploading' && <div className="text-muted position-absolute"><i className="icon-spinner2 spinner mr-2" /> Processing...</div>
                                            }
                                        </div>
                                        <div className="ml-3">
                                            {/* <span className="badge badge-mark bg-grey-300 border-grey-300" /> */}
                                            <a onClick={() => this.props.removeFile(index)} href="#" className="button text-white bg-theme-6 inlint-block">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                })}
                            </div>
                        </aside>
                    </div>
                );
            }}
        </Dropzone>
    }
}

// Disable click and keydown behavior on the <Dropzone>
