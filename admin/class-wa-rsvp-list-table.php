<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WA_RSVP_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( array(
            'singular' => 'submission',
            'plural'   => 'submissions',
            'ajax'     => false,
        ) );
    }

    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'name'       => 'Full Name',
            'email'      => 'Email',
            'phone'      => 'Phone',
            'guests'     => 'Total Guests',
            'created_at' => 'Date',
            'actions'    => 'Actions',
        );
    }

    public function get_sortable_columns() {
        return array(
            'name'       => array( 'name', true ),
            'created_at' => array( 'created_at', true ),
        );
    }

    public function get_bulk_actions() {
        return array(
            'bulk-delete' => __( 'Delete', 'wedding-rsvp' ),
        );
    }

    /**
     * Column for checkbox
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="submissions[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * Prepare the items for display
     */
    public function prepare_items() {
        global $wpdb;
        $table_name   = $wpdb->prefix . 'wa_rsvp_submissions';
        $per_page     = 20;
        $current_page = $this->get_pagenum();

        // Handle search if present
        $search = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        $where  = '';
        if ( !empty( $search ) ) {
            $where = $wpdb->prepare(
                "WHERE owner_name LIKE %s OR phone LIKE %s OR address LIKE %s",
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%'
            );
        }
        // Get total items
        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name $where" );

        // Set up column headers
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        // Handle sorting (Default: `created_at DESC` to show newest first)
        $allowed_columns = array( 'owner_name', 'created_at' ); // Allowed sortable columns
        $orderby         = isset( $_REQUEST['orderby'] ) && in_array( $_REQUEST['orderby'], $allowed_columns )
        ? esc_sql( $_REQUEST['orderby'] )
        : 'created_at'; // Default sort by date

        $order = isset( $_REQUEST['order'] ) && strtoupper( $_REQUEST['order'] ) === 'ASC'
        ? 'ASC'
        : 'DESC'; // Default: Descending order

        // Fetch sorted data
        $query = "SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT %d OFFSET %d";
        $data  = $wpdb->get_results( $wpdb->prepare( $query, $per_page, ( $current_page - 1 ) * $per_page ), ARRAY_A );

        // Set up pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ) );

        $this->items = $data;

    }

    /**
     * Default column output
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
        case 'name':
            // Return the full name and add a link to the view page
            return sprintf(
                '<a href="%s">%s %s</a>',
                admin_url( 'admin.php?page=wedding-rsvp&action=view&id=' . $item['id'] ),
                esc_html( $item['first_name'] ),
                esc_html( $item['last_name'] )
            );

        case 'email':
            return $item['email'];
        case 'phone':
            return $item['phone'];
        case 'guests':
            $guests = json_decode( $item['guests'], true );

            return is_array( $guests ) ? count( $guests ) : 0;

        case 'created_at':
            return date( 'F j, Y, g:i a', strtotime( $item['created_at'] ) );
        default:
            return '';
        }
    }

    /**
     * Column for actions View, Delete, (Download as Excel)
     */
    public function column_actions( $item ) {
        $actions = array(
            'delete'   => sprintf(
                '<a href="#" class="button button-small delete-submission" data-id="%s">Delete</a>',
                $item['id']
            ),
            'download' => sprintf(
                '<a href="#" class="button button-small download-excel" data-id="%s">Download</a>',
                $item['id']
            ),
        );

        return implode( ' ', $actions );
    }

}
