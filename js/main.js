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


document.addEventListener('DOMContentLoaded', function() {
    // Mapea los enlaces con sus secciones
    const sections = ['about', 'service', 'menu'];
    const navLinks = {
        'about': document.getElementById('about-link'),
        'service': document.getElementById('service-link'),
        'menu': document.getElementById('menu-link'),
        'inicio': document.getElementById('inicio-link')
    };

    function removeActive() {
        Object.values(navLinks).forEach(link => link.classList.remove('active'));
    }

    function setActiveSection() {
        let scrollPos = window.scrollY || window.pageYOffset;
        let found = false;
        sections.forEach(sectionId => {
            let section = document.getElementById(sectionId);
            if (section) {
                let top = section.offsetTop - 80; // Ajusta según tu navbar
                let bottom = top + section.offsetHeight;
                if (scrollPos >= top && scrollPos < bottom) {
                    removeActive();
                    navLinks[sectionId].classList.add('active');
                    found = true;
                }
            }
        });
        // Si no está en ninguna sección, activa "Inicio"
        if (!found) {
            removeActive();
            navLinks['inicio'].classList.add('active');
        }
    }

    window.addEventListener('scroll', setActiveSection);
    setActiveSection();

    // Opcional: al hacer clic, activa manualmente el enlace
    Object.entries(navLinks).forEach(([key, link]) => {
        link.addEventListener('click', function() {
            removeActive();
            link.classList.add('active');
        });
    });
});
