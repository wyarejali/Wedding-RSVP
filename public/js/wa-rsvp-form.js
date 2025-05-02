jQuery(document).ready(function ($) {
    // Add hidden fields for action and nonce
    const $form = $('#wedding-rsvp-form');
    $form.prepend(`
        <input type="hidden" name="action" value="wa_rsvp_submit">
        <input type="hidden" name="nonce" value="${wa_rsvp_ajax.nonce}">
    `);

    // Button html
    const buttonHtml = `<svg class="mr-3 -ml-1 size-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span>Processing...</span>`;

    // Add new guest input fields after clicking the button
    let guestCount = 1; // Start with one guest
    const maxGuests = 6; // Maximum number of guests allowed

    $('.add-new-guest-btn').on('click', function () {
        if (guestCount >= maxGuests) {
            alert('Maximum ' + maxGuests + ' guests allowed per RSVP.');
            return;
        }

        guestCount++;

        // Clone the first guest details container
        const $firstGuest = $('.guest-fields').first();
        const $newGuest = $firstGuest.clone();

        // Update IDs and clear values
        $newGuest.find('input[type="text"]').each(function () {
            const oldId = $(this).attr('id');
            const newId = oldId + '_' + guestCount;
            $(this).attr('id', newId);
            $(this).attr('name', $(this).attr('name') + '_' + guestCount);
            $(this).val(''); // Clear value
        });
        // find label and update for and text
        $newGuest.find('label').each(function () {
            const oldFor = $(this).attr('for');
            const newFor = oldFor + '_' + guestCount;
            $(this).attr('for', newFor);

            // Replace the first word with "Guest_" + guestCount + "_"
            const oldText = $(this).text();
            const newText = oldText.replace(/^[^ ]+/, 'Guest ' + guestCount);
            $(this).text(newText);
        });

        // Add this new under the button
        $newGuest.insertBefore('.add-new-guest-btn');
    });

    // Handle form submission
    $(document).on('click', '.rsvp-submit', function (e) {
        e.preventDefault();

        const $form = $('#wedding-rsvp-form');
        const $message = $form.find('.response-message');
        const $submitButton = $(this);
        const $requiredFields = $form.find('[required]');
        const $requiredRadios = $form.find('input[type="radio"][required]');

        // Reset previous errors
        $requiredFields.removeClass('field-error');
        $requiredRadios.closest('.radio-group').removeClass('field-error');
        $message.removeClass('error success').fadeOut();

        // Validate required fields
        let hasError = false;

        // Check text inputs
        $requiredFields.each(function () {
            if (!$(this).val().trim()) {
                $(this).addClass('field-error');
                hasError = true;
                console.log('Empty field:', this.name);
            }
        });

        // Check radio buttons
        $requiredRadios.each(function () {
            const name = $(this).attr('name');
            if (!$form.find(`input[name="${name}"]:checked`).length) {
                $(this).closest('.radio-group').addClass('field-error');
                hasError = true;
                console.log('Radio not selected:', name);
            }
        });

        if (hasError) {
            $message
                .html('Please fill in all required fields.')
                .addClass('error')
                .fadeIn();
            return false;
        }

        // Prepare form data
        const formData = new FormData($form[0]); // Debug point 2

        // Submit form via AJAX
        $.ajax({
            url: wa_rsvp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $submitButton.prop('disabled', true).html(buttonHtml);
                // Add processing class to the button
                $submitButton.addClass('processing');
            },
            success: function (response) {
                console.log('Response:', response); // Debug point 3
                if (response.success) {
                    // Optionally, you can clear the form fields here or hide the form or redirect
                    // $form[0].reset();
                    // $form.slideUp();
                    $message
                        .html(response.data)
                        .removeClass('error')
                        .addClass('success')
                        .fadeIn();
                } else {
                    $message
                        .html(response.data || 'Submission failed')
                        .removeClass('success')
                        .addClass('error')
                        .fadeIn();
                }
            },
            error: function (xhr, status, error) {
                console.error('Ajax error:', error);
                $message
                    .html('Something went wrong4. Please try again.')
                    .removeClass('success')
                    .addClass('error')
                    .fadeIn();
            },
            complete: function () {
                $submitButton.prop('disabled', false).text('Submit RSVP');
            },
        });
    });

    // Remove error, success class on input, change, update radio, checkbox, textarea and keyup
    $(document).on(
        'input change',
        '#wedding-rsvp-form [required]',
        function () {
            $(this).removeClass('field-error');
            $(this).closest('.radio-group').removeClass('field-error');
            $('.response-message').removeClass('error').fadeOut();
            $('.response-message').removeClass('success').fadeOut();
        }
    );
});
