function openGallery(id){
    $.get('/inventories/getimages/' + id, {}, function(data){
        // $(document).data('lightGallery').destroy(true);
        if(data.length){

            $(".car_photo#" + id).lightGallery({
                dynamic: true,
                dynamicEl: data
            })
        }
        else{
            new PNotify({
                title: 'Sorry!',
                text: 'This inventory has not any photos.',
                addclass: 'bg-danger border-danger'
            });
        }

    })

}
