<?php

namespace BS\BSFormular2;
defined('ABSPATH') or die();

use BS\Formular2\BS_Formular2_Defaults_Trait;
use stdClass;

/**
 * The Table BS-Formular2 Settings plugin class.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
final class BS_Formular_Settings_Table
{

    private static $instance;

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
     * @param string $dbVersion ;
     * @param int $settings_id ;
     */
    public function __construct(string $dbVersion, int $settings_id)
    {
        $this->dbVersion = $dbVersion;
        $this->settings_id = $settings_id;
    }

    /**
     * @param string $dbVersion ;
     * @param int $settings_id ;
     * @return static
     */
    public static function instance(string $dbVersion, int $settings_id): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($dbVersion, $settings_id);
        }
        return self::$instance;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set_bs_formular2_settings($key, $value)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_settings;
        $wpdb->insert(
            $table,
            array(
                'id' => $this->settings_id,
                $key => $value,
            ),
            array('%s')
        );
    }

    /**
     * @param $select
     * @return object
     */
    public function bsFormGetFormularSettingsByArgs($select): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $table = $wpdb->prefix . $this->table_formular2_settings;
        $where = sprintf('WHERE id=%s', $this->settings_id);
        $result = $wpdb->get_row("SELECT {$select} FROM {$table} {$where}");
        if (!$result) {
            return $return;
        }
        $data = json_decode($result->$select);
        $return->status = true;
        $return->$select = $data;

        return $return;
    }


    /**
     * @param $key
     * @param $value
     */
    public function updateDefaultSettings($key, $value)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_settings;
        $wpdb->update(
            $table,
            array(
                $key => $value,
            ),
            array('id' => $this->settings_id),
            array('%s')
        );
    }
}