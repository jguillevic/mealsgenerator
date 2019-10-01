$(document).ready(function()
{
    // Sur le bouton d'insription.
    $("#register-button").click(function(event)
    {
        event.preventDefault(); // Empêche l'action par défaut.

        console.log("salut");
        console.log(window.location.href);
        window.location.href = "Register"; // Renvoi vers l'adresse correspondant à l'enregistrement.
    });
});