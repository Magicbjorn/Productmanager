+function ($) {
    $(document).ready(function() {
    $('#searchclear').on('click', function() {
        $('#searchinput').val('');
    });
    $('#btnShowLogin').on('click', function() {
        $('#btnShowLogin').blur();
        if ($('#hiddenlogin').hasClass("loginregisterformpadding")) {
            $('#hiddenlogin').removeClass("loginregisterformpadding");
            $('#hiddenlogin').slideToggle(500);
        } else {
            $('#hiddenlogin').addClass("loginregisterformpadding");
            $('#hiddenlogin').slideToggle(500);
        }
    });
    $('#btnLogin').on('click', function() {
        var urlToRoute = Routing.generate('account_login');
        var username = $('#username').val();
        var password = $('#password').val();
        $.ajax({
            type: "POST",
            url: urlToRoute,
            dataType: "json",
            data: {
                "username": username,
                "password": password
            },
            async: true,
            crossDomain: true,
            complete: function(xhr) {
                console.log(xhr);
                if (xhr.status == 200) {
                    location.href = urlToRoute
                }
            }
        });
        return false;
    });
    $('#btnShowRegister').on('click', function() {
        console.log("KOP");
    });
});

}(jQuery);