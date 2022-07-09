$(document).ready(function (){
    //add image for avatar
    $('#btn_add_img_change_avatar_in_table').on('click', function() {
        $('#input_up_img_avatar').click();
    });

    //delete preview img avatar
    $('#btn_delete_preview_img_avatar_in_table').click(function (){
        $('#img_preview_atavar').attr('src', '');
        $('#input_up_img_avatar').val('');
        $('#btn_add_img_change_avatar_in_table').html('Add image');
    })

    //image preview before up avatar
    $('#input_up_img_avatar').change(function (){
        PreviewImage()
        $('#img_preview_atavar').show();

    })
    function PreviewImage() {
        var imageReader = new FileReader();
        imageReader.readAsDataURL(document.getElementById("input_up_img_avatar").files[0]);

        imageReader.onload = function (oFREvent) {
            document.getElementById("img_preview_atavar").src = oFREvent.target.result;
        };
    };

    //change image avatar
    $('.img-post-in-table').mouseenter(function (){
        if($('#img_preview_atavar').attr('src') != '')
        {
            $('#btn_add_img_change_avatar_in_table').html('Change image');
            $('#img_preview_atavar').hide();
        }
    })
    $('.img-post-in-table').mouseleave(function (){
        if($('#img_preview_atavar').attr('src') != '')
        {
            $('#img_preview_atavar').show();
        }
    })

    //if post content is empty will not update avatar
    $('#change_avatar_form').submit(function(e){
        var image = $('#input_up_img_avatar')[0].files.length;

        if(image === 0)
        {
            $('#error_change_avatar').html('Please choose avatar');
            console.log('click')
            e.preventDefault();
        }
        else
        {
            e.preventDefault();
            var changeAvatarForm = new FormData(this);

            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                data: changeAvatarForm,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data){
                    if(data['status_code'] == 200)
                    {
                        location.href = '/profile/' + data['userId'];
                    }
                    else
                    {
                        $('#error_change_avatar').html(data['Message']);
                    }
                }
            })

        }
    });
});