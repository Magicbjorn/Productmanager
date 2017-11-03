+ function($) {
    $(document).ready(function() {
        $('#searchclear').on('click', function() {
            $('#searchinput').val('');
        });
        $('#btnShowLogin').on('click', function() {
            $('#btnShowLogin').blur();
            $('#loginRegisterGroup').slideToggle(500);
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
        $('#loginToRegisterLink').on('click', function() {
            slideLoginRegister();
        });
        $('#registerToLoginLink').on('click', function() {
            slideLoginRegister();
        });
        slideLoginRegister = function() {
            $('#hiddenregister').slideToggle(500);
            $('#hiddenlogin').slideToggle(500);
        }
        $('#projects').on('click', function() {
            console.log("HIOOGOAGOAG");
            $('#projectsCollapsible').slideToggle(500);
        });
        $('#quarantines').on('click', function() {
            $('#quarantineCollapsible').slideToggle(500);
        });
        $('#segments').on('click', function() {
            $('#segmentsCollapsible').slideToggle(500);
        });
        $('#requirements').on('click', function() {
            $('#requirementsCollapsible').slideToggle(500);
        });
        $('#settings').on('click', function() {
            $('#settingsCollapsible').slideToggle(500);
        });
        $('#other').on('click', function() {
            CloseMenu();
        });
        $('#menuAText').on('click', function() {
            $('#menuAText').blur();
        });
        CloseMenu = function() {
            $('.slideout-menu').animate({
                left: -$('.slideout-menu').width()
            }, 250);
        }
    });
}(jQuery);