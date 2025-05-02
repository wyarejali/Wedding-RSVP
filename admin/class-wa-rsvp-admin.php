<?php

class WA_RSVP_Admin {
    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        require_once WA_RSVP_PLUGIN_DIR . 'admin/class-wa-rsvp-list-table.php';
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'wa-rsvp-admin',
            WA_RSVP_PLUGIN_URL . 'admin/js/admin.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_localize_script( 'wa-rsvp-admin', 'wa_rsvp_admin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'wa_rsvp_admin_nonce' ),
        ) );

        wp_enqueue_style(
            'wa-rsvp-admin',
            WA_RSVP_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            $this->version
        );
    }

    public function add_menu_page() {
        add_menu_page(
            'Wedding RSVP',
            'Wedding RSVP',
            'manage_options',
            'wedding-rsvp',
            array( $this, 'display_submissions_page' ),
            'dashicons-heart',
            30
        );

        add_submenu_page(
            'wedding-rsvp',
            'All Submissions',
            'All Submissions',
            'manage_options',
            'wedding-rsvp',
            array( $this, 'display_submissions_page' )
        );
    }

    public function display_submissions_page() {
        $list_table = new WA_RSVP_List_Table();
        $list_table->prepare_items();
        include WA_RSVP_PLUGIN_DIR . 'admin/views/submissions-list.php';
    }

    public function delete_submission() {
        check_ajax_referer( 'wa_rsvp_admin_nonce', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $id = intval( $_POST['id'] );
        global $wpdb;
        $table_name = $wpdb->prefix . 'wa_rsvp_submissions';

        $result = $wpdb->delete( $table_name, array( 'id' => $id ) );

        if ( $result ) {
            wp_send_json_success( 'Submission deleted successfully' );
        } else {
            wp_send_json_error( 'Error deleting submission' );
        }
    }

    // Export submissions to Excel
    public function export_submission() {
        check_ajax_referer( 'wa_rsvp_admin_nonce', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        // Include SimpleXLSXGen class
        require_once WA_RSVP_PLUGIN_DIR . 'lib/SimpleXLSXGen.php';

        $id = intval( $_POST['id'] );
        global $wpdb;
        $table_name = $wpdb->prefix . 'wa_rsvp_submissions';

        $submission = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $id
        ), ARRAY_A );

        if ( !$submission ) {
            wp_die( 'Submission not found' );
        }

        // Format guests data
        $guests      = json_decode( $submission['guests'], true );
        $guests_list = '';
        if ( !empty( $guests ) ) {
            foreach ( $guests as $guest ) {
                $guests_list .= $guest['first_name'] . ' ' . $guest['last_name'] . ", ";
            }
        }

        // Format the data as a 2D array
        $data = array(
            array( 'RSVP Submission Details', '' ), // Title row with empty second column
            array( '', '' ), // Empty row
            array( 'Field', 'Value' ), // Headers
            array( 'Full Name', $submission['first_name'] . ' ' . $submission['last_name'] ),
            array( 'Email', $submission['email'] ),
            array( 'Phone', $submission['phone'] ),
            array( 'Address', $submission['address'] ),
            array( 'Attending', ucfirst( $submission['attend'] ) ),
            array( 'Guests', trim( $guests_list ) ),
            array( 'Allergies', $submission['allergies'] ?: 'None' ),
            array( 'Message', $submission['message'] ?: 'None' ),
            array( 'Submitted On', date( 'F j, Y g:i a', strtotime( $submission['created_at'] ) ) ),
        );

        try {
            // Create and download Excel file
            $xlsx = new \Shuchkin\SimpleXLSXGen();
            $xlsx->addSheet( $data, 'RSVP Details' );
            $xlsx->downloadAs( 'rsvp_submission_' . $submission['first_name'] . '_' . $submission['last_name'] . '.xlsx' );
        } catch ( Exception $e ) {
            error_log( 'Excel Generation Error: ' . $e->getMessage() );
            wp_die( 'Error generating Excel file' );
        }

        exit;
    }

    /**
     * Export all guests to Excel
     */
    public function export_all_guests() {
        check_ajax_referer( 'wa_rsvp_admin_nonce', 'nonce' );

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( 'Unauthorized' );
        }

        require_once WA_RSVP_PLUGIN_DIR . 'lib/SimpleXLSXGen.php';

        global $wpdb;
        $table_name = $wpdb->prefix . 'wa_rsvp_submissions';

        // Get all submissions
        $submissions = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY created_at DESC",
            ARRAY_A
        );

        if ( empty( $submissions ) ) {
            wp_die( 'No submissions found' );
        }

        // Prepare Excel data
        $data = array(
            array( 'All Wedding Guests List', '', '', '', '' ), //  Title
            // Current date and time
            array( 'Generated on:', current_time( 'F j, Y g:i a', true ), '', '', '' ), // Added true for GMT/UTC time

            array( '', '', '', '', '' ), // Empty row
            array( 'Primary Guest', 'Email', 'Phone', 'Additional Guests', 'Attending?' ), // Headers
        );

        $total_guests         = 0;
        $total_primary_guests = 0;

        foreach ( $submissions as $submission ) {
            $guests            = json_decode( $submission['guests'], true );
            $additional_guests = array();

            if ( !empty( $guests ) ) {
                foreach ( $guests as $guest ) {
                    $additional_guests[] = $guest['first_name'] . ' ' . $guest['last_name'];
                }
                $total_guests += count( $guests );
            }

            $data[] = array(
                $submission['first_name'] . ' ' . $submission['last_name'],
                $submission['email'],
                $submission['phone'],
                !empty( $additional_guests ) ? implode( ", ", $additional_guests ) : 'None',
                ucfirst( $submission['attend'] ),
            );
            $total_primary_guests++; // Count primary guest
        }

        // Add summary at the end
        $data[] = array( '', '', '', '', '' ); // Empty row
        $data[] = array( 'Total Primary Guests: ' . $total_primary_guests, ' ', ' ', 'Total Additional Guests:' . $total_guests );
        $data[] = array( '', '', '', '', '' ); // Empty row
        // Grand total guests
        $data[] = array( 'Grand Total Guests: ' . ( $total_primary_guests + $total_guests ), ' ', ' ', ' ', ' ' );

        try {
            $xlsx = new \Shuchkin\SimpleXLSXGen();
            $xlsx->addSheet( $data, 'Guest List' );
            $xlsx->downloadAs( 'wedding_guest_list_' . current_time( 'Y-m-d', true ) . '.xlsx' );
        } catch ( Exception $e ) {
            error_log( 'Excel Generation Error: ' . $e->getMessage() );
            wp_die( 'Error generating Excel file' );
        }

        exit;
    }
}