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
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop)
    {
        navbar.classList.add('hidden');
        // Use a dedicated class for better maintainability
    } else {
        navbar.classList.remove('hidden');
    }

    lastScrollTop = scrollTop;
});
