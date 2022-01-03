<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */


use BS\Formular2\Bs_Formular2_Public;
use BS\Formular2\Bs_Formular2_Admin;
use BS\Formular2\BS_Formular2_Options;
use Hupa\FormLicense\Bs_Formular2_Register;
use BSFormularAPIExec\EXEC\BS_Formular2_License_Exec_Api;
use Hupa\BsPluginLicense\Hupa_Server_WP_Remote_Handle;
use JetBrains\PhpStorm\NoReturn;

class Bs_Formular2 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bs_Formular2_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected Bs_Formular2_Loader $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected string $version;

    /**
     * The current database version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $db_version    The current database version of the plugin.
     */
    protected string $db_version;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @var object The main class.
     */
    public object $main;

    /**
     * Activate Plugin License.
     *
     * @since    1.0.0
     * @var object      The license class.
     */
    protected $license;

    /**
     * WP-Remote Plugin License.
     *
     * @since    1.0.0
     * @var object      The Remote class.
     */
    protected $remote;


    /**
     * Get-Options Class.
     *
     * @since    1.0.0
     * @var object      The Options class.
     */
    protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'BS_FORMULAR_PLUGIN_VERSION' ) ) {
			$this->version = BS_FORMULAR_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}

        if ( defined( 'BS_FORMULAR_PLUGIN_DB_VERSION' ) ) {
            $this->db_version = BS_FORMULAR_PLUGIN_DB_VERSION;
        } else {
            $this->db_version = '1.0.0';
        }

		$this->plugin_name = 'bs-formular2';
        $this->main = $this;

        //Check PHP AND WordPress Version
        $this->check_dependencies();
        // Require dependencies
		$this->load_dependencies();
        //Set Locale "bs-formular2"
		$this->set_locale();
        // Register License and Import Data from Server
        $this->register_bs_formular2_license();
        // Set Settings Default-Optionen AND Helper Functions
        $this->bs_formular2_options();

        if (get_option('bs_formular_product_install_authorize')) {
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
     * - Bs_Formular2_Register. Register License.
     * - BS_Formular2_License_Exec_Api. RESPONSE API SERVER.
     * - Hupa_Server_WP_Remote_Handle. WP-REMOTE API.
     * - BS_Formular2_Defaults_Trait. Trait Default-Settings
     * - BS_Formular2_Options. Helper AND Default Options
	 * - Bs_Formular2_Loader. Orchestrates the hooks of the plugin.
	 * - Bs_Formular2_i18n. Defines internationalization functionality.
	 * - Bs_Formular2_Admin. Defines all hooks for the admin area.
	 * - Bs_Formular2_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        /**
         * The class, Registers and activates the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/license/class-register-hupa-plugin.php';

        /**
         * The class API EXEC.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/license/api-exec-class.php';

        /**
         * The class API WP-Remote Class.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/license/hupa_client_api_wp_remote.php';


        /**
         * The class for the options of the BS-Formular2
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/Traits/BS_Formular2_Defaults_Trait.php';

        /**
         * The class for the options of the BS-Formular2
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class_bs-formular2_options.php';

        /**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bs-formular2-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bs-formular2-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bs-formular2-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bs-formular2-public.php';

		$this->loader = new Bs_Formular2_Loader();

	}

    /**
     * Check PHP and WordPress Version
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function check_dependencies(): void
    {
        global $wp_version;
        if (version_compare(PHP_VERSION, BS_FORMULAR_MIN_PHP_VERSION, '<') || $wp_version < BS_FORMULAR_MIN_WP_VERSION) {
            $this->maybe_self_deactivate();
        }
    }

    /**
     * Self-Deactivate
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function maybe_self_deactivate(): void
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        deactivate_plugins(BS_FORMULAR_SLUG_PATH);
        add_action('admin_notices', array($this, 'self_deactivate_notice'));
    }

    /**
     * Self-Deactivate Admin Notiz
     * of the plugin.
     *
     * @since    1.0.0
     * @access   public
     */
    #[NoReturn] public function self_deactivate_notice(): void
    {
        echo sprintf('<div class="error" style="margin-top:5rem"><p>' . __('This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'hupa-api-editor') . '</p></div>', BS_FORMULAR_MIN_PHP_VERSION, BS_FORMULAR_MIN_WP_VERSION);
        exit();
    }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bs_Formular2_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bs_Formular2_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    /**
     * Validate BS-Formular2 License.
     *
     * Uses the class RegisterBsFormular to register the licence for the plugin
     * @since    1.0.0
     * @access   private
     */
    private function register_bs_formular2_license() {

        $this->license = Bs_Formular2_Register::instance();

        /** Register License Admin Menu
         * @since    1.0.0
         */
        // TODO REGISTER LICENSE MENU
        if(!get_option('bs_formular_product_install_authorize')) {
            $this->loader->add_action('admin_menu',$this->license, 'register_license_bs_formular2_plugin');
        }

        $this->loader->add_action('wp_ajax_BsFormularLicenceHandle', $this->license, 'prefix_ajax_BsFormularLicenceHandle');
        $this->loader->add_action( 'init', $this->license, 'bs_formular_license_site_trigger_check' );
        $this->loader->add_action( 'template_redirect',$this->license, 'bs_formular_license_callback_trigger_check');

        /** Register License API EXEC CLASS
         * @since    1.0.0
         */
        global $bs_formular2_license_exec;
        $bs_formular2_license_exec = BS_Formular2_License_Exec_Api::instance($this->get_db_version(), $this->get_version());

        /** Register License API WP-REMOTE CLASS
         * @since    1.0.0
         */
        global $bs_formular2_license_wp_remote;
        $bs_formular2_license_wp_remote = Hupa_Server_WP_Remote_Handle::instance();
        $this->remote = $bs_formular2_license_wp_remote;

        $this->loader->add_action('plugin_loaded', $this->remote, 'wp_loaded_bs_formular2_remote');

    }

    /**
     * Basic settings Options for BS-Formular2
     *
     * Uses the BS_Formular2_Options class to register the options and hook.
     * register with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_options()
    {

        $this->options = BS_Formular2_Options::instance();
        $this->loader->add_action('init', $this->options, 'bs_formular2_set_default_options');
        $this->loader->add_action('bs_formular_array_to_object', $this->options, 'bsFormular2ArrayToObject');

    }

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Bs_Formular2_Admin( $this->get_plugin_name(), $this->get_version(), $this->main );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bs_Formular2_Public( $this->get_plugin_name(), $this->get_version(), $this->main );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name(): string
    {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bs_Formular2_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): Bs_Formular2_Loader
    {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(): string
    {
		return $this->version;
	}

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_db_version(): string
    {
        return $this->db_version;
    }

}
