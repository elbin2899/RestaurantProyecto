/**
 * This function initializes various components and UI behaviors:
 * - Hides a spinner element after a short delay.
 * - Initiates WOW.js for reveal animations on scroll.
 * - Adds a sticky-top class and shadow to the navbar when scrolling past a specified point.
 * - Initializes a counter-up animation for elements with the 'counter-up' data-toggle attribute.
 * - Sets up a modal video player that plays a video when a button with class 'btn-play' is clicked,
 *   and stops the video when the modal is closed.
 */
(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
        }
    });
    

    // Facts counter ,About start section
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });

    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });


})(jQuery);
