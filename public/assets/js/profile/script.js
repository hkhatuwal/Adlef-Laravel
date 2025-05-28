$(document).ready(function() {
    const $currentPasswordField = $('#current_password');
    const $newPasswordField = $('#new_password');
    const $confirmPasswordField = $('#new_password_confirmation');

    // Show/hide new password fields based on current password input
    $currentPasswordField.on('input', function() {
        const hasCurrentPassword = $(this).val().length > 0;
        $newPasswordField.parent().toggle(hasCurrentPassword);
        $confirmPasswordField.parent().toggle(hasCurrentPassword);

        if (!hasCurrentPassword) {
            $newPasswordField.val('');
            $confirmPasswordField.val('');
        }
    });

    // Initially hide new password fields
    if ($currentPasswordField.val().length === 0) {
        $newPasswordField.parent().hide();
        $confirmPasswordField.parent().hide();
    }

    // Password confirmation validation
    $confirmPasswordField.on('input', function() {
        if ($(this).val() !== $newPasswordField.val()) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    $newPasswordField.on('input', function() {
        if ($confirmPasswordField.val() && $(this).val() !== $confirmPasswordField.val()) {
            $confirmPasswordField[0].setCustomValidity('Passwords do not match');
        } else {
            $confirmPasswordField[0].setCustomValidity('');
        }
    });
});
