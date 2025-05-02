<?php
class WA_RSVP {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->version     = WA_RSVP_VERSION;
        $this->plugin_name = 'wa_rsvp';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once WA_RSVP_PLUGIN_DIR . 'includes/class-wa-rsvp-loader.php';
        require_once WA_RSVP_PLUGIN_DIR . 'admin/class-wa-rsvp-admin.php';
        require_once WA_RSVP_PLUGIN_DIR . 'public/class-wa-rsvp-public.php';

        $this->loader = new WA_RSVP_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new WA_RSVP_Admin( $this->plugin_name, $this->version );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_wa_rsvp_delete_submission', $plugin_admin, 'delete_submission' );

        // Export submissions to Excel with ajax
        $this->loader->add_action( 'wp_ajax_wa_rsvp_export_submission', $plugin_admin, 'export_submission' );

        $this->loader->add_action( 'wp_ajax_wa_rsvp_export_all_guests', $plugin_admin, 'export_all_guests' );
    }

    private function define_public_hooks() {
        $plugin_public = new WA_RSVP_Public( $this->plugin_name, $this->version );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_wa_rsvp_submit', $plugin_public, 'handle_submission' );
        $this->loader->add_action( 'wp_ajax_nopriv_wa_rsvp_submit', $plugin_public, 'handle_submission' );
    }

    public function run() {
        $this->loader->run();
    }
}