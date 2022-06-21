$(document).ready(function (){

    //add image for post
    $('#btn_add_img_post_in_table').on('click', function() {
        $('#input_up_img').click();
    });

    //image preview before up post
    $('#input_up_img').change(function (){

        PreviewImage()
        $('#img_preview').show();

    })

    //delete preview img post
    $('#btn_delete_preview_img_in_table').click(function (){
        $('#img_preview').attr('src', '');
        $('#input_up_img').val('');
        $('#btn_add_img_post_in_table').html('Add image');
    })

    function PreviewImage() {
        var imageReader = new FileReader();
        imageReader.readAsDataURL(document.getElementById("input_up_img").files[0]);

        imageReader.onload = function (oFREvent) {
            document.getElementById("img_preview").src = oFREvent.target.result;
        };
    };

    //change image up post
    $('.img-post-in-table').mouseenter(function (){
        if($('#img_preview').attr('src') != '')
        {
            $('#btn_add_img_post_in_table').html('Change image');
            $('#img_preview').hide();
        }
    });
    $('.img-post-in-table').mouseleave(function (){
        if($('#img_preview').attr('src') != '')
        {
            $('#img_preview').show();
        }
    });

    //if post content is empty will not up post else will be up load by ajax
    $('#new_post_form').submit(function(e){
        var caption = $('#caption_post_in_table').val();
        var image = $('#input_up_img')[0].files;

        if(caption == '' && image.length === 0)
        {
            $('#error_up_post').html('please enter caption or choose image');
            e.preventDefault();
        }
        else
        {
            e.preventDefault();
            var postForm = new FormData(this);

            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                data:postForm,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data){
                    if(data['status_code'] == 200)
                    {
                        location.href = '/profile/'+data['userId'];
                    }
                    else
                    {
                        $('#error_up_post').html(data['Message'])
                    }

                }
         })

        }
    });




});