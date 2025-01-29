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

function copyToClipboard(elementId) {
    const text = $(`#${elementId}`).text();
    const button = $(`[onclick="copyToClipboard('${elementId}')"]`);

    navigator.clipboard.writeText(text).then(() => {
        // Show success feedback
        button.html(`
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                `);

        // Reset after 2 seconds
        setTimeout(() => {
            button.html(`
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    `);
        }, 2000);

        toastr.success("Copied to clipboard")
    }).catch(err => {
        console.error('Failed to copy text: ', err);
        // Show error feedback
        button.html(`
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                `);
    });
}

// Make copyToClipboard function globally accessible
window.copyToClipboard = copyToClipboard;


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
