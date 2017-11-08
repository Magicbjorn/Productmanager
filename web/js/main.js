+ function($) {
    $(document).ready(function() {
        var url = location.href;
        var urlarray = url.split('/');
        var urlpart = urlarray[3];
        // if (urlpart == "components" || urlpart == "component") {
        //     //document.getElementById('entityselector').checked = true;
        //     $('#txtcomponents').removeClass('entity-text-notselected');
        //     $('#txtcomponents').addClass('entity-text-selected');
        //     $('#txtbuilds').removeClass('entity-text-selected');
        //     $('#txtbuilds').addClass('entity-text-notselected');
        // }
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
        $('#menu-builds').on('click', function() {
            $('#buildsCollapsible').slideToggle(500);
        });
        $('#menu-components').on('click', function() {
            $('#componentsCollapsible').slideToggle(500);
        });
        $('#menu-settings').on('click', function() {
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
        // $('#entityselector').on('click', function() {
        //     if (document.getElementById('entityselector').checked) {
        //         $('#txtcomponents').removeClass('entity-text-notselected');
        //         $('#txtcomponents').addClass('entity-text-selected');
        //         $('#txtbuilds').removeClass('entity-text-selected');
        //         $('#txtbuilds').addClass('entity-text-notselected');
        //         var url = Routing.generate('component_index');
        //         location.href = url;
        //         entityswitchtoggled = true;
        //     } else {
        //         $('#txtcomponents').addClass('entity-text-notselected');
        //         $('#txtcomponents').removeClass('entity-text-selected');
        //         $('#txtbuilds').addClass('entity-text-selected');
        //         $('#txtbuilds').removeClass('entity-text-notselected');
        //         var url = Routing.generate('build_index');
        //         location.href = url;
        //         entityswitchtoggled = false;
        //     }
        // });
        $('#builds').on('click', function() {
            var url = Routing.generate('build_index');
            location.href = url;
        });
        $('#components').on('click', function() {
            var url = Routing.generate('component_index');
            location.href = url;
        });
    });
}(jQuery);