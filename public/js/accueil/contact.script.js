$(document).ready(function() {

    setTimeout(function() {
        
        $("#contact-bag").hide();
    }, 3000);

    $("#contact-bag").on("click", function() {

        $(this).hide();
    })

    $(window).scroll(function() {

        let hauteur_scrollee = $(window).scrollTop() + $(window).height();
        let hauteur_message = $("#message-clients").offset().top;

        if(hauteur_scrollee >= hauteur_message) {

            $("#message-clients").css({
                'left': '0',
                'transition': 'all 800ms'
            });
        }
    })
})