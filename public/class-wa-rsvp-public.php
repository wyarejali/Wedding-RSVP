<?php
class WA_RSVP_Public {
    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        add_shortcode( 'wedding_rsvp_form', array( $this, 'render_form' ) );
    }

    public function enqueue_scripts() {

        // Enqueue CSS
        wp_enqueue_style(
            'wa-rsvp-style',
            WA_RSVP_PLUGIN_URL . 'public/css/wa-rsvp-public.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_script(
            'wa-rsvp-form',
            WA_RSVP_PLUGIN_URL . 'public/js/wa-rsvp-form.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_localize_script( 'wa-rsvp-form', 'wa_rsvp_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wa_rsvp_nonce' ),
        ) );
    }

    public function render_form() {
        ob_start();
        include WA_RSVP_PLUGIN_DIR . 'public/templates/form-template.php';

        return ob_get_clean();
    }

    public function handle_submission() {
        try {
            check_ajax_referer( 'wa_rsvp_nonce', 'nonce' );

            // Debug: Log POST data
            error_log( 'RSVP Form POST data: ' . print_r( $_POST, true ) );

            // Validate required fields
            $required_fields = array( 'attend', 'first_name', 'last_name', 'email', 'phone', 'address' );
            $empty_fields    = array();

            foreach ( $required_fields as $field ) {
                if ( empty( $_POST[$field] ) ) {
                    $empty_fields[] = $field;
                }
            }

            if ( !empty( $empty_fields ) ) {
                $field_labels = array(
                    'attend'     => 'Attending Status',
                    'first_name' => 'First Name',
                    'last_name'  => 'Last Name',
                    'email'      => 'Email',
                    'phone'      => 'Phone',
                    'address'    => 'Address',
                );

                $error_fields = array_map( function ( $field ) use ( $field_labels ) {
                    return $field_labels[$field];
                }, $empty_fields );

                wp_send_json_error( 'Please fill in these required fields: ' . implode( ', ', $error_fields ) );

                return;
            }

            // Validate email
            if ( !is_email( $_POST['email'] ) ) {
                wp_send_json_error( 'Invalid email address. Ex: hello@example.com' );

                return;
            }
            // Validate phone
            if ( !preg_match( '/^\+?[0-9]{10,15}$/', $_POST['phone'] ) ) {
                wp_send_json_error( 'Invalid phone number. min 10 max 15' );

                return;
            }

            // Prepare guest info
            $guests = array();

            // Determine how many guests were submitted
            $guest_count = 1;
            while ( isset( $_POST['guest_fname' . ( $guest_count > 1 ? '_' . $guest_count : '' )] ) ) {
                $guest_count++;
            }
            $guest_count--; // Adjust count back down

            // Debug guest count
            error_log( 'Guest Count: ' . $guest_count );

            // Process each guest's details
            for ( $i = 1; $i <= $guest_count; $i++ ) {
                $suffix           = $i > 1 ? '_' . $i : '';
                $guest_first_name = isset( $_POST['guest_fname' . $suffix] ) ?
                sanitize_text_field( $_POST['guest_fname' . $suffix] ) : '';
                $guest_last_name = isset( $_POST['guest_lname' . $suffix] ) ?
                sanitize_text_field( $_POST['guest_lname' . $suffix] ) : '';

                // Debug each guest's data
                error_log( "Guest {$i} data - First Name: {$guest_first_name}, Last Name: {$guest_last_name}" );

                // Only process if a guest name is provided
                if ( !empty( $guest_first_name ) || !empty( $guest_last_name ) ) {
                    $guests[] = array(
                        'first_name' => $guest_first_name,
                        'last_name'  => $guest_last_name,
                    );
                }
            }

            // debug: Log guest info
            error_log( 'RSVP Guest Info: ' . print_r( $guests, true ) );

            // Prepare data for database
            $submission = array(
                'attend'     => sanitize_text_field( $_POST['attend'] ),
                'first_name' => sanitize_text_field( $_POST['first_name'] ),
                'last_name'  => sanitize_text_field( $_POST['last_name'] ),
                'email'      => sanitize_email( $_POST['email'] ),
                'phone'      => sanitize_text_field( $_POST['phone'] ),
                'address'    => sanitize_textarea_field( $_POST['address'] ),
                'guests'     => json_encode( $guests ),
                'allergies'  => isset( $_POST['allergies'] ) ? sanitize_textarea_field( $_POST['allergies'] ) : '',
                'message'    => isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '',
            );

            // Insert into the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'wa_rsvp_submissions';
            $result     = $wpdb->insert( $table_name, $submission );

            if ( $result === false ) {
                // Log database error
                error_log( 'RSVP DB Error: ' . $wpdb->last_error );
                wp_send_json_error( 'Database error: ' . $wpdb->last_error );

                return;
            }

            wp_send_json_success( 'Your information submitted successfully. Thank you for your RSVP!' );

        } catch ( Exception $e ) {
            error_log( 'RSVP Exception: ' . $e->getMessage() );
            wp_send_json_error( 'Server error: ' . $e->getMessage() );
        }
    }
}