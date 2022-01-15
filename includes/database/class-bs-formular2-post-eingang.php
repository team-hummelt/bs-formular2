<?php

namespace BS\BSFormular2;
defined('ABSPATH') or die();

use BS\Formular2\BS_Formular2_Defaults_Trait;
use stdClass;

/**
 * The Table BS-Formular2 Formular plugin class.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */

final class BS_Formular_Post_Eingang_Table
{

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
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * The DB-Version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $dbVersion The current DB_Version of this plugin.
     */
    private string $dbVersion;

    /**
     * The Settings ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      int $settings_id The Settings ID of this plugin.
     */
    private int $settings_id;

    /**
     * @param string $dbVersion
     * @param int $settings_id
     * @param string $basename
     * @return static
     */
    public static function instance(string $dbVersion, int $settings_id, string $basename ): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($dbVersion, $settings_id, $basename);
        }
        return self::$instance;
    }

    /**
     * @param string $dbVersion
     * @param int $settings_id
     * @param string $basename
     */
    public function __construct(string $dbVersion, int $settings_id, string $basename)
    {
        $this->dbVersion = $dbVersion;
        $this->settings_id = $settings_id;
        $this->basename = $basename;
    }

    public function bsFormSetEmailEmpfang($record): object
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_post_eingang;
        $wpdb->insert(
            $table,
            array(
                'form_id' => $record->form_id,
                'betreff' => $record->betreff,
                'email_at' => $record->email_at,
                'abs_ip' => $record->abs_ip,
                'message' => $record->message,
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );
        $return = new stdClass();
        if (!$wpdb->insert_id) {
            $return->status = false;
            $return->msg = 'Daten konnten nicht gespeichert werden!';
            $return->id = false;

            return $return;
        }
        $return->status = true;
        $return->msg = 'Daten gespeichert!';
        $return->id = $wpdb->insert_id;

        return $return;
    }

    /**
     * @param $args
     * @param bool $fetchMethod
     * @return object
     */
    public function getEmailEmpfangData($args, bool $fetchMethod = true): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $return->count = 0;
        $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
        $tm = $wpdb->prefix . $this->table_formular2_post_eingang;
        $f = $wpdb->prefix . $this->table_formulare2;
        $fm = $wpdb->prefix . $this->table_formular2_message;
        $result = $wpdb->$fetch("SELECT {$tm}.*,DATE_FORMAT({$tm}.created_at, '%d.%m.%Y %H:%i:%s') AS created,
       								  $f.bezeichnung, $f.shortcode								
									  FROM {$tm} 
									  LEFT JOIN {$f} ON {$tm}.form_id = {$f}.id   
									   {$args}");
        if (!$result) {
            return $return;
        }
        $fetchMethod ? $count = count($result) : $count = 1;
        $return->count = $count;
        $return->status = true;
        $return->record = $result;

        return $return;
    }

    /**
     * @param $id
     */
    public function deleteFormularEmail($id): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_post_eingang;
        $wpdb->delete(
            $table,
            array(
                'id' => $id
            ),
            array('%d')
        );
    }

}