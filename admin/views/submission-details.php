<?php
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    $guests = json_decode( $submission->guests, true );
?>

<div class="wrap wedding-rsvp-details">
    <div class="rsvp_submitter_details">
        <h3>Submitter Details</h3>
        <table>
            <tbody>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo esc_html( $submission->first_name . ' ' . $submission->last_name ); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo esc_html( $submission->email ); ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo esc_html( $submission->phone ); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo esc_html( $submission->address ); ?></td>
                </tr>
                <tr>
                    <th>Attending?</th>
                    <td>
                        <?php if ( $submission->attend == 'yes' ): ?>
                            <p class="attending">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2c-.218 0 -.432 .002 -.642 .005l-.616 .017l-.299 .013l-.579 .034l-.553 .046c-4.785 .464 -6.732 2.411 -7.196 7.196l-.046 .553l-.034 .579c-.005 .098 -.01 .198 -.013 .299l-.017 .616l-.004 .318l-.001 .324c0 .218 .002 .432 .005 .642l.017 .616l.013 .299l.034 .579l.046 .553c.464 4.785 2.411 6.732 7.196 7.196l.553 .046l.579 .034c.098 .005 .198 .01 .299 .013l.616 .017l.642 .005l.642 -.005l.616 -.017l.299 -.013l.579 -.034l.553 -.046c4.785 -.464 6.732 -2.411 7.196 -7.196l.046 -.553l.034 -.579c.005 -.098 .01 -.198 .013 -.299l.017 -.616l.005 -.642l-.005 -.642l-.017 -.616l-.013 -.299l-.034 -.579l-.046 -.553c-.464 -4.785 -2.411 -6.732 -7.196 -7.196l-.553 -.046l-.579 -.034a28.058 28.058 0 0 0 -.299 -.013l-.616 -.017l-.318 -.004l-.324 -.001zm2.293 7.293a1 1 0 0 1 1.497 1.32l-.083 .094l-4 4a1 1 0 0 1 -1.32 .083l-.094 -.083l-2 -2a1 1 0 0 1 1.32 -1.497l.094 .083l1.293 1.292l3.293 -3.292z" fill="currentColor" stroke-width="0" /></svg>
                                <span class="yes"><?php _e( 'Yes', 'wa_rsvp' ); ?></span>
                            </p>
                        <?php else: ?>
                            <p class="not-attending">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm-1.489 7.14a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" fill="currentColor" stroke-width="0" /></svg>
                                <span class="no"><?php _e( 'No', 'wa_rsvp' ); ?></span>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Allergies</th>
                    <td><?php echo esc_html( $submission->allergies ); ?></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?php echo esc_html( $submission->message ); ?></td>
                </tr>

                <tr class="section">
                    <th><?php _e( 'Submission', 'wa_rsvp' ); ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th><?php _e( 'Date & Time', 'wa_rsvp' ); ?></th>
                    <td><?php echo date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $submission->created_at ) ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="rsvp_guests_details">
        <h3>Guests Details</h3>
        <?php if ( !empty( $guests ) ): ?>
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $guests as $guest ): ?>
                    <tr>
                        <td><?php echo esc_html( $guest['first_name'] ); ?></td>
                        <td><?php echo esc_html( $guest['last_name'] ); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No guests added.</p>
        <?php endif; ?>
    </div>
</div>