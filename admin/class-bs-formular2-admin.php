<?php
namespace BS\Formular2;
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

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private string $plugin_name;

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
     * @var Bs_Formular2 $main          The main class.
     */
    protected Bs_Formular2 $main;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string    $plugin_name    The name of this plugin.
	 * @param string    $version        The version of this plugin.

	 *@since    1.0.0
	 */
	public function __construct(string $plugin_name, string $version,  $plugin_main ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->main = $plugin_main;
	}


    /**
     * Register BS-Formular2 Register Admin Menu
     *
     * @since    1.0.0
     */
    public function register_bs_formular2_menu(): void
    {
        $hook_suffix = add_menu_page(
            __('Formulare', 'bs-formular2'),
            __('Formulare', 'bs-formular2'),
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
     * Register BS-Formular2 ADMIN SCRIPTS
     *
     * @since    1.0.0
     */
    public function bs_formular2_load_ajax_admin_options_script() {
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bs-formular2-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name.'-tools', plugin_dir_url( __FILE__ ) . 'css/tools.css', array(), $this->version, false );
        wp_enqueue_style( $this->plugin_name.'-Glyphter', plugin_dir_url( __FILE__)  . 'css/Glyphter.css', array(), $this->version, false );
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
