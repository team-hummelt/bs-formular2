<?php
namespace BS\Formular2;
use BS\BSFormular2\BS_Formular_Admin_Ajax_Handle;
use Bs_Formular2;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/admin
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Bs_Formular2_Admin {


    private static $instance;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $basename    The ID of this plugin.
	 */
	private string $basename;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
     */
    private string $version;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @var Bs_Formular2 $main The main class.
     */
    protected Bs_Formular2 $main;

    /**
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * @param string $basename
     * @param string $version
     * @param Bs_Formular2 $main
     * @return static
     */
    public static function instance(string $basename, string $version, Bs_Formular2 $main ): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $version, $main);
        }
        return self::$instance;
    }

    /**
     * Initialize the class and set its properties.
     *
     * @param string $basename The name of this plugin.
     * @param string $version The version of this plugin.
     * @param Bs_Formular2 $main The version of this plugin.
     * @since    1.0.0
     */
    public function __construct(string $basename, string $version, Bs_Formular2 $main)
    {
		$this->basename = $basename;
		$this->version = $version;
        $this->main = $main;
	}


    /**
     * Register BS-Formular2 Register Admin Menu
     *
     * @since    1.0.0
     */
    public function register_bs_formular2_menu(): void
    {
        $hook_suffix = add_menu_page(
            __('BS-Formular', 'bs-formular2'),
            __('BS-Formular', 'bs-formular2'),
            'manage_options',
            'bs-formular2',
            array($this, 'admin_bs_formular2_page'),
            'dashicons-email-alt', 7
        );

        add_action('load-' . $hook_suffix, array($this, 'bs_formular2_load_ajax_admin_options_script'));
    }


    /**
     * Register BS-Formular2 ADMIN PAGE
     *
     * @since    1.0.0
     */
    public function admin_bs_formular2_page(): void
    {
        require 'partials/bs-formular2-admin-display.php';
    }

    /**
     * Register BS-Formular2 WP-SMTP Mail
     *
     * @since    1.0.0
     */
    public function bs_formular2_mailer_phpmailer_configure($phpmailer)
    {
        $config = get_option($this->basename . '-get-options');
        $phpmailer->isSMTP();
        $phpmailer->Host = $config['bs_form_smtp_host'];
        $phpmailer->SMTPAuth = $config['bs_form_smtp_auth_check'];
        $phpmailer->Port = $config['bs_form_smtp_port'];
        $phpmailer->Username = $config['bs_form_email_benutzer'];
        $phpmailer->Password = $config['bs_form_email_passwort'];
        $phpmailer->SMTPSecure = $config['bs_form_smtp_secure'];
        $phpmailer->SMTPDebug = 0;
        $phpmailer->CharSet = "utf-8";
    }

    /**
     * Register BS-Formular2 WP-Mail HTML Content
     *
     * @since    1.0.0
     */
    public function bs_formular2_mail_content_type(): string
    {
        return "text/html";
    }

    /**
     * Register BS-Formular2 WP-Mail Error Log
     *
     * @since    1.0.0
     */
    public function bs_formular2_log_mailer_errors($wp_error)
    {
        $dir = 'log' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                return '';
            }
        }

        $file = $dir . 'mail-error.log';
        $current = "Mailer Error: " . $wp_error->get_error_message() . "\n";
        file_put_contents($file, $current, LOCK_EX);
        // $wp_error->get_error_message();
    }


    /**
     * Register BS-Formular2 ADMIN SCRIPTS
     *
     * @since    1.0.0
     */
    public function bs_formular2_load_ajax_admin_options_script()
    {

        add_action('admin_enqueue_scripts', array($this, 'load_bs_formular2_admin_style'));
        $title_nonce = wp_create_nonce('bs_formular_admin_handle');

        wp_register_script('bs-formular-ajax-script', '', [], '', true);
        wp_enqueue_script('bs-formular-ajax-script');
        wp_localize_script('bs-formular-ajax-script', 'bs_form_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce,
        ));
    }

    /**
     * Register BS-Formular2 AJAX ADMIN RESPONSE HANDLE
     *
     * @since    1.0.0
     */
    public function prefix_ajax_BsFormularHandle(): void
    {
        check_ajax_referer('bs_formular_admin_handle');
        /**
         * The class for defining AJAX in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ajax/bs-form-admin-ajax.php';
        $adminAjaxHandle = BS_Formular_Admin_Ajax_Handle::instance($this->version, $this->basename);
        wp_send_json($adminAjaxHandle->bs_formular_admin_ajax_handle());
    }



    /**
     * Register BS-Formular2 Gutenberg Formular Selector
     *
     * @since    1.0.0
     */
    public function gutenberg_block_bootstrap_formular2_register()
    {
        register_block_type('bs/bootstrap-formular', array(
            'render_callback' => 'callback_bootstrap_formular2_block',
            'editor_script' => 'gutenberg-bootstrap-formular2-block',
        ));

        add_filter('gutenberg_block_bs_formular2_render', 'gutenberg_block_bs_formular2_render_filter', 10, 20);
    }

    public function bs_formular2_plugin_editor_block_scripts(): void
    {
        $plugin_asset = require BS_FORMULAR2_PLUGIN_ADMIN_DIR . 'includes/gutenberg/plugin-data/build/index.asset.php';

        // Scripts.
        wp_enqueue_script(
            'gutenberg-bootstrap-formular2-block',
            plugins_url('bs-formular2') . 'includes/gutenberg/plugin-data/build/index.js',
            $plugin_asset['dependencies'], $this->version
        );

        // Styles.
        wp_enqueue_style(
            'gutenberg-bootstrap-formular2-block', // Handle.
            plugins_url('bs-formular2') . '/includes/gutenberg/plugin-data/build/index.css', array(), $this->version
        );

        wp_register_script('bs-formular2-rest-gutenberg-js-localize', '', [], $this->version, true);
        wp_enqueue_script('bs-formular2-rest-gutenberg-js-localize');
        wp_localize_script('bs-formular2-rest-gutenberg-js-localize',
            'WPBSFRestObj',
            array(
                'url' => esc_url_raw(rest_url('bs-formular-endpoint/v1/method/')),
                'nonce' => wp_create_nonce('wp_rest')
            )
        );
    }


    public function load_bs_formular2_admin_style(): void
    {
        //TODO FontAwesome / Bootstrap
        wp_enqueue_style('bs-formular-admin-bs-style', plugins_url('bs-formular2') . '/admin/css/bs/bootstrap.min.css', array(), $this->version, false);
        // TODO ADMIN ICONS
        wp_enqueue_style('bs-formular-admin-icons-style', plugins_url('bs-formular2') . '/admin/css/font-awesome.css', array(), $this->version, false);
        // TODO DASHBOARD STYLES
        wp_enqueue_style('bs-formular-admin-dashboard-style', plugins_url('bs-formular2') . '/admin/css/admin-dashboard-style.css', array(), $this->version, false);
        wp_enqueue_style('bs-formular-data-table-style', plugins_url('bs-formular2') . '/admin/css/tools/dataTables.bootstrap5.min.css', array(), $this->version, false);



        // TODO ADMIN localize Script
        wp_register_script('bs-formular-admin-js-localize', '', [], '', true);
        wp_enqueue_script('bs-formular-admin-js-localize');
        wp_localize_script('bs-formular-admin-js-localize',
            'bs_form',
            array(
                'admin_url' => plugins_url('bs-formular2') .'/admin/',
                'data_table' => plugins_url('bs-formular2') . '/admin/json/DataTablesGerman.json',
                'site_url' => get_bloginfo('url'),
            )
        );

        $language = [
            'create_edit' => $this->get_theme_default_settings('meldungen_site_language'),
        ];
        wp_register_script('bs-formular2-admin-language', '', [], '', true);
        wp_enqueue_script('bs-formular2-admin-language');
        wp_localize_script('bs-formular2-admin-language',
            'bs_form_lang',
            array(
                'lang' => $language
            )
        );

        wp_enqueue_script('jquery');

        wp_enqueue_script('bs-formular-bs', plugins_url('bs-formular2') . '/admin/js/bs/bootstrap.bundle.min.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-tiny5', plugins_url('bs-formular2') . '/admin/js/tools/tiny5/tinymce.min.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-tiny5-jquery', plugins_url('bs-formular2') . '/admin/js/tools/tiny5/jquery.tinymce.min.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-init-tiny5', plugins_url('bs-formular2') . '/admin/js/tools/tiny5/form-tiny-init.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-jquery-table-js', plugins_url('bs-formular2') . '/admin/js/tools/data-table/jquery.dataTables.min.js', array(), $this->version, true);
        wp_enqueue_script('bs5-data-table', plugins_url('bs-formular2') . '/admin/js/tools/data-table/dataTables.bootstrap5.min.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-data-js', plugins_url('bs-formular2') . '/admin/js/admin-formulare.js', array(), $this->version, true);
        wp_enqueue_script('bs-formular-table-bs-js', plugins_url('bs-formular2') . '/admin/js/formular-table.js', array(), $this->version, true);
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bs_Formular2_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Bs_Formular2_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->basename, plugin_dir_url( __FILE__ ) . 'css/bs-formular2-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->basename.'-tools', plugin_dir_url( __FILE__ ) . 'css/tools.css', array(), $this->version, false );
        wp_enqueue_style( $this->basename.'-Glyphter', plugin_dir_url( __FILE__)  . 'css/Glyphter.css', array(), $this->version, false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bs_Formular2_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Bs_Formular2_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bs-formular2-admin.js', array( 'jquery' ), $this->version, true );

	}

}
