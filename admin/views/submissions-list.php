<?php
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Wedding RSVP Submissions</h1>

    <?php if ( isset( $_GET['action'] ) && $_GET['action'] === 'view' && isset( $_GET['id'] ) ):
            // Get submission details
            global $wpdb;
            $id         = intval( $_GET['id'] );
            $submission = $wpdb->get_row( $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wa_rsvp_submissions WHERE id = %d",
                $id
            ) );

            if ( $submission ):
                include 'submission-details.php';
        else: ?>
		            <div class="notice notice-error">
		                <p>Submission not found.</p>
		            </div>
		        <?php endif;
                    else:
                        // Display submissions list
                        $list_table = new WA_RSVP_List_Table();
                    $list_table->prepare_items(); ?>

		        <form method="post">
		            <?php
                            $list_table->search_box( 'Search', 'search_id' );
                            $list_table->display();
                        ?>
		        </form>
		    <?php endif; ?>
</div>