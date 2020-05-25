<?php
/**
 * The core plugin class. This is used to define internationalization, admin-specific hooks, and
 * in principle public-facing site hooks.
 */
class Croquet_Match_Report {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'croquet-match-report';
        $this->version = '1.0.0';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_metabox_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-i18n.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin-metaboxes.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/croquet-match-report-global-functions.php';

        /*
         * Ensure that Sportspress is present
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/class-tgm-plugin-activation.php';
        $this->loader = new Croquet_Match_Report_Loader();
        $this->loader->add_action('tgmpa_register', $this, 'require_core');
    }

    /**
     * Define the locale for this plugin for internationalization.
     */
    private function set_locale() {
        $plugin_i18n = new Croquet_Match_Report_i18n();
        $plugin_i18n->set_domain( $this->get_plugin_name() );
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'hook_text_translations');
    }

    /**
     * Register all of the admin hooks
     */
    private function define_admin_hooks() {
        $plugin_admin = new Croquet_Match_Report_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

    /**
     * Register all of the hooks related to metaboxes
     */
    private function define_metabox_hooks() {
        $plugin_metaboxes = new Croquet_Match_Report_Admin_Metaboxes( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action('add_meta_boxes', $plugin_metaboxes, 'add_metaboxes', 20 );
        $this->loader->add_action('save_post_report', $plugin_metaboxes, 'validate_meta_report', 10, 2 );
        $this->loader->add_action('add_meta_boxes', $plugin_metaboxes, 'zap_metaboxes', 99);
        $this->loader->add_action('pre_post_update', $plugin_metaboxes, 'pre_post_update_action', 0, 2);
        $this->loader->add_action('admin_notices', $plugin_metaboxes, 'admin_notice_handler');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }


    /**
     * Require SportsPress core.
     */
    public static function require_core() {
        $plugins = array(
            array(
                'name'        => 'SportsPress',
                'slug'        => 'sportspress',
                'required'    => true,
                'version'     => '2.3',
                'is_callable' => array( 'SportsPress', 'instance' ),
            ),
        );

        $config = array(
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'has_notices'  => true,
            'dismissable'  => true,
            'is_automatic' => true,
            'message'      => '',
            'strings'      => array(
                'nag_type' => 'updated'
            )
        );

        tgmpa( $plugins, $config );
    }
}
