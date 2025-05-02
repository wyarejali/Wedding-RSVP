<?php
class WA_RSVP_Deactivator {
    public static function deactivate() {
        // global $wpdb;

        // // Drop the RSVP submissions table
        // $table_name = $wpdb->prefix . 'wa_rsvp_submissions';
        // $sql        = "DROP TABLE IF EXISTS $table_name;";
        // $wpdb->query( $sql );

        // Delete the RSVP page
        // $rsvp_page = get_page_by_path( 'rsvp' );
        // if ( $rsvp_page ) {
        //     wp_delete_post( $rsvp_page->ID, true );
        // }
    }
}