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
        $register = BS_FORMULAR_PLUGIN_ADMIN_DIR . 'class-hupa-api-editor-admin.php';
        if(!get_option('hupa_api_editor_product_install_authorize')){
            $input = '';
            file_put_contents($register,$input);
        }
        delete_option("bs_formular_product_install_authorize");
        delete_option("bs_formular_client_id");
        delete_option("bs_formular_client_secret");
        delete_option("bs_formular_message");
        delete_option("bs_formular_access_token");
        $infoTxt = 'aktiviert am ' . date('d.m.Y H:i:s')."\r\n";
        file_put_contents(BS_FORMULAR_PLUGIN_ADMIN_DIR.'hupa-bs-formular.txt',$infoTxt,  FILE_APPEND | LOCK_EX);
        set_transient('bs_formular2_show_lizenz_info', true, 5);
	}
}

