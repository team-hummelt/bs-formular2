<?php
namespace BS\BSFormular2;
defined('ABSPATH') or die();
use stdClass;
use BS\Formular2\BS_Formular2_Defaults_Trait;

/**
 * The Database plugin class.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class BS_Formular2_Database
{
    /**
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * The current version of the DB-Version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $dbVersion The current version of the database Version.
     */
    protected string $dbVersion;


    /**
     * @param $db_version
     */
    public function __construct($db_version)
    {
        $this->dbVersion = $db_version;

    }

    /**
     * Insert | Update Table Editor
     * INIT Function
     * @since 1.0.0
     */
    public function update_create_bs_formular2_database()
    {
        if ($this->dbVersion !== get_option('jal_bs_formular2_db_version')) {
            $this->create_bs_formular2_database();
            update_option('jal_bs_formular2_db_version', $this->dbVersion);
            $this->set_bs_formular2_defaults();
        }
    }

    public function set_bs_formular2_defaults()
    {

      $settings = apply_filters('bs_form_get_settings_by_select', 'form_meldungen');
      if(!$settings->status) {
          $defSettings = $this->get_theme_default_settings('default_formular_messages');
          apply_filters('set_formular2_settings', 'form_meldungen', json_encode($defSettings));
      }
    }

    /**
     *
     * CREATE BS-Formular2 Database
     * @since 1.0.0
     */
    private function create_bs_formular2_database() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;

        $table_name = $wpdb->prefix . $this->table_formulare2;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        shortcode varchar(14) NOT NULL UNIQUE, 
        bezeichnung varchar(50) NOT NULL,
        input_class varchar(64) NULL,
        form_class varchar(64) NULL,
        btn_class varchar(64) NULL,
        btn_icon varchar(64) NULL,
        label_class varchar(64) NULL,
        class_aktiv tinyint(1) NOT NULL DEFAULT 0,
        redirect_aktiv tinyint(1) NOT NULL DEFAULT 0,
        send_redirection_data_aktiv tinyint(1) NOT NULL DEFAULT 0,
        redirect_page int(12) NOT NULL DEFAULT 0,
        redirect_data TEXT NULL,
        layout text NOT NULL,
        inputs text NOT NULL,
        user_layout text NOT NULL,
        form_meldungen text NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . $this->table_formular2_message;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        formId mediumint(9) NOT NULL UNIQUE,
        betreff varchar(128) NULL,
        email_at varchar(50) NOT NULL,
        email_cc text NULL,
        message text NOT NULL,
        response_aktiv mediumint(1) NOT NULL DEFAULT 0,
        email_template mediumint(1) NOT NULL DEFAULT 2,
        auto_betreff varchar(128) NULL,
        auto_msg text NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . $this->table_formular2_post_eingang;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL,
        betreff varchar(128) NULL,
        email_at varchar(50) NOT NULL,
        abs_ip varchar(50) NOT NULL,
        message text NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . $this->table_formular2_settings;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL,
        form_meldungen text NULL,
       PRIMARY KEY (id)
     ) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . $this->table_formular2_extensions;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        aktiv tinyint(1) NOT NULL,
        bezeichnung varchar(256) NOT NULL UNIQUE,
        client_id varchar(80) NOT NULL,
        client_secret varchar(80) NOT NULL,
        extension_scope varchar(24) NOT NULL,
        extension_dir text NOT NULL,
        extension_root_file varchar(255) NOT NULL,
        activate_time varchar(24) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
        dbDelta($sql);
    }



}