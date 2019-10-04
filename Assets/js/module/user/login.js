$(document).ready(function()
{
    // Sur le clic du bouton d'inscription.
    $("#register-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        window.location.href = "Register"; // Renvoi vers l'adresse correspondant à l'enregistrement.
    });

    // Sur le changement du login.
    $("#input-login").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-login-errors").remove();
    });

    // Sur le changement du password.
    $("#input-password").on("input", function()
    {
        $(this).removeClass("error");

        $("#input-password-errors").remove();
    });

    // Sur le clic du bouton de connexion facebook.
    $("#facebook-login-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        window.location.href = "Login/Facebook"; // Renvoi vers l'adresse correspondant à l'enregistrement.
    });

    // Sur le clic du bouton de connexion twitter.
    $("#twitter-login-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        window.location.href = "Login/Twitter"; // Renvoi vers l'adresse correspondant à l'enregistrement.
    });
});