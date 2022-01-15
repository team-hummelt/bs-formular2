<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.hummelt-werbeagentur.de
 * @since             1.0.0
 * @package           Bs_Formular2
 *
 * @wordpress-plugin
 * Plugin Name:       BS Formular2 - Boostrap Formular-Plugin
 * Plugin URI:        https://www.hummelt-werbeagentur.de/leistungen/
 * Description:       Bootstrap Formulare mit verschiedenen Ausgabeoptionen und integrierter REST-API.
 * Version:           1.0.0
 * Author:            Jens Wiecker
 * Author URI:        https://www.hummelt-werbeagentur.de
 * License:           MIT License
 * Tested up to:      5.8
 * Stable tag:        1.0.0
 */

// If this file is called directly, abort.
use JetBrains\PhpStorm\NoReturn;

if (!defined('WPINC')) {
    die;
}

/**
 * Check is Install OLD BS-Formular-Version
 *
 * @since    1.0.0
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('bs-formular/bs-formular.php')) {
    deactivate_plugins('bs-formular2/bs-formular2.php');

    add_action('admin_notices','self_deactivate_bs_form2_notice');
}

/**
 * Self-Deactivate Admin Notiz
 * of the plugin.
 *
 * @since    1.0.0
 */
#[NoReturn] function self_deactivate_bs_form2_notice():void
{
    echo '<div class="error" style="margin-top: 4rem"><p>' .
        'Plugin kann nicht aktiviert werden. Es ist eine ältere BS-Formular-Version installiert.' .
        '</p></div>';
}

/**
 * Currently plugin version.
 * @since             1.0.0
 */
$plugin_data = get_file_data(dirname(__FILE__) . '/bs-formular2.php', array('Version' => 'Version'), false);
define("BS_FORMULAR2_PLUGIN_VERSION", $plugin_data['Version']);
/**
 * Currently DATABASE VERSION
 * @since             1.0.0
 */
const BS_FORMULAR2_PLUGIN_DB_VERSION = '1.0.0';

/**
 * MIN PHP VERSION for Activate
 * @since             1.0.0
 */
const BS_FORMULAR2_MIN_PHP_VERSION = '8.0';

/**
 * MIN WordPress VERSION for Activate
 * @since             1.0.0
 */
const BS_FORMULAR2_MIN_WP_VERSION = '5.7';

/**
 * SET Formular Default Message Option
 * @since             1.0.0
 */
const SET_EMAIL_DEFAULT_MELDUNGEN = true;

/**
 * BS-Formular2 Query Settings
 * @since             1.0.0
 */
const BS_FORMULAR_QUERY_VAR = 'get-bs-form-email';
const BS_FORMULAR_QUERY_URI = 1206711901102021;


/**
 * Default Settings ID
 * @since             1.0.0
 */
const BS_FORMULAR2_SETTINGS_ID = 1;


/**
 * Plugin benötigt eine Lizenz zur aktivierung
 * @since             1.0.0
 */
const BS_FORMULAR2_Requires_Activation = false;

/**
 * PLUGIN SLUG
 * @since             1.0.0
 */
define('BS_FORMULAR2_SLUG_PATH', plugin_basename(__FILE__));

/**
 * PLUGIN ADMIN DIR
 * @since             1.0.0
 */
define('BS_FORMULAR2_PLUGIN_ADMIN_DIR', dirname(__FILE__). DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR);

/**
 * PLUGIN BASENAME
 * @since             1.0.0
 */
define('BS_FORMULAR2_BASENAME', plugin_basename(__DIR__));


//PLUGIN INC DIR
define("BS_FORMULAR2_INC", WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . plugin_basename(__DIR__) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR);
// E-MAIL TEMPLATE FOLDER
const EMAIL_TEMPLATES_DIR = BS_FORMULAR2_INC . 'templates' . DIRECTORY_SEPARATOR;

//PLUGIN GUTENBERG DATA PATH
const BS_FORMULAR_GUTENBERG_DATA = BS_FORMULAR2_INC . 'gutenberg' . DIRECTORY_SEPARATOR . 'plugin-data' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;

/**
 * PLUGIN UPLOAD DIR for Formular Upload Function
 * @since             1.0.0
 */
$upload_dir = wp_get_upload_dir();
define("BS_FILE_UPLOAD_DIR", $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'bs-formular-files' . DIRECTORY_SEPARATOR);
define("BS_FILE_UPLOAD_URL", $upload_dir['baseurl'] . '/bs-formular-files/');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bs-formular2-activator.php
 */
function activate_bs_formular2()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-bs-formular2-activator.php';
    Bs_Formular2_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bs-formular2-deactivator.php
 */
function deactivate_bs_formular2() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bs-formular2-deactivator.php';
	Bs_Formular2_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bs_formular2' );
register_deactivation_hook( __FILE__, 'deactivate_bs_formular2' );

if ( is_admin() ) {
    /**
     * @link http://w-shadow.com/blog/2011/06/02/automatic-updates-for-commercial-themes/
     * @link https://github.com/YahnisElsts/plugin-update-checker
     * @link https://github.com/YahnisElsts/wp-update-server
     */
    if( ! class_exists( 'Puc_v4_Factory' ) ) {
        require_once 'vendor/autoload.php';
    }

    get_option(BS_FORMULAR2_BASENAME . '_server_api') !== null ? $options =  get_option(BS_FORMULAR2_BASENAME . '_server_api') : $options = '';
    if ($options && $options['product_install_authorize'] ) {
        delete_transient('bs-formular2_lizenz_info');
        if ( $options['update_aktiv'] == '1' ) {
            $bsFormular2UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                $options['update_url'],
                __FILE__,
                BS_FORMULAR2_BASENAME
            );
            if ( $options['update_type'] == '1' ) {
                $bsFormular2UpdateChecker->getVcsApi()->enableReleaseAssets();
            }
        }
    }

    /**
     * add plugin upgrade notification
     */
    add_action( 'in_plugin_update_message-' . BS_FORMULAR2_SLUG_PATH . '/' . BS_FORMULAR2_SLUG_PATH .'.php', 'bs_formular2_show_upgrade_notification', 10, 2 );
    function bs_formular2_show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ) {

        /**
         *
         * @since    1.0.0
         * Notice	<- message
         */
        if ( isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {

            // Display "upgrade_notice".
            echo sprintf( '<span style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px;display:block;"><strong>%1$s: </strong>%2$s</span>', esc_attr( 'Important Upgrade Notice', 'exopite-multifilter' ), esc_html( rtrim( $new_plugin_metadata->upgrade_notice ) ) );

        }
    }
}

function showWPHupaApiBSForm2Info() {
    if ( get_transient( BS_FORMULAR2_BASENAME.'_lizenz_info' ) ) {
        echo '<div class="error"><p>' .
            'BS-Formular2 ungültige Lizenz: Zum Aktivieren geben Sie Ihre Zugangsdaten ein.' .
            '</p></div>';
    }
}

add_action( 'admin_notices', 'showWPHupaApiBSForm2Info' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bs-formular2.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
global $bsf_bs_formular2;
$bsf_bs_formular2 = new Bs_Formular2();
$bsf_bs_formular2->run();

