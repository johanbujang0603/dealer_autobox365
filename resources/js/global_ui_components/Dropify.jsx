import React, {createRef} from 'react'
import Dropzone from 'react-dropzone';
import Lang from "../Lang/Lang";
const dropzoneRef = createRef();
export default class Dropify extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            file: null,
            preview_image: null,
            height: 200
        }
        this.handleDrop = this.handleDrop.bind(this)
        this.handleRemoveFile = this.handleRemoveFile.bind(this)
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
    async handleDrop(files) {

        // const photoPreview = await this.readUploadedPhotoPreview(files[0])
        // this.setState({ preview_image: photoPreview }, ()=>{

        // })
        this.props.onChange(files[0])
    }

    handleRemoveFile() {
        this.setState({
            file: null, preview_image: null
        }, ()=>{
            this.props.onRemove()
        })
    }
    render() {
        return <div className="grid grid-cols-12 gap-3 mt-5 " >
            {
                this.props.defaultImage == null ?
                <Dropzone ref={dropzoneRef}  noKeyboard
                        onDrop = {this.handleDrop}
                    >
                    {({getRootProps, getInputProps, acceptedFiles}) => {
                    return (
                        <div {...getRootProps({className: 'col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 box'})}>
                            <input {...getInputProps()} />
                            <div className="dropzone border-gray-200 border-dashed dz-clickable">
                                <div className="dz-message">
                                    <h4 className="text-lg font-medium">{this.props.title}</h4>
                                    <p className="text-gray-600">{this.props.subtitle}</p>
                                    <h4 className="text-lg font-medium">
                                        Click to open files!
                                    </h4>
                                </div>
                            </div>
                        </div>
                    );
                    }}
                </Dropzone>
                    :
                    <div className="col-span-12 sm:col-span-12 md:col-span-12 xxl:col-span-12 box intro-y blog box">
                        <div className="relative image-contain mr-5 cursor-pointer zoom-in">
                            <img className="rounded-md" style={{ height: this.state.height, width: '100%', objectFit: 'contain' }} src={this.props.defaultImage} alt="" />
                            <div className="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-6 right-0 top-0 -mr-2 -mt-2 tooltipstered" style={{top: 0}}>
                                <i className="icon-bin" onClick={this.handleRemoveFile}></i>
                            </div>
                        </div>
                    </div>
            }
            {/*  */}

        </div>
    }
}
