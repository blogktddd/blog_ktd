$(document).ready(function (){

    //send invite friend
    $('#send_invite_friend').click(function (){
        var userId = $(this).data('user-id');

        $.ajax({
            url:'/sendInviteFriend',
            type:'PUT',
            data:{'userId':userId},
            success:function (data){
                if(data['status_code'] == 200)
                {
                    location.href = '/profile/' + userId;
                }
            }
        })
    })

    //accept friend
    $('#btn_accept_invite_friend').click(function (){
        var senderId = $(this).data('sender-id');

        $.ajax({
            url:'/acceptFriend',
            type:'PUT',
            data:{'senderId':senderId},
            success:function (data){
                if(data['status_code'] == 200)
                {
                    location.href = '/profile/' + senderId;
                }
            }
        })
    });

    var friendId = '';

    //unfriend
    $('#btn_unfriend').click(function (){
        friendId = $(this).data('friend-id');
    });
    $('#btn_confirm_unfriend_post').click(function (){
        $.ajax({
            url:'/unFriend',
            type:'DELETE',
            data:{'friendId':friendId},
            success:function (data){
                if(data['status_code'] == 200)
                {
                    location.href = '/profile/' + friendId;
                }
            }
        })
    })

})
