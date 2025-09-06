$(document).ready(function () {
    // Tab functionality
    console.log("Payment Gateway JS Loaded");

    $('.tab-button').on('click', function () {
        const tabName = $(this).data('tab');

        // Remove active class from all buttons and add to clicked button
        $('.tab-button').removeClass('active border-black text-black')
            .addClass('border-transparent text-gray-500');

        $(this).addClass('active border-black text-black')
            .removeClass('border-transparent text-gray-500');

        // Hide all tab contents and show selected one
        $('.tab-content').addClass('hidden');
        $('#' + tabName + '-tab').removeClass('hidden');
    });

    // Copy functionality for API documentation
    $('.copy-btn').on('click', function () {
        const copyType = $(this).data('copy');
        let textToCopy = '';

        if (copyType === 'curl') {
            textToCopy = $('#curl-example code').text();
        } else if (copyType === 'headers') {
            textToCopy = `X-API-Key: your_api_key_here
X-Secret-Key: your_secret_key_here
Content-Type: application/json`;
        }

        copyToClipboard(textToCopy);
    });
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function () {
        toastr.success('Copied to clipboard!');
    });
}

// Modal functions
function openCreateApiKeyModal() {
    $('#createApiKeyModal').removeClass('hidden');
}

function closeCreateApiKeyModal() {
    $('#createApiKeyModal').addClass('hidden');
    $('#createApiKeyForm')[0].reset();
}

// API Key visibility toggle
function toggleApiKeyVisibility(elementId) {
    const $element = $('#' + elementId);
    const $eyeButton = $element.parent().find('.fa-eye, .fa-eye-slash');

    $element.toggleClass('hidden');

    // Toggle eye icon
    if ($element.hasClass('hidden')) {
        $eyeButton.removeClass('fa-eye-slash').addClass('fa-eye');
    } else {
        $eyeButton.removeClass('fa-eye').addClass('fa-eye-slash');
    }
}

// Submit create API key form
function submitCreateApiKey() {
    const form = $('#createApiKeyForm')[0];
    const formData = new FormData(form);

    // Show loading state
    const $submitBtn = $('#createApiKeyModal button[onclick="submitCreateApiKey()"]');
    const originalHtml = $submitBtn.html();
    $submitBtn.html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Creating...').prop('disabled', true);

    // Get the route URL from a data attribute or meta tag
    const createUrl = $('meta[name="api-keys-create-url"]').attr('content');

    $.ajax({
        url: createUrl,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if (data.success) {
                toastr.success('API key created successfully!');
                closeCreateApiKeyModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                console.log(data)
                toastr.error('Error creating API key: ' + (data.message || 'Unknown error'));
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);

            // Try to get message from JSON response
            let errorMessage = 'Error creating API key';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                // fallback for plain text responses
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        },
        complete: function () {
            $submitBtn.html(originalHtml).prop('disabled', false);
        }
    });
}

// Other API key management functions
function editApiClient(clientId) {
    // TODO: Implement edit functionality
    toastr.info('Edit functionality coming soon');
}

function regenerateApiKey(clientId) {
    if (confirm('Are you sure you want to regenerate this API key? The old key will stop working immediately.')) {
        const $button = $(event.target).closest('button');
        const originalHtml = $button.html();
        $button.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: `/payment-gateway/api-keys/${clientId}/regenerate`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            success: function (data) {
                if (data.success) {
                    toastr.success('API key regenerated successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error('Error regenerating API key: ' + (data.message || 'Unknown error'));
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                toastr.error('Error regenerating API key');
            },
            complete: function () {
                $button.html(originalHtml).prop('disabled', false);
            }
        });
    }
}

function toggleApiClientStatus(clientId, newStatus) {
    const action = newStatus === 'true' ? 'enable' : 'disable';
    if (confirm(`Are you sure you want to ${action} this API key?`)) {
        const $button = $(event.target).closest('button');
        const originalHtml = $button.html();
        $button.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: `/payment-gateway/api-keys/${clientId}/toggle`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({is_active: newStatus === 'true'}),
            success: function (data) {
                if (data.success) {
                    toastr.success(`API key ${action}d successfully!`);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(`Error ${action}ing API key: ` + (data.message || 'Unknown error'));
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                toastr.error(`Error ${action}ing API key`);
            },
            complete: function () {
                $button.html(originalHtml).prop('disabled', false);
            }
        });
    }
}

function deleteApiClient(clientId) {
    if (confirm('Are you sure you want to delete this API key? This action cannot be undone and will stop all integrations using this key.')) {
        const $button = $(event.target).closest('button');
        const originalHtml = $button.html();
        $button.html('<i class="fa-solid fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: `/payment-gateway/api-keys/${clientId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            success: function (data) {
                if (data.success) {
                    toastr.success('API key deleted successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error('Error deleting API key: ' + (data.message || 'Unknown error'));
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                toastr.error('Error deleting API key');
            },
            complete: function () {
                $button.html(originalHtml).prop('disabled', false);
            }
        });
    }
}


// Close modal when clicking outside or pressing Escape
$(document).on('click', function (event) {
    if ($(event.target).is('#createApiKeyModal')) {
        closeCreateApiKeyModal();
    }
});

$(document).on('keydown', function (event) {
    if (event.key === 'Escape') {
        closeCreateApiKeyModal();
    }
});



