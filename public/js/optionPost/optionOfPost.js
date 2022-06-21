$(document).ready(function (){
    var idPost = '';

    //get url
    function getUrl()
    {
        var locationCurrent = $(location).attr("href");
        var indexSubstring = locationCurrent.lastIndexOf('/');

        return url = locationCurrent.substring(indexSubstring);
    }

    //delete post
    $(document).on('click', '.delete-option-post-profile', function (){
        idPost = $(this).data('post-id');
    })
    $('#btn_confirm_delete_post').click(function (){
        $.ajax({
            url:'/profile/deletePost',
            type:'DELETE',
            dataType: 'json',
            data: {'idPost':idPost},
            success: function (data){
                if(data['status_code'] == 200)
                {
                    $('#btn_close_comfirm_table').click();
                    $('#post_id_'+data['postId']).remove();
                }
                else
                {
                    console.log(data['Message']);
                }
            }
        })
    });

    //edit post
    $(document).on('click', '.edit-option-post-profile', function (){
        idPost = $(this).data('post-id');
        console.log(idPost);
        getInforPost(idPost);

    })
    function getInforPost(idPost)
    {
        $.ajax({
            url: '/post/getInforPost',
            type: 'GET',
            dataType:'json',
            data:{'idPost': idPost},
            success: function (data){
                if(data['status_code'] == 200)
                {
                    $('#post_id_edit').val(idPost);
                    $('#caption_edit_post_in_table').html(data['caption']);
                    $('#img_preview_edit').attr('src', '../image/post/' + data['image']);
                    $('#btn_add_img_post_in_table').html('Change image');
                    $('#img_preview_edit').show();
                    $('#edit_option_post_profile').click();
                    console.log(data);
                }
                else
                {
                    console.log(data['Message']);
                }
            }
        })

    }
    var editTable =
        ' <div class="modal fade" id="edit_post_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
        '        <div class="modal-dialog modal-dialog-custom-new-post">\n' +
        '            <div class="modal-content">\n' +
        '                <div class="modal-header">\n' +
        '                    <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>\n' +
        '                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn_close_table_new_post"></button>\n' +
        '                </div>\n' +
        '                <form method="POST" action="/post/updatePost" enctype="multipart/form-data" id="edit_post_form">\n' +
        '                <div class="modal-body new-post-table-body">\n' +
        '                        <div class="new-post-table">\n' +
        '                            <div class="img-post-in-table">\n' +
        '                                <div class="btn-add-img-post-in-table" id="btn_add_img_edit_post_in_table">Add image</div>\n' +
        '                                <div class="btn-add-img-post-in-table" id="btn_delete_preview_img_edit_in_table">Delete</div>\n' +
        '                                <input type="file" id="input_up_img_edit" name="imgPost" hidden>\n' +
        '                                <input type="hidden" id="post_id_edit" name="postId" value="">\n' +
        '                                <img src="" alt="" id="img_preview_edit">\n' +
        '                            </div>\n' +
        '                            <div class="caption-post-in-table">\n' +
        '                                <textarea placeholder="write something..." name="captionPost" id="caption_edit_post_in_table" cols="30" rows="10" class="text-area-caption-in-table"></textarea>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                </div>\n' +
        '                <div class="modal-footer">\n' +
        '                        <div class="error-message" id="error_up_edit_post" style="transform: translateX(-20rem); color: red">\n' +
        '                        </div>\n' +
        '                    <button type="submit" class="btn btn-primary" id="btn_submit_post">Update Post</button>\n' +
        '                </div>\n' +
        '                </form>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>'
    $('.root-body-profile').append(editTable);
    //change image up post
    $('.img-post-in-table').mouseenter(function (){
        if($('#img_preview_edit').attr('src') != '')
        {
            $('#btn_add_img_edit_post_in_table').html('Change image');
            $('#img_preview_edit').hide();
        }
    })
    $('.img-post-in-table').mouseleave(function (){
        if($('#img_preview_edit').attr('src') != '')
        {
            $('#img_preview_edit').show();
        }
    });
    //add image edit
    $('#btn_add_img_edit_post_in_table').on('click', function() {
        $('#input_up_img_edit').click();
    });
    //image preview before up post
    $('#input_up_img_edit').change(function (){

        PreviewImageEdit()
        $('#img_preview_edit').show();

    })
    function PreviewImageEdit() {
        var imageReader = new FileReader();
        imageReader.readAsDataURL(document.getElementById("input_up_img_edit").files[0]);

        imageReader.onload = function (oFREvent) {
            document.getElementById("img_preview_edit").src = oFREvent.target.result;
        };
    }
    // delete preview img edit post
    $('#btn_delete_preview_img_edit_in_table').click(function (){
        $('#img_preview_edit').attr('src', '');
        $('#input_up_img_edit').val('');
        $('#btn_add_img_edit_post_in_table').html('Add image');
    })
    //save post just update
    $('#edit_post_form').submit(function(e){
        var caption = $('#caption_edit_post_in_table').val();
        var image = $('#input_up_img_edit')[0].files;

        if(caption == '' && image.length === 0)
        {
            $('#error_up_edit_post').html('please enter caption or choose image');
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
                        location.href = '/profile/' + data['userId'];
                    }
                    else
                    {
                        $('#error_up_edit_post').html(data['Message'])
                    }

                }
            })
        }
    });

    var reportPostId = '';
    //send report
    $('.report-option-post-profile').click(function (){
        reportPostId = $(this).data('post-id');
    })
    $('#report_post_form').submit(function (e){
        var captionReport = $('#report_caption').val();

        if(captionReport == '')
        {
            e.preventDefault();
            $('#error_report_post').html('Write something!');
        }
        else
        {
            e.preventDefault();
            $.ajax({
                type:'PUT',
                url: $(this).attr('action'),
                data:{'captionReport':captionReport, 'reportPostId':reportPostId},
                success: function (data){
                    if(data['status_code'] == 200)
                    {
                        $('#report_caption').val('');
                        $('#btn_close_table_report_post').click();
                    }
                    else
                    {
                        $('#error_report_post').html(data['Message'])
                    }

                }
            })
        }
    })

})