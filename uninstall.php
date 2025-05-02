<?php
/**
 * Delete all plugin data
 *
 * This function will remove all data associated with the wedding RSVP plugin
 *
 * @return void
 */
global $wpdb;

// Drop the RSVP submissions table
$table_name = $wpdb->prefix . 'wa_rsvp_submissions';
$sql        = "DROP TABLE IF EXISTS $table_name;";
$wpdb->query( $sql );