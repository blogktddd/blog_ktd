$(document).ready(function () {

    $('.title-login').click(function (){
        $('.title-login').addClass('active');
        $('.title-sign-up').removeClass('active');
        document.title = 'Sign In'

        $('.register-form').hide();
        $('.login-form').show();
    })

    $('.title-sign-up').click(function (){
        $('.title-sign-up').addClass('active');
        $('.title-login').removeClass('active');
        document.title = 'Sign Up';

        $('.login-form').hide();
        $('.register-form').show();
    })

    $('#register_form').submit(function (e){
        var pass = $('#register_form_password_first').val();
        var passConfirm = $('#register_form_password_second').val();

        if(pass != passConfirm)
        {
            e.preventDefault();
            $('#error_register_form').html('Confirm pass not match');
        }
    })

})