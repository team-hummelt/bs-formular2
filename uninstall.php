<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'bs_formulare';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_name = $wpdb->prefix . 'bs_form_message';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_name = $wpdb->prefix . 'bs_post_eingang';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_name = $wpdb->prefix . 'bs_form_settings';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);


delete_option("jal_bs_formular_db_version");

delete_option("email_empfang_aktiv");
delete_option("email_abs_name");
delete_option("bs_abs_email");
delete_option("bs_form_smtp_host");
delete_option("bs_form_smtp_auth_check");
delete_option("bs_form_smtp_port");
delete_option("bs_form_email_benutzer");
delete_option("bs_form_email_passwort");
delete_option("bs_form_smtp_secure");

delete_option("bs_formular_product_install_authorize");
delete_option("bs_formular_client_id");
delete_option("bs_formular_client_secret");
delete_option("bs_formular_message");
delete_option("bs_formular_access_token");
delete_option('bs_formular_install_time');
delete_option('bs_formular2_options');


delete_transient('bs_formular2_show_lizenz_info');