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
        $this->define_widget_hooks();
        $this->define_metabox_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
     * - Plugin_Name_i18n. Defines internationalization functionality.
     * - Plugin_Name_Admin. Defines all hooks for the admin area.
     * - Plugin_Name_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin.php';

        /**
         * The class responsible for defining all actions relating to metaboxes.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-croquet-match-report-admin-metaboxes.php';

        /**
         * The class responsible for all global functions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/croquet-match-report-global-functions.php';

        /**
         * The class responsible for defining all actions shared by the Dashboard and public-facing sides.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-shared.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-widget.php';

        /**
         * The class responsible for sanitizing user input
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-croquet-match-report-sanitize.php';

        /**
         * The class which ensures that sportspress if loaded
         */
        require_once plugin_dir_path(dirname(__FILE__)) . '/includes/class-tgm-plugin-activation.php';

        $this->loader = new Croquet_Match_Report_Loader();
        $this->sanitizer = new Croquet_Match_Report_Sanitize(); // TODO change to sanitizer

        /*
         * Ensure that Sportspress is present
         */
        $this->loader->add_action('tgmpa_register', $this, 'require_core');
    }

    /**
     * Define the locale for this plugin for internationalization.
     */
    private function set_locale() {
        $plugin_i18n = new Croquet_Match_Report_i18n();
        $plugin_i18n->set_domain( $this->get_plugin_name() );
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Croquet_Match_Report_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_admin, 'new_cpt_report' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
        //  $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );
        //  $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_notices_init' );
    }

    /**
     * Register all of the hooks shared between public-facing 
     * and admin functionality of the plugin.
     */
    private function define_shared_hooks() {
        $plugin_shared = new Croquet_Match_Report_Shared( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'widgets_init', $plugin_shared, 'widgets_init' );
        $this->loader->add_action( 'save_post_job', $plugin_shared, 'flush_widget_cache' );
        $this->loader->add_action( 'deleted_post', $plugin_shared, 'flush_widget_cache' );
        $this->loader->add_action( 'switch_theme', $plugin_shared, 'flush_widget_cache' );
    } 


    /**
     * Register all of the hooks related to metaboxes
     */
    private function define_metabox_hooks() {
        $plugin_metaboxes = new Croquet_Match_Report_Admin_Metaboxes( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action('add_meta_boxes', $plugin_metaboxes, 'add_metaboxes', 20 );
        $this->loader->add_action('add_meta_boxes', $plugin_metaboxes, 'set_meta' ,30 );
        $this->loader->add_action('save_post', $plugin_metaboxes, 'validate_meta', 10, 2 );
        $this->loader->add_action('add_meta_boxes', $plugin_metaboxes, 'zap_metaboxes', 99);
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


    /**
     * Register all of the hooks shared between public-facing and admin functionality
     * of the plugin.
     */
    private function define_widget_hooks() {
        $this->loader->add_action( 'widgets_init', $this, 'widgets_init' );
        $this->loader->add_action( 'save_post_job', $this, 'flush_widget_cache' );
        $this->loader->add_action( 'deleted_post', $this, 'flush_widget_cache' );
        $this->loader->add_action( 'switch_theme', $this, 'flush_widget_cache' );
    }

    /**
     * Flushes widget cache
     */
    public function flush_widget_cache( $post_id ) {
        if ( wp_is_post_revision( $post_id ) ) { return; }
        $post = get_post( $post_id );
        if ( 'job' == $post->post_type ) wp_cache_delete( $this->plugin_name, 'widget' );
    }

    /**
     * Registers widgets with WordPress
     */
    public function widgets_init() {
        register_widget( 'croquet_match_report_widget' );
    }
}
