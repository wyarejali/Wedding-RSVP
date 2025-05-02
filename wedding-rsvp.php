<?php
/**
 * Plugin Name: Wedding RSVP
 * Plugin URI: https://divinationkit.com/plugins/wedding-rsvp
 * Description: A simple wedding RSVP management system. Use this shortcode [wedding_rsvp_form] to display the RSVP form.
 * Version: 1.0.2
 * Author: Wyarej Ali
 * Author URI: https://divinationkit.com
 * Text Domain: wa_rsvp
 * Domain Path: /languages
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( !defined( 'WPINC' ) ) {
    die;
}

define( 'WA_RSVP_VERSION', '1.0.2' );
define( 'WA_RSVP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WA_RSVP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WA_RSVP_PLUGIN_DIR . 'includes/class-wa-rsvp-activator.php';
require_once WA_RSVP_PLUGIN_DIR . 'includes/class-wa-rsvp-deactivator.php';
require_once WA_RSVP_PLUGIN_DIR . 'includes/class-wa-rsvp.php';

// Activation Hook
register_activation_hook( __FILE__, array( 'WA_RSVP_Activator', 'activate' ) );

// Deactivation Hook
register_deactivation_hook( __FILE__, array( 'WA_RSVP_Deactivator', 'deactivate' ) );

// Initialize the plugin
function run_wa_rsvp() {
    $plugin = new WA_RSVP();
    $plugin->run();
}

run_wa_rsvp();