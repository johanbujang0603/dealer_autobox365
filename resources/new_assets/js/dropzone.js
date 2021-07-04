import Dropzone from 'dropzone'
import jqueryConfirm from '../../../node_modules/jquery-confirm/dist/jquery-confirm.min.js'
import jqueryConfirmCSS from '../../../node_modules/jquery-confirm/dist/jquery-confirm.min.css'

(function($) { 
    "use strict";
        
    // Dropzone
    Dropzone.autoDiscover = false
    if ($('#document_upload_form').length != 0) {
        let options = {
            autoProcessQueue: false,
            dictDefaultMessage: 'Drop document files to upload <span>or CLICK</span>',
            maxFilesize: 1024, // MB
            addRemoveLinks: true,
            paramName: "file", // The name that will be used to transfer the file
            accept: (file, done) => {
                console.log("Uploaded")
                done()
            }
        }

        if ($(this).data('single')) {
            options.maxFiles = 1
        }

        if ($(this).data('file-types')) {
            options.accept = (file, done) => {
                if ($(this).data('file-types').split('|').indexOf(file.type) === -1) {
                    alert("Error! Files of this type are not accepted")
                    done("Error! Files of this type are not accepted")
                } else {
                    console.log("Uploaded")
                    done()
                }
            }
        }
        let dz = new Dropzone('#document_upload_form', options)

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!")
        })
        dz.on("sending", function(file, xhr, data) {
            data.append("leads", $('.select2[name ="leads"]').val());
            data.append("customers", $('.select2[name ="customers"]').val());
            data.append("locations", $('.select2[name ="locations"]').val());
            data.append("users", $('.select2[name ="users"]').val());
            data.append("description", $('.doc-description').val());
            data.append("inventories", $('.select2[name ="inventories"]').val());
        });
        dz.on("complete", function (file) {
            $.confirm({
                title: '<h2>You have uploaded documents successfully<h2>',
                content: '<p>Are you sure you want to upload new documents?</p>',
                theme: 'supervan',
                buttons: {
                    confirm: function () {
                        location.href = '/documents/create';
                    },
                    cancel: function () {
                        location.href = '/documents/index';
                    },
                }
            });
        });
        $("#upload-doc").on('click', function() {
            dz.processQueue();
        });
    }
    else if ($('#upload-documents-modal').length != 0) {
        
        let options = {
            autoProcessQueue: false,
            dictDefaultMessage: 'Drop document files to upload <span>or CLICK</span>',
            maxFilesize: 1024, // MB
            addRemoveLinks: true,
            paramName: "file", // The name that will be used to transfer the file
            accept: (file, done) => {
                console.log("Uploaded")
                done()
            }
        }

        if ($(this).data('single')) {
            options.maxFiles = 1
        }

        if ($(this).data('file-types')) {
            options.accept = (file, done) => {
                if ($(this).data('file-types').split('|').indexOf(file.type) === -1) {
                    alert("Error! Files of this type are not accepted")
                    done("Error! Files of this type are not accepted")
                } else {
                    console.log("Uploaded")
                    done()
                }
            }
        }
        let dz = new Dropzone('#upload-documents-modal', options)

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!")
        })
        dz.on("sending", function(file, xhr, data) {
            data.append("leads", $('.select2[name ="leads"]').val());
            data.append("customers", $('.select2[name ="customers"]').val());
            data.append("locations", $('.select2[name ="locations"]').val());
            data.append("users", $('.select2[name ="users"]').val());
            data.append("description", $('.doc-description').val());
            data.append("inventories", $('.select2[name ="inventories"]').val());
        });
        dz.on("complete", function (file) {
            $("#add_documents_modal").modal('hide')
            $("#document_list").load($("#upload-documents-modal-url").val());
        });
        $("#upload-doc").on('click', function() {
            dz.processQueue();
        });
    }
    else {

        $('.dropzone').each(function() {
            let options = {
                accept: (file, done) => {
                    console.log("Uploaded")
                    done()
                }
            }
    
            if ($(this).data('single')) {
                options.maxFiles = 1
            }
    
            if ($(this).data('file-types')) {
                options.accept = (file, done) => {
                    if ($(this).data('file-types').split('|').indexOf(file.type) === -1) {
                        alert("Error! Files of this type are not accepted")
                        done("Error! Files of this type are not accepted")
                    } else {
                        console.log("Uploaded")
                        done()
                    }
                }
            }
    
            let dz = new Dropzone(this, options)
    
            dz.on("maxfilesexceeded", (file) => {
                alert("No more files please!")
            })
        })
    }

})($)