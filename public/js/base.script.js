$(document).ready(function() {

    //apparition-disparition du formulaire de recherche
    //au clic sur l'icone "loupe"
    $("#icone-recherche").on("click", function() {

        $(".ligne-recherche").slideToggle();
    })

    //disparition du formulaire de recherche
    //au clic sur la croix de fermeture du formulaire
    $("#croix-recherche").on("click", function() {

        $("#icone-recherche").trigger("click");
    })

    //les listes deroulantes du menu de navigation se deroulent au survol
    $(".liste").hover(function() {

        $(this).find('[data-toggle=dropdown]').dropdown('toggle');
    })

    $("#bouton-fermeture-lateral").on("click", function() {

        if($("#menu-lateral").is(":visible")) {

            $("#cache").css("display", "none");
        }

        else {

            $("#cache").css("display", "block");
        }

        $("#menu-lateral").animate({'width': 'toggle'}, 300);
    });

    $("#burger-button").on("click", function() {

        $("#bouton-fermeture-lateral").trigger("click");
    })

    ///--------------

    // $("#burger-button").on("shown.bs.collapse", function() {

    //     alert("ok");
    // })

    // $("#navbarNav").on("toggle", function() {

    //     console.log("ok");
    // })

    $(window).resize(function(){
        
        if($("#burger-button").is(":visible")) {

            console.log("burger visible");
        }

        else {

            console.log("burger invisible");
        }
    })
})