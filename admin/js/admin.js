jQuery(document).ready(function ($) {
    // Export RSVP submissions
    $('.download-excel').on('click', function (e) {
        e.preventDefault();
        const $button = $(this);
        const submissionId = $button.data('id');

        // Change button text
        const originalText = $button.text();
        $button.text('Exporting...').prop('disabled', true);

        // Create form for download
        const $form = $('<form>', {
            method: 'POST',
            action: wa_rsvp_admin.ajax_url,
        });

        // Add form fields
        $form.append(
            $('<input>', {
                type: 'hidden',
                name: 'action',
                value: 'wa_rsvp_export_submission',
            })
        );

        $form.append(
            $('<input>', {
                type: 'hidden',
                name: 'nonce',
                value: wa_rsvp_admin.nonce,
            })
        );

        $form.append(
            $('<input>', {
                type: 'hidden',
                name: 'id',
                value: submissionId,
            })
        );

        // Add form to body and submit
        $('body').append($form);
        $form.submit();
        $form.remove();

        // Reset button after a short delay
        setTimeout(() => {
            $button.text(originalText).prop('disabled', false);
        }, 2000);
    });

    // Delete RSVP submission
    $('.delete-submission').on('click', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to delete this submission?')) {
            return;
        }

        const $button = $(this);
        const submissionId = $button.data('id');

        $.ajax({
            url: wa_rsvp_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'wa_rsvp_delete_submission',
                nonce: wa_rsvp_admin.nonce,
                id: submissionId,
            },
            success: function (response) {
                if (response.success) {
                    $button.closest('tr').fadeOut();
                } else {
                    alert(response.data);
                }
            },
        });
    });
});
