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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bs-formular2-admin.js', array( 'jquery' ), $this->version, true );

	}

}
