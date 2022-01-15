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

final class BS_Formular_Message_Table
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

    /**
     * @param $args
     * @param bool $fetchMethod
     * @param null $col
     * @return object
     */
    public function bsFormGetFormularMessageByArgs($args, bool $fetchMethod = true, $col = NULL): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $return->count = 0;
        $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
        $table = $wpdb->prefix . $this->table_formular2_message;
        $col ? $select = $col : $select = '*';
        $result = $wpdb->$fetch("SELECT {$select} ,DATE_FORMAT(created_at, '%d.%m.%Y %H:%i:%s') AS created  FROM {$table} {$args}");
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
     * @param $input
     * @param $id
     */
    public function updateMessageEmailTxt($input, $id)
    {
        $args = sprintf(' WHERE formId=%d', $id);
        $formMsg = $this->bsFormGetFormularMessageByArgs($args, false);
        if (!$formMsg->status) {
            return;
        }

        $regExp = '@\[.*?]@m';
        $message = $formMsg->record->message;
        preg_match_all($regExp, $message, $matches, PREG_SET_ORDER, 0);
        $MessArr = [];
        foreach ($matches as $tmp) {
            if ($tmp[0]) {
                $MessArr[] = $tmp[0];
            }
        }
        $auto_msg = $formMsg->record->auto_msg;
        preg_match_all($regExp, $auto_msg, $matches, PREG_SET_ORDER, 0);
        $AutoMessArr = [];
        foreach ($matches as $tmp) {
            if ($tmp[0]) {
                $AutoMessArr[] = $tmp[0];
            }
        }

        $inArr = [];
        foreach ($input as $tmp) {
            if ($tmp->type == 'button' || $tmp->type == 'dataprotection') {
                continue;
            }
            if ($tmp->type == 'select' || $tmp->type == 'radio') {
                $userVal = $tmp->label . ' - ' . $tmp->type;
            } else {
                $userVal = $tmp->values;
            }
            $inArr[] = '[' . $userVal . ']';
        }

        foreach ($MessArr as $tmp) {
            if (!in_array($tmp, $inArr)) {
                $message = str_replace($tmp, '', $message);
            }
        }

        foreach ($AutoMessArr as $tmp) {
            if (!in_array($tmp, $inArr)) {
                $auto_msg = str_replace($tmp, '', $auto_msg);
            }
        }

        $record = new stdClass();
        $record->message = $message;
        $record->auto_msg = $auto_msg;
        $record->id = $id;
        $this->update_db_message_text($record);
    }

    /**
     * @param $record
     */
    public function update_db_message_text($record)
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_message;
        $wpdb->update(
            $table,
            array(
                'message' => $record->message,
                'auto_msg' => $record->auto_msg
            ),
            array('id' => $record->id),
            array(
                '%s',
                '%s'
            ),
            array('%d')
        );
    }

    /**
     * @param $record
     * @return object
     */
    public function setMessageFormular($record): object
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_message;
        $wpdb->insert(
            $table,
            array(
                'formId' => $record->formId,
                'betreff' => $record->betreff,
                'email_at' => $record->email_at,
                'message' => $record->message,
            ),
            array('%d', '%s', '%s', '%s')
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
     * @param $record
     */
    public function updateFormMessage($record): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_message;
        $wpdb->update(
            $table,
            array(
                'email_cc' => $record->email_cc,
                'betreff' => $record->betreff,
                'email_at' => $record->email,
                'message' => $record->message,
                'email_template' => $record->email_template
            ),
            array('id' => $record->id),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%d'
            ),
            array('%d')
        );
    }

    /**
     * @param $record
     */
    public function updateFormAutoMessage($record): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formular2_message;
        if ($record->auto_save) {
            $wpdb->update(
                $table,
                array(
                    'response_aktiv' => $record->aktiv,
                ),
                array('id' => $record->id),
                array('%d'),
                array('%d')
            );

            return;
        }

        $wpdb->update(
            $table,
            array(
                'response_aktiv' => $record->aktiv,
                'auto_betreff' => $record->auto_betreff,
                'auto_msg' => $record->auto_msg,
            ),
            array('id' => $record->id),
            array(
                '%d',
                '%s',
                '%s'
            ),
            array('%d')
        );
    }



}