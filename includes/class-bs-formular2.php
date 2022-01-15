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


use BS\BSFormular2\BS_Formular2_Helper;
use BS\BSFormular2\BS_Formular_Formular_Table;
use BS\BSFormular2\BS_Formular_Message_Table;
use BS\BSFormular2\BS_Formular_Post_Eingang_Table;
use BS\BSFormular2\BS_Formular_Public_Ajax_Handle;
use BS\BSFormular2\BS_Formular_Settings_Table;
use BS\Formular2\Bs_Formular2_Admin;
use BS\Formular2\BS_Formular2_Form_Inputs;
use BS\Formular2\BS_Formular2_Form_Validate_Inputs;
use BS\Formular2\BS_Formular2_Options;
use BS\Formular2\Bs_Formular2_Public;
use BSFormular2\License\Hupa_License_Register;
use BS\BSFormular2\BS_Formular2_Database;
use BSFormular2APIExec\EXEC\Hupa_License_Exec_Api;
use Hupa\BsFormular2License\Hupa_Server_WP_Remote_Handle;
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
     * The settings id for settings table of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      int    $settings_id    The settings id for settings table.
     */
    protected int $settings_id;

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
     * The plugin Slug Path.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_slug    plugin Slug Path.
     */
    protected string $plugin_slug;

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
		if ( defined( 'BS_FORMULAR2_PLUGIN_VERSION' ) ) {
			$this->version = BS_FORMULAR2_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}

        if ( defined( 'BS_FORMULAR2_PLUGIN_DB_VERSION' ) ) {
            $this->db_version = BS_FORMULAR2_PLUGIN_DB_VERSION;
        } else {
            $this->db_version = '1.0.0';
        }

        if ( defined( 'BS_FORMULAR2_SETTINGS_ID' ) ) {
            $this->settings_id = BS_FORMULAR2_SETTINGS_ID;
        } else {
            $this->settings_id = 1;
        }

		$this->plugin_name = BS_FORMULAR2_BASENAME;
        $this->plugin_slug = BS_FORMULAR2_SLUG_PATH;
        $this->main = $this;

        //Check PHP AND WordPress Version
        $this->check_dependencies();
        // Require dependencies
		$this->load_dependencies();
        //Set Locale "bs-formular2"
		$this->set_locale();
        // Register License and Import Data from Server
        if(BS_FORMULAR2_Requires_Activation){
            $this->register_bs_formular2_license();
        }
        // Set Settings Default-Optionen AND Helper Functions
        $this->bs_formular2_options();
        // Set Inputs Functions
        $this->bs_formular2_input_handle();
        // Set Validate Formulare Functions
        $this->bs_formular2_validate_input_handle();
        // BS-Formular2 Database
        $this->bs_formular2_database();
        // BS-Formular2 Database
        $this->bs_formular2_database_hooks();
        //BS-Formular2 Helper
        $this->bs_formular2_helper();
        $options = get_option($this->plugin_name . '_server_api');
        if ($options['product_install_authorize']) {
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/database/class-bs-formular2-database.php';

        /**
         * The class for the Database TABLE bs_formular2_settings
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/database/class-bs-formular2-settings-table.php';

        /**
         * The class for the Database TABLE bs_formular2_message
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/database/class-bs-formular2-message-table.php';

        /**
         * The class for the Database TABLE bs_formular2
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/database/class-bs-formular2-formular-table.php';

        /**
         * The class for the Database TABLE bs_formular2_post_eingang
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/database/class-bs-formular2-post-eingang.php';

        /**
         * The class for the Options of the BS-Formular2
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
         * The BS-Formular2 Helper Class.
         *
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bs-formular2-helper.php';

        /**
         * The BS-Formular2 PHPMAILER SMTP-Test.
         *
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Mailer/class-bs-formular2-smtp-test.php';

        /**
         * The class for create and validate Formulars.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bs-formular2-form-inputs.php';

        /**
         * The class for validate input Formulars.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bs-formular2-validate-inputs.php';

        /**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bs-formular2-admin.php';

        /**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bs-formular2-public.php';


        /**
         * The class for defining AJAX in the public area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/ajax/bs-form-public-ajax.php';

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
        if (version_compare(PHP_VERSION, BS_FORMULAR2_MIN_PHP_VERSION, '<') || $wp_version < BS_FORMULAR2_MIN_WP_VERSION) {
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
        deactivate_plugins($this->plugin_slug);
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
        echo sprintf('<div class="error" style="margin-top:5rem"><p>' . __('This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'hupa-api-editor') . '</p></div>', BS_FORMULAR2_MIN_PHP_VERSION, BS_FORMULAR2_MIN_WP_VERSION);
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
    private function register_bs_formular2_license()
    {

        $this->license = Hupa_License_Register::instance($this->get_plugin_name(), $this->get_version());
        /** Register License Admin Menu
         * @since    1.0.0
         */

        // TODO REGISTER LICENSE MENU
        $options = get_option($this->plugin_name . '_server_api');
        if (BS_FORMULAR2_Requires_Activation && !$options['product_install_authorize']) {
            $this->loader->add_action('admin_menu', $this->license, 'register_hupa_license_menu');
        }

        $this->loader->add_action('wp_ajax_BsFormular2LicenceHandle', $this->license, 'prefix_ajax_BsFormular2LicenceHandle');
        $this->loader->add_action('init', $this->license, 'hupa_license_site_trigger_check');
        $this->loader->add_action('template_redirect', $this->license, 'hupa_license_callback_trigger_check');

        /** Register License API EXEC CLASS
         * @since    1.0.0
         */
        global $hupa_license_exec;
        $hupa_license_exec = Hupa_License_Exec_Api::instance($this->get_db_version(), $this->get_version(), $this->get_plugin_name(), $this->get_plugin_slug());

        /** Register License API WP-REMOTE CLASS
         * @since    1.0.0
         */
        global $hupa_license_wp_remote;
        $hupa_license_wp_remote = Hupa_Server_WP_Remote_Handle::instance($this->get_plugin_name(), $this->get_version());
        $this->remote = $hupa_license_wp_remote;

        $this->loader->add_action('plugin_loaded', $this->remote, 'wp_loaded_wp_remote');

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
        $this->options = BS_Formular2_Options::instance($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('init', $this->options, 'bs_formular2_set_default_options');
        $this->loader->add_filter('bs_form_default_settings', $this->options, 'get_bs_form2_default_settings',10 ,2);
        //TODO E-Mail Template auswahl
        $this->loader->add_filter('bs_form_select_email_template', $this->options, 'bsFormSelectEmailTemplate',10, 2);

        global $bsForm2Option;
        $bsForm2Option = $this->options;
    }

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

        $plugin_admin = Bs_Formular2_Admin::instance($this->get_plugin_name(), $this->get_version(), $this->main);

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // TODO GUTENBERG PLUGIN
        $this->loader->add_action('init', $plugin_admin, 'gutenberg_block_bootstrap_formular2_register');
        $this->loader->add_action('enqueue_block_editor_assets', $plugin_admin, 'bs_formular2_plugin_editor_block_scripts');

        //TODO REGISTER Admin-Menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_bs_formular2_menu');
        //TODO REGISTER WP-Mailer
        $this->loader->add_action('phpmailer_init', $plugin_admin, 'bs_formular2_mailer_phpmailer_configure');
        $this->loader->add_action('wp_mail_content_type', $plugin_admin, 'bs_formular2_mail_content_type');
        $this->loader->add_action('wp_mail_failed', $plugin_admin, 'bs_formular2_log_mailer_errors');

        // TODO AJAX ADMIN RESPONSE HANDLE
        $this->loader->add_action('wp_ajax_BsFormularHandle', $plugin_admin, 'prefix_ajax_BsFormularHandle');

    }

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

        $plugin_public = Bs_Formular2_Public::instance($this->get_plugin_name(), $this->get_version(), $this->main );

        // TODO AJAX PUBLIC RESPONSE HANDLE
        $this->loader->add_action('wp_ajax_nopriv_BsFormularNoAdmin', $plugin_public, 'prefix_ajax_BsFormularNoAdmin');
        $this->loader->add_action('wp_ajax_BsFormularNoAdmin', $plugin_public, 'prefix_ajax_BsFormularNoAdmin');

        // TODO AJAX PUBLIC RESPONSE CLASS
        global $publicAjaxHandle;
        $publicAjaxHandle = BS_Formular_Public_Ajax_Handle::instance($this->get_version(), $this->get_plugin_name());

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

    /**
     * Register all the DATABASE hooks
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_database() {

        global $bsFormular2Database;
        $bsFormular2Database = new BS_Formular2_Database($this->get_db_version());
        /**
         * Create Database
         * @since    1.0.0
         */
        $this->loader->add_action('init', $bsFormular2Database, 'update_create_bs_formular2_database');
    }

    /**
     * Database Tables bs_formulare2|bs_formular2_settings|bs_formular2_message|bs_formular2_post_eingang
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_database_hooks() {

        /**
         * Table bs_formular2_settings
         * @since    1.0.0
         */
        global $bsFormularTableSettings;
        $bsFormularTableSettings = new BS_Formular_Settings_Table($this->get_db_version(), $this->get_settings_id());
        // Set Default Settings
        $this->loader->add_filter('set_formular2_settings', $bsFormularTableSettings, 'set_bs_formular2_settings', 10,2);
        // Get Settings by Select
        $this->loader->add_filter('bs_form_get_settings_by_select', $bsFormularTableSettings, 'bsFormGetFormularSettingsByArgs');
        // UPDATE Default Settings
        $this->loader->add_filter('bs_update_default_settings', $bsFormularTableSettings, 'updateDefaultSettings',10,2);


        /**
         * Table bs_formular2
         * @since    1.0.0
         */
        global $bsFormularTable;
        $bsFormularTable = new BS_Formular_Formular_Table($this->get_db_version(), $this->get_settings_id(), $this->get_plugin_name());

        // GET Formulare
        $this->loader->add_filter('get_formulare_by_args', $bsFormularTable, 'bsFormGetFormulareByArgs', 10, 3);
        // Formular Speichern
        $this->loader->add_filter('set_bs_formular', $bsFormularTable, 'bsFormSetFormular');
        // Formular Update
        $this->loader->add_filter('update_bs_formular', $bsFormularTable, 'updateBsFormular');
        // Formular löschen
        $this->loader->add_filter('delete_bs_formular', $bsFormularTable, 'deleteBsFormular');
        // Update Formular Meldungen
        $this->loader->add_filter('update_bs_form_meldungen', $bsFormularTable, 'updateFormMeldungen');
        // Get Formulardaten by JOIN
        $this->loader->add_filter('bs_form_formular_data_by_join', $bsFormularTable, 'bsFormFormularDataByJoin',10 ,2);
        // Get Input By ID
        $this->loader->add_filter('get_formular_inputs_by_id', $bsFormularTable, 'bsFormGetFormulareInputsById');
        //UPDATE REDIRECT DATA
        $this->loader->add_filter('bs_form_update_redirect_data', $bsFormularTable, 'updateRedirectData');
        //GET Formular Meldungen by ID|Shortcode
        $this->loader->add_filter('bs_formular_message', $bsFormularTable, 'get_bs_formular_message',10,3);

        /**
         * Table bs_formular2_message
         * @since    1.0.0
         */
        global $bsFormularMessageTable;
        $bsFormularMessageTable = new BS_Formular_Message_Table($this->get_db_version(), $this->get_settings_id(), $this->get_plugin_name());

        //GET Formular Message
        $this->loader->add_filter('get_formular_message_by_args', $bsFormularMessageTable, 'bsFormGetFormularMessageByArgs', 10, 3);
        // Formular E-Mail Message Update
        $this->loader->add_filter('update_form_message_email_txt', $bsFormularMessageTable, 'updateMessageEmailTxt', 10, 2);
        // Formular Message Speichern
        $this->loader->add_filter('set_bs_message_formular', $bsFormularMessageTable, 'setMessageFormular');
        // Formular Send Message Update
        $this->loader->add_filter('update_bs_msg_formular', $bsFormularMessageTable, 'updateFormMessage');
        // Formular Auto Send Message Update
        $this->loader->add_filter('update_form_auto_message', $bsFormularMessageTable, 'updateFormAutoMessage', 10,2);

        /**
         * Table bs_formular2_post_eingang
         * @since    1.0.0
         */
        global $bsFormularPostEingangTable;
        $bsFormularPostEingangTable = new BS_Formular_Post_Eingang_Table($this->get_db_version(), $this->get_settings_id(), $this->get_plugin_name());

        //Set E-Mail Data
        $this->loader->add_filter('set_email_empfang_table', $bsFormularPostEingangTable, 'bsFormSetEmailEmpfang');
        // Get E-Mail Data
        $this->loader->add_filter('get_email_empfang_data', $bsFormularPostEingangTable, 'getEmailEmpfangData',10,2);
        // Delete E-Mail
        $this->loader->add_filter('delete_bs_formular_email', $bsFormularPostEingangTable, 'deleteFormularEmail');

    }

    /**
     * Register Class BS_Formular2_Form_Inputs hooks
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_input_handle() {
        global $bsFormularInputHandle;
        $bsFormularInputHandle = BS_Formular2_Form_Inputs::instance($this->get_plugin_name(), $this->get_version(), $this->get_settings_id());

        // TODO CREATE FORMULAR FELDER
        $this->loader->add_filter('create_formular_fields', $bsFormularInputHandle, 'bs_form_create_formular_fields');

    }


    /**
     * Register Class BS_Formular2_Form_Validate_Inputs hooks
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_validate_input_handle(){
        global $bsFormularValidateInput;
        $bsFormularValidateInput = BS_Formular2_Form_Validate_Inputs::instance($this->get_plugin_name(),$this->get_version(), $this->get_settings_id());

        // TODO VALIDATE FORMULAR SEND MESSAGE
        $this->loader->add_filter('bs_formular_validate_message_inputs', $bsFormularValidateInput, 'bsFormularValidateMessageInputs', 10, 3);
        // TODO VALIDATE formular Input Checkbox / Radio
        $this->loader->add_filter('validate_formular_radio_checkbox', $bsFormularValidateInput, 'validateFormularRadioCheckbox', 10, 3);

    }

    /**
     * Register Class BS-Formular2_Helper hooks
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function bs_formular2_helper() {
        global $bs_formular2_helper;
        $bs_formular2_helper = BS_Formular2_Helper::instance($this->get_plugin_name(), $this->get_version(), $this->get_db_version(),$this->main);

        $this->loader->add_filter('bs_array_to_object', $bs_formular2_helper, 'bsFormular2ArrayToObject');
        $this->loader->add_filter('bs_load_random_string',$bs_formular2_helper, 'bs_formular2_load_random_string');
        $this->loader->add_filter('bs_generate_random_id', $bs_formular2_helper, 'getBSFormular2GenerateRandomId', 10, 4);
        $this->loader->add_filter('bs_file_size_convert', $bs_formular2_helper, 'BsFormular2FileSizeConvert');

        //Todo Create File multi Upload
        $this->loader->add_filter('re_array_files', $bs_formular2_helper, 'reArrayFiles');
        //TODO DESTROY DIR
        $this->loader->add_action('bs_form_destroy_dir', $bs_formular2_helper, 'bsFormDestroyDir');
        //TODO Delete File Input Folders
        $this->loader->add_action('bs_form_delete_file_folder', $bs_formular2_helper, 'bsFormDeleteFileFolder');
        //TODO STRING REPLACE
        $this->loader->add_filter('string_replace_limit', $bs_formular2_helper, 'bs_form_string_replace_limit',10,4);

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
     * The SLUG of the plugin used to uniquely identify it within the context of
     *
     * @since     1.0.0
     * @return    string    The SLUG of the plugin.
     */
    public  function get_plugin_slug():string {
       return $this->plugin_slug;
    }

    /**
     * Settings ID for Plugin.
     * @return int
     * @since    1.0.0
     */
    public function get_settings_id():int {
      return $this->settings_id;
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
