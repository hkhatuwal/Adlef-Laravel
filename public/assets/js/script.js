// Initialize AOS library if needed (assuming AOS is a library for animations)
AOS.init();


/* Mobile menu handling */
const menuToggle = document.getElementById('menu-toggle');
const menuItems = document.getElementById('menu-items');

menuToggle.addEventListener('click', function () {
    menuItems.classList.toggle('opacity-0');
    // Toggle opacity class for smooth transition
    console.log("Menu toggled"); // Optional console message for debugging or logging
});

/* Dropdown menu hover interaction */
const dropdownButton = document.querySelector('.dropdown-button');
const dropdownContent = document.querySelector('.dropdown-content');

dropdownButton.addEventListener('mouseenter',
    () => {
        dropdownContent.classList.remove('hidden');
    });

dropdownContent.addEventListener('mouseleave', () => {
    dropdownContent.classList.add('hidden');
});

/* Smooth navbar hiding/showing on scroll */
const navbar = document.querySelector('.navbar');
let lastScrollTop = 0;
window.addEventListener('scroll', function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop) {
        navbar.style.top = '-80px';
    } else {
        // Scroll up - show navbar
        navbar.style.top = '0';

    }

    lastScrollTop = scrollTop;
});


const swiper = new Swiper('.swiper', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    breakpoints: {
        480: {
            slidesPerView: 1,
            spaceBetween: 1,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 4,
        }, 1688: {
            slidesPerView: 5,
            spaceBetween: 8,
        }
    },
    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next-custom',
        prevEl: '.swiper-button-prev-custom',
    },


});


$(document).ready(function () {
    $('.select2').select2();


    /* Registration */
    const tinProvidedRadio = document.getElementById('tin_provided');
    const tinNotProvidedRadio = document.getElementById('tin_not_provided');
    const tinInputSection = document.getElementById('tin_input_section');
    const tinReasonSection = document.getElementById('tin_reason_section');

    function updateSections() {
        if (tinProvidedRadio.checked) {
            tinInputSection.style.display = 'block';
            tinReasonSection.style.display = 'none';
        } else if (tinNotProvidedRadio.checked) {
            tinInputSection.style.display = 'none';
            tinReasonSection.style.display = 'block';
        }
    }

    tinProvidedRadio.addEventListener('change', updateSections);
    tinNotProvidedRadio.addEventListener('change', updateSections);


    /* Verification Page  */
    $('#file-upload').on('click', function () {
        $('#file-upload input').click();
    });


});
