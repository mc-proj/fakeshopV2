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

    $(".sous-liste-normale").on("mouseover", function() {

         //ajout pseudo element pour avoir la barre rouge au dessus du texte
        $(this).parent().prev().addClass("special");
        $(this).parent().prev().css("color", "rgb(202, 64, 64)");
    })

    $(".sous-liste-normale").on("mouseleave", function() {

        //retrait pseudo element pour avoir la barre rouge au dessus du texte
       $(this).parent().prev().removeClass("special");
       $(this).parent().prev().css("color", "black");
   })

   $(".sous-liste-laterale").on("mouseover", function() {

        //ajout pseudo element pour avoir la barre rouge au dessus du texte
        $(this).parent().prev().addClass("special");
        $(this).parent().prev().css("color", "rgb(202, 64, 64)");
    })

    $(".sous-liste-laterale").on("mouseleave", function() {

    //retrait pseudo element pour avoir la barre rouge au dessus du texte
        $(this).parent().prev().removeClass("special");
        $(this).parent().prev().css("color", "black");
    })


    //gestion recherche dynamique
    let tempo = null;

    $("#texte-recherche").on("keyup", function() {

        if(tempo != null) {

            clearTimeout(tempo);
            tempo = null;
        }

        let debut = $(this).val();

        tempo = setTimeout(function() {

            $.ajax({
                type: "POST",
                url: "/recherche",
                data: {
                    debut: debut
                },
                success: function(response) {

                    let results = JSON.parse(response);
                    $("#resultats-recherche").empty();

                    if(results.length == 0) {

                        $("#resultats-recherche").slideUp();
                    }

                    else {

                        for(result of results) {
                            
                            $("#resultats-recherche").append("<a href='#'>" + result.nom + "</a><br>");
                        }
                        $("#resultats-recherche").slideDown();
                    }    
                },
                error: function(err) {

                    console.log(err);
                }
            })
        }, 1000);
    })

    $("#bouton-cookies").on("click", function() {

        $("#container-cookies").css("display", "none");
    })
})