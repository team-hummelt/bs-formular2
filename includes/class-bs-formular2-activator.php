<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Bs_Formular2_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if(BS_FORMULAR2_Requires_Activation):
        $register = BS_FORMULAR2_PLUGIN_ADMIN_DIR . 'class-bs-formular2-admin.php';
        $options = get_option( 'bs-formular2_server_api');
        if(!$options['product_install_authorize']){
            $input = '';
            file_put_contents($register,$input);
        }
        delete_option('bs-formular2_server_api');
        $infoTxt = 'aktiviert am ' . date('d.m.Y H:i:s')."\r\n";
        file_put_contents(BS_FORMULAR2_PLUGIN_ADMIN_DIR . BS_FORMULAR2_BASENAME. '-license.txt',$infoTxt,  FILE_APPEND | LOCK_EX);
        set_transient(BS_FORMULAR2_BASENAME . '_lizenz_info', true, 5);
	    endif;
    }
}

