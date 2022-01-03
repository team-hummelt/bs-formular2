<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Bs_Formular2_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        delete_option("bs_formular_product_install_authorize");
        delete_option("bs_formular_client_id");
        delete_option("bs_formular_client_secret");
        delete_option("bs_formular_message");
        delete_option("bs_formular_access_token");
        $infoTxt = 'deaktiviert am ' . date('d.m.Y H:i:s')."\r\n";
        file_put_contents(BS_FORMULAR_PLUGIN_ADMIN_DIR.'hupa-bs-formular',$infoTxt,  FILE_APPEND | LOCK_EX);
	}
}
