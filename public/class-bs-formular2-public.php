<?php
namespace BS\Formular2;
use BS\BSFormular2\BS_Formular_Public_Ajax_Handle;
use Bs_Formular2;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/public
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Bs_Formular2_Public {

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
     * The plugin dir.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_dir    plugin dir Path.
     */
    protected string $plugin_dir;

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
	 * @param string    $basename    The name of the plugin.
	 * @param string    $version        The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct(string $basename, string $version, $main ) {

		$this->basename = $basename;
		$this->version = $version;
        $this->main = $main;
        $this->plugin_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename;
	}

    /**
     * Register BS-Formular2 AJAX NO ADMIN RESPONSE HANDLE
     *
     * @since    1.0.0
     */
    public function prefix_ajax_BsFormularNoAdmin(): void
    {

        check_ajax_referer('bs_form_public_handle');

        /**
         * The class for defining AJAX in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/ajax/bs-form-public-ajax.php';
        $publicAjaxHandle = BS_Formular_Public_Ajax_Handle::instance($this->version, $this->basename);
        wp_send_json($publicAjaxHandle->bs_formular_public_ajax_handle());
    }


	/**
	 * Register the stylesheets for the public-facing side of the site.
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

        $ifHupaStarter = wp_get_theme('hupa-starter');
        if (!$ifHupaStarter->exists()) {
            $modificated = date( 'YmdHi', filemtime( $this->plugin_dir . '/admin/css/font-awesome.css' ) );
            wp_enqueue_style( 'bootstrap-formular-font-awesome', plugins_url( $this->basename ) . '/admin/css/font-awesome.css', array(),$modificated , '' );

            $modificated = date( 'YmdHi', filemtime( $this->plugin_dir . '/assets/public/css/bs/bootstrap.min.css' ) );
            wp_enqueue_style( 'bootstrap-formular-namespace', plugins_url( $this->basename ) . '/public/css/bs/bootstrap.min.css', array(),$modificated , '' );
        }

        $modificated = date( 'YmdHi', filemtime( $this->plugin_dir . '/public/css/bs-formular-public.css' ) );
        wp_enqueue_style( 'bootstrap-formular-public-style', plugins_url( $this->basename ) . '/public/css/bs-formular-public.css', array(), $modificated, '');
        $modificated = date( 'YmdHi', filemtime( $this->plugin_dir . '/public/js/bs-formular-public.js' ) );
        wp_enqueue_script( 'bootstrap-formular-public-script', plugins_url( $this->basename ) . '/public/js/bs-formular-public.js', array(),$modificated, true );
        //filepond
        $modificated = date( 'YmdHi', filemtime( $this->plugin_dir . '/public/js/filepond/filepond-config.js' ) );
        wp_enqueue_script( 'bootstrap-formular-filepond-script', plugins_url( $this->basename ) . '/public/js/filepond/filepond-config.js', array(),$modificated, true );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

        $ifHupaStarter = wp_get_theme('hupa-starter');
        if (!$ifHupaStarter->exists()) {
            $modificated = date('YmdHi', filemtime($this->plugin_dir . '/public/js/bs/bootstrap.bundle.min.js'));
            wp_enqueue_script( 'bootstrap-bs-formular', plugins_url( $this->basename ) . '/public/js/bs/bootstrap.bundle.min.js', array(),$modificated, true );
        }

        $fileLang = $this->get_theme_default_settings('file_upload_language');
        $options = get_option($this->basename . '-get-options');
        $redirectData = [];
        global $post;
        $formData = apply_filters('get_formulare_by_args','WHERE redirect_page='.$post->ID.'', false);
        if($formData->status) {
            $data = $formData->record;
            if(isset($data->redirect_data) && $data->redirect_data) {
                $redirectData = json_decode($data->redirect_data);
            }
        }

        $title_nonce = wp_create_nonce('bs_form_public_handle');
        wp_register_script('bs-formular-public-ajax-script', '', [], '', true);
        wp_enqueue_script('bs-formular-public-ajax-script');
        wp_localize_script('bs-formular-public-ajax-script', 'bs_form_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce,
            'bs_form_redirect_data' => $redirectData,
            'file_size' => $options['file_max_size'] * 1024 * 1024,
            'post_id' => $post->ID,
            'file_size_mb' => ['file_max_size'],
            'file_size_all_mb' => $options['file_max_all_size'],
            'max_files' => $options['upload_max_files'],
            'assets_url' => plugins_url($this->basename) .'/public/',
            'language' => $fileLang
        ));

        if($formData->status) {
            $updData = [
                'shortcode' => $formData->record->shortcode,
                'redirect_data' => ''
            ];
            apply_filters('bs_form_update_redirect_data', (object) $updData);
        }

	}

}
