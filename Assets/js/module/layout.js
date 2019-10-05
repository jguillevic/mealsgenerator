$(document).ready(function()
{
    $("#logout-link").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        $.get(window.location.protocol + "//" + window.location.hostname + "/User/Login/Kind", function(data)
        {
            console.log(data);
            if (data === "WEBSITE")
                window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Logout"); // Renvoi vers l'adresse correspondant à l'enregistrement.
            else if (data === "FACEBOOK")
            {
                FB.getLoginStatus(function(response)
                {
                    FB.logout(function(response) 
                    {
                        window.location.assign(window.location.protocol + "//" + window.location.hostname + "/User/Logout"); // Renvoi vers l'adresse correspondant à l'enregistrement.
                    });
                });
            }
        });
    });

    window.fbAsyncInit = function() 
    {
        FB.init({
          appId: "2446860912259803"
          , autoLogAppEvents: true
          , xfbml: true
          , cookie: true // Active les cookies pour permettre au serveur d'accéder à la session.
          , version: "v4.0"
        });
    };

    (function(d, s, id) 
    {   
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/fr_FR/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));
});