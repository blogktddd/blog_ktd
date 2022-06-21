$(document).ready(function (){
    
    $('#count-friend').click(function (){
        var userId =  $(this).data('user-id');
        location.assign('/friendList/' + userId)
    });

    $('#count_posts').click(function (){
        var userId =  $(this).data('user-id');
        location.assign('/profile/' + userId)
    });

    $('#count_friend').click(function (){
        var userId = $(this).data('user-id');

        location.href = '/friendList/' + userId;
    });

    //go to friend profile
    $('.friend-in-list-profile').click(function (){
        var userId  = $(this).data('user-id');

        location.href = '/profile/' + userId;
    })
});