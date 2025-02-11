// Initialize AOS library if needed (assuming AOS is a library for animations)
AOS.init();

// Tablet detection and sidebar handling
function isTablet() {
    console.log($(window).width());

    return $(window).width() >= 768 && $(window).width() <= 1100;
}

function toggleSidebar(hide = false) {
    const $sidebar = $('.sidebar');
    const $mainContent = $('.main');
    const $topNav = $('.top-nav');

    console.log('Toggle sidebar called. Hide:', hide);
    console.log('Sidebar found:', $sidebar.length);

    if (!$sidebar.length) {
        console.log('Sidebar element not found!');
        return;
    }

    if (hide) {
        $sidebar.css('transform', 'translateX(-100%)');
        $mainContent.removeClass('ml-64');
        $topNav.removeClass('left-64').addClass('left-0');
        console.log('Sidebar hidden');
    } else {
        $sidebar.css('transform', 'translateX(0)');
        $mainContent.addClass('ml-64');
        $topNav.addClass('left-64').removeClass('left-0');
        console.log('Sidebar shown');
    }
}

// Document ready handler
$(function () {
    console.log('Document ready');

    // Handle sidebar toggle button click
    $(document).on('click', '#sidebar-toggle', function (e) {
        e.preventDefault();
        console.log('Sidebar toggle clicked');

        const $sidebar = $('.sidebar');
        const transform = $sidebar.css('transform');
        const isHidden = transform && transform !== 'none' && transform !== 'matrix(1, 0, 0, 1, 0, 0)';

        console.log('Current transform:', transform);
        console.log('Is hidden:', isHidden);

        toggleSidebar(!isHidden);
    });

    // Check tablet size on page load
    handleTabletView();

    // Handle window resize with debounce
    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleTabletView, 250);
    });
});

// Check tablet size and handle sidebar
function handleTabletView() {
    const isTabletSize = isTablet();
    console.log('Tablet view check - Is tablet:', isTabletSize);
    toggleSidebar(isTabletSize);
}

/* Mobile menu handling */
$(document).ready(function () {
    // Mobile menu toggle
    $('#menu-toggle').on('click', function () {
        $('#menu-items').toggleClass('opacity-0');
        console.log("Menu toggled");
    });

    // Dropdown menu hover interaction
    $('.dropdown-button').on('mouseenter', function () {
        $('.dropdown-content').removeClass('hidden');
    });

    $('.dropdown-content').on('mouseleave', function () {
        $(this).addClass('hidden');
    });

    // Smooth navbar hiding/showing on scroll
    let lastScrollTop = 0;
    $(window).on('scroll', function () {
        let scrollTop = $(window).scrollTop();
        if (scrollTop > lastScrollTop) {
            $('.navbar').css('top', '-80px');
        } else {
            // Scroll up - show navbar
            $('.navbar').css('top', '0');
        }
        lastScrollTop = scrollTop;
    });
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

function showIcon(iconElement) {
    if (!iconElement.id) {
        return iconElement.text;
    }
    let icon = $(iconElement.element).data("icon");
    if (icon) {
        // Create elements using vanilla JS for performance optimization
        let wrapper = document.createElement("div");
        wrapper.className = "flex items-center";

        let img = document.createElement("img");
        img.src = icon;
        img.width = 20;
        img.height = 20;
        img.style.marginRight = "10px";
        img.style.objectFit = "contain";

        let separator = document.createElement("span");
        separator.style.width = "2px";
        separator.style.height = "25px";
        separator.style.background = "#dedddd";
        separator.style.borderRadius = "2px";
        separator.style.marginRight = "6px";

        let textNode = document.createTextNode(iconElement.text);

        wrapper.appendChild(img);
        wrapper.appendChild(separator);
        wrapper.appendChild(textNode);

        return wrapper;
    }

    return iconElement.text;
}

$(document).ready(function () {

    $('.select2').select2(
        {
            templateResult: showIcon,
            templateSelection: showIcon,
            minimumResultsForSearch: -1
        }
    );

    // Account view toggle
    $('#view-toggle').on('change', function () {
        if ($(this).is(':checked')) {
            $('#bank-accounts-view').addClass('hidden');
            $('#crypto-wallets-view').removeClass('hidden');
            $('#bank-label').removeClass('text-purple-600').addClass('text-gray-400');
            $('#circle').css('transform', 'translateX(32px)');
            $('#crypto-label').removeClass('text-gray-400').addClass('text-purple-600');
        } else {
            $('#bank-accounts-view').removeClass('hidden');
            $('#crypto-wallets-view').addClass('hidden');
            $('#bank-label').removeClass('text-gray-400').addClass('text-purple-600');
            $('#crypto-label').removeClass('text-purple-600').addClass('text-gray-400');
            $('#circle').css('transform', 'translateX(0)');
        }
    });

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

    if (tinProvidedRadio && tinNotProvidedRadio) {
        tinProvidedRadio.addEventListener('change', updateSections);
        tinNotProvidedRadio.addEventListener('change', updateSections);

    }

    /* Verification Page  */
    $('#file-upload').on('click', function () {
        $('#file-upload input').click();
    });

    // Transfer logic
    let currentStep = 1;
    const $form = $('#transferForm');
    let availableBalance = null;

    // Update progress bar
    function updateProgress() {
        $('.progress-bar').css('width', currentStep === 1 ? '50%' : '100%');
        $('.step-text').removeClass('text-black').addClass('text-gray-500');
        $('.step-text').each(function (index) {
            if (index + 1 <= currentStep) {
                $(this).addClass('text-black').removeClass('text-gray-500');
            }
        });
    }

    // Show/hide steps
    function showStep(step) {
        $('.step-content').addClass('hidden');
        $(`#step${step}`).removeClass('hidden');

        if (step === 1) {
            $('#backButton').show();
            $('#prevButton').hide();
            $('#nextButton').show();
            $('#submitButton').hide();
        } else {
            $('#backButton').hide();
            $('#prevButton').show();
            $('#nextButton').hide();
            $('#submitButton').show();
        }

        updateProgress();
    }

    // Validate step 1
    function validateStep1TransferOut() {
        let isValid = true;
        const isUSD = $('#isUSD').val();
        if (isUSD) {
            const fromAccount = $('#fromAccount').val();
            if (!fromAccount) {
                $('.from-account .error-message').text('Please select a bank account').removeClass('hidden');
                isValid = false;
            } else {
                $('.from-account .error-message').addClass('hidden');
            }
        } else {
            const fromCrypto = $('#fromWallet').val();
            if (!fromCrypto) {
                $('.from-wallet .error-message').text('Please select a cryptocurrency wallet').removeClass('hidden');
                isValid = false;
            } else {
                $('.from-wallet .error-message').addClass('hidden');
            }
        }
        const sourceAccount = $('#sourceAccount').val();


        if (!sourceAccount) {
            $('#sourceAccount').next('.error-message').text('Please select a source of funds').removeClass('hidden');
            isValid = false;
        } else {
            $('#sourceAccount').next('.error-message').addClass('hidden');
        }

        return isValid;
    }
    function validateStep1TransferIn() {
        let isValid = true;
        const isUSD = $('#isUSD').val();
        if (isUSD) {
            const fromAccount = $('#fromAccount').val();
            if (!fromAccount) {
                $('.from-account .error-message').text('Please select a bank account').removeClass('hidden');
                isValid = false;
            } else {
                $('.from-account .error-message').addClass('hidden');
            }
        }
        const sourceAccount = $('#sourceAccount').val();


        if (!sourceAccount) {
            $('#sourceAccount').next('.error-message').text('Please select a source of funds').removeClass('hidden');
            isValid = false;
        } else {
            $('#sourceAccount').next('.error-message').addClass('hidden');
        }

        return isValid;
    }

    // Validate step 2
    function validateStep2() {
        let isValid = true;
        const amount = parseFloat($('#amount').val());

        if (!amount || amount <= 0) {
            $('#amount').next('.error-message').text('Please enter a valid amount').removeClass('hidden');
            isValid = false;
        } else if (availableBalance !== null && amount > availableBalance) {
            $('#amount').next('.error-message').text('Amount exceeds available balance').removeClass('hidden');
            isValid = false;
        } else {
            $('#amount').next('.error-message').addClass('hidden');
        }

        return isValid;
    }

    // Handle next button click
    $('#transfer-out #nextButton').click(function () {
        if (validateStep1TransferOut()) {
            currentStep = 2;
            showStep(currentStep);
        }
    });
    $('#transfer-in #nextButton').click(function () {
        if (validateStep1TransferIn()) {
            currentStep = 2;
            showStep(currentStep);
        }
    });

    // Handle previous button click
    $('#prevButton').click(function () {
        currentStep = 1;
        showStep(currentStep);
    });

    // Handle form submission
    $form.on('submit', function (e) {
        e.preventDefault();

        if (validateStep2()) {
            $('.submit-text').addClass('hidden');
            $('.loading-text').removeClass('hidden');
            $('.submit-icon').addClass('hidden');
            $('#submitButton').prop('disabled', true);

            // Submit the form
            this.submit();
        }
    });


    // Show initial step
    showStep(currentStep);

    /* Transfer Asset */
    // Handle transfer option selection
    $('.transfer-option').click(function () {
        // Remove selected state from all options
        $('.transfer-option').removeClass('border-black scale-105 shadow-xl').addClass('border-gray-100');
        // Add selected state to clicked option
        $(this).removeClass('border-gray-100').addClass('border-black scale-105 shadow-xl');
        // Scroll to button on mobile
        if (window.innerWidth < 768) {
            $('#createInstructionBtn')[0].scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });

    // Handle create instruction button click
    $('#createInstructionBtn').click(function () {
        const selectedOption = $('.transfer-option.border-black');
        if (!selectedOption.length) {
            toastr.error('Please select a transfer type');
            return;
        }

        const selectedCurrency = $('#currency_select').val();
        if (!selectedCurrency) {
            toastr.error('Please select a currency');
            return;
        }

        let redirectUrl = selectedOption.data('url');
        // Add currency_id as query parameter
        redirectUrl += (redirectUrl.includes('?') ? '&' : '?') + 'currency_id=' + selectedCurrency;

        // Redirect to the appropriate URL
        window.location.href = redirectUrl;
    });
});
