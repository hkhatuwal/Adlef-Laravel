// Initialize AOS library if needed (assuming AOS is a library for animations)
// noinspection JSJQueryEfficiency

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
        $mainContent.removeClass('ml-72');
        $topNav.removeClass('left-72').addClass('left-0');
        console.log('Sidebar hidden');
    } else {
        $sidebar.css('transform', 'translateX(0)');
        $mainContent.addClass('ml-72');
        $topNav.addClass('left-72').removeClass('left-0');
        console.log('Sidebar shown');
    }
}

function markNotificationAsRead(id) {
    fetch(`/notifications/${id}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the notifications or remove the notification from DOM
                location.reload();
            }
        });
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
            minimumResultsForSearch: 0,
            width: '100%'
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
            const fromAccount = $('#to_account').val();
            console.log(fromAccount)
            if (!fromAccount) {
                $('.from-account .error-message').text('Please select a bank account').removeClass('hidden');
                isValid = false;
            } else {
                $('.from-account .error-message').addClass('hidden');
            }
        } else {
            const fromCrypto = $('#to_wallet').val();
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

    $('#transfer-out #amount').change(function (e) {

        calculateFee(e.target.value);
    })

    function calculateFee(amount) {
        // Get the currency ID from the hidden input
        const currencyId = document.querySelector('input[name="currency_id"]').value;
        // Make API call to calculate fee
        fetch(route('client.client.transfer.calculate-fee'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                amount: amount,
                currency_id: currencyId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const feeDetails = data.data;
                    document.getElementById('fee-amount').textContent = '$' + feeDetails.fee.toFixed(2);
                    document.getElementById('total-amount').textContent = '$' + feeDetails.total.toFixed(2);
                    document.getElementById('fee-input').value = feeDetails.fee.toFixed(2);
                    $('#submitButton').prop("disabled",false);

                } else {
                    if(!data.success && data.message){
                        $('#submitButton').prop("disabled",true);
                        toastr.error(data.message)
                    }
                    console.error('Fee calculation failed:', data);
                }
            })
            .catch(error => {

                console.error('Error calculating fee:', error);
            });
    }

});

// Currency Exchange Functionality
$(document).ready(function () {
    // Initially hide currency dialogs
    $('.currency-dialog').hide();

    // Toggle currency dialogs
    $('.pay-currency-btn').on('click', function (e) {
        e.stopPropagation();
        const $dialog = $('.pay-currency-dialog');
        const isVisible = $dialog.is(':visible');
        $dialog.toggle();
        $('.receive-currency-dialog').hide();
    });

    $('.receive-currency-btn').on('click', function (e) {
        e.stopPropagation();
        const $dialog = $('.receive-currency-dialog');
        const isVisible = $dialog.is(':visible');
        $dialog.toggle();
        $('.pay-currency-dialog').hide();
    });

    // Handle search functionality
    $('.currency-search').on('input', function () {
        const searchTerm = $(this).val().toLowerCase();
        const $currencyList = $(this).closest('.currency-dialog').find('.currency-item');

        $currencyList.each(function () {
            const currencyName = $(this).find('.currency-name').text().toLowerCase();
            $(this).toggle(currencyName.includes(searchTerm));
        });
    });

    // Close dialogs when clicking outside
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.currency-selector, .currency-dialog').length) {
            $('.currency-dialog').hide();
        }
    });

    // Handle currency selection
    $('.currency-item').on('click', function () {
        const $item = $(this);
        const selectedCurrency = $item.find('.currency-name').text();
        const selectedCurrencyId = $item.data('value');
        const selectCurrencyBalance = $item.data('balance');
        const selectedIcon = $item.find('.currency-icon').attr('src');
        const type = $item.data('type');
        const $dialog = $item.closest('.currency-dialog');
        const $button = $dialog.hasClass('pay-currency-dialog') ?
            $('.pay-currency-btn') : $('.receive-currency-btn');

        updateSelectedCurrency($button, selectedIcon, selectedCurrency, selectedCurrencyId, selectCurrencyBalance)


        // Hide dialog
        $dialog.hide();
        checkIfExchangePossible()

        // Trigger exchange rate calculation
        calculateExchangeRate();

    });



    function updateSelectedCurrency($button, selectedIcon, selectedCurrency, selectedCurrencyId, selectCurrencyBalance) {
        // Update button content
        $button.find('.currency-icon').attr('src', selectedIcon);
        $button.find('.currency-code').text(selectedCurrency);
        $button.find('.currency').val(selectedCurrencyId)
        if (selectCurrencyBalance) {
            $('#availableBalance').text(`${selectedCurrency} ${selectCurrencyBalance}`)
        }
    }

    // Handle amount input
    $('#fromAmount').on('input', function () {
        calculateExchangeRate();
    });

    // Switch currencies
    $('.switch-currencies-btn').on('click', function () {
        swapPayReceiveValues();
    });

    function swapPayReceiveValues() {
        // Get current values for currencies
        const payIcon = $('.pay-currency-btn .currency-icon').attr('src');
        const payCurrency = $('.pay-currency-btn .currency-code').text();
        const payCurrencyId = $('.pay-currency-btn .currency').val();
        const receiveIcon = $('.receive-currency-btn .currency-icon').attr('src');
        const receiveCurrency = $('.receive-currency-btn .currency-code').text();
        const receiveCurrencyId = $('.receive-currency-btn .currency').val();

        // Get current amounts
        const payAmount = $('#fromAmount').val();
        const receiveAmount = $('#toAmount').val();

        // Swap currencies
        $('.pay-currency-btn .currency-icon').attr('src', receiveIcon);
        $('.pay-currency-btn .currency-code').text(receiveCurrency);
        $('.pay-currency-btn .currency').val(receiveCurrencyId);
        $('.receive-currency-btn .currency-icon').attr('src', payIcon);
        $('.receive-currency-btn .currency-code').text(payCurrency);
        $('.receive-currency-btn .currency').val(payCurrencyId);

        // Swap amounts
        $('#fromAmount').val(receiveAmount);
        $('#toAmount').val(payAmount);

        // Recalculate exchange rate with new values
        calculateExchangeRate();
    }

    // Handle terms checkbox
    $('.terms-checkbox').on('change', function () {
        $('.confirm-exchange-btn').prop('disabled', !$(this).is(':checked'));
    });

    // Calculate exchange rate
    function calculateExchangeRate() {
        const fromAmount = parseFloat($('#fromAmount').val()) || 0;
        const fromCurrency = $('#from-currency').val();
        const toCurrency = $('#to-currency').val();
        if (fromAmount > 0 && fromCurrency && toCurrency) {
            // First get the current rate
            $.ajax({
                url: route('client.otc.calculate'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    from_amount: fromAmount,
                    from_currency: fromCurrency,
                    to_currency: toCurrency
                },
                success: function (response) {
                    if (response.success) {
                        const rate = response.data.rate;
                        const fromSymbol = response.data.from.symbol;
                        const toSymbol = response.data.to.symbol;

                        // Update the exchange rate display
                        $('.exchange-rate').text(`1 ${fromSymbol} = ${rate.toFixed(6)} ${toSymbol}`);
                        $('#toAmount').val(response.data.converted_amount.toFixed(6));
                        $('.network-fee').text(`${response.data.fee.toFixed(6)} ${fromSymbol}`);
                        // Calculate the exchange

                    }
                },
                error: function (xhr) {
                    console.error('Error fetching rate:', xhr);
                    toastr.error('Failed to fetch exchange rate');
                }
            });
        } else {
            $('#toAmount').val('');
            $('.exchange-rate').text('-');
            $('.network-fee').text('N/A');
        }
    }
    function checkIfExchangePossible() {
        const fromCurrency = $('#from-currency').val();
        const toCurrency = $('#to-currency').val();
        if (!fromCurrency || !toCurrency) {
            return;
        }
        $.ajax({
            url: route('client.otc.is-exchange-possible'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                from_currency: fromCurrency,
                to_currency: toCurrency
            },
            success: function (response) {

            },
            error: function (xhr) {
                console.error('Error fetching rate:', xhr);
                toastr.error(xhr.responseJSON.message);
                $('.pay-currency-dialog .currency-item')[0].click();

            }
        });
    }

    // Initialize exchange rate calculation
    calculateExchangeRate();
    // Select default currencies
    $('.currency-item:first').click();
    $('.receive-currency-list li').eq(3).click();


});
