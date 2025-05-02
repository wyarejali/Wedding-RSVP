<?php
class WA_RSVP_Activator {
    public static function activate() {
        global $wpdb;

        // Create database table
        $table_name      = $wpdb->prefix . 'wa_rsvp_submissions';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            attend ENUM('yes', 'no') NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            address text NOT NULL,
            guests longtext,
            allergies text,
            message text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Create RSVP page if it doesn't exist
        self::create_rsvp_page();
    }

    private static function create_rsvp_page() {
        // Check if RSVP page exists
        $rsvp_page = get_page_by_path( 'rsvp' );

        if ( !$rsvp_page ) {
            // Create new page
            $page_data = array(
                'post_title'   => 'RSVP',
                'post_content' => '[wedding_rsvp_form]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => 'rsvp',
            );
            wp_insert_post( $page_data );
        } else {
            // Update existing page to include shortcode if it's not already there
            $content = $rsvp_page->post_content;
            if ( strpos( $content, '[wedding_rsvp_form]' ) === false ) {
                $updated_content = $content . "\n[wedding_rsvp_form]";
                wp_update_post( array(
                    'ID'           => $rsvp_page->ID,
                    'post_content' => $updated_content,
                ) );
            }
        }
    }
}