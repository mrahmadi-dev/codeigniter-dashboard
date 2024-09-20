
$('#profile_img_form').on('submit',function (e) {
    e.preventDefault()
    $.ajax({
        url: 'profile',
        type: 'POST',
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend : function()
        {
            //$("#preview").fadeOut();
            $("#err").fadeOut();
            $('#profile_img_form_errors').html('')
        },
        success: function (data) {
            swal("successful", "profile image uploaded", "success");
            $('#uploadedAvatar').attr('src',data.data)
            $('#profile_img_form input[name=csrf_test_name]').val(data.hash)
        },
        error: function (data) {
            console.log(data.responseJSON)
            data = data.responseJSON
            $('#profile_img_form_errors').html(data.message.img)
            $('#profile_img_form input[name=csrf_test_name]').val(data.hash)
        },
    })
})

$('#profile_form').on('submit',function (e) {
    e.preventDefault()
    $.ajax({
        url: 'profile',
        type: 'POST',
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        beforeSend : function()
        {
            //$("#preview").fadeOut();
            $('#profile_form_errors').html('')
            $("#err").fadeOut();
        },
        success: function (data) {
            swal("successful", "profile updated", "success");
        },
        error: function (data) {
            $('#profile_form_errors').html(data.responseJSON.s)
            $('#profile_form input[name=csrf_test_name]').val(data.responseJSON.ss)
        }
    })
})


