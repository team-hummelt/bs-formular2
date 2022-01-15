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

final class BS_Formular_Formular_Table
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
     * @param string|null $col
     * @return object
     */
    public function bsFormGetFormulareByArgs($args, bool $fetchMethod = true, string $col = NULL): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $return->count = 0;
        $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
        $table = $wpdb->prefix . $this->table_formulare2;
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
     * @param $record
     * @return object
     */
    public function bsFormSetFormular($record): object
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        $wpdb->insert(
            $table,
            array(
                'shortcode' => $record->form_id,
                'bezeichnung' => $record->bezeichnung,
                'layout' => $record->layout,
                'inputs' => $record->form_inputs,
                'user_layout' => $record->user_layout,
                'form_meldungen' => $record->form_meldungen,
                'input_class' => $record->input_class,
                'form_class' => $record->form_class,
                'label_class' => $record->label_class,
                'class_aktiv' => $record->class_aktiv,
                'btn_class' => $record->btn_class,
                'btn_icon' => $record->btn_icon,
                'redirect_page' => $record->redirect_page,
                'redirect_aktiv' => $record->redirection_aktiv,
                'send_redirection_data_aktiv' => $record->send_redirection_data_aktiv,
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s','%d','%d','%d')
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
    public function updateBsFormular($record): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        $wpdb->update(
            $table,
            array(
                'bezeichnung' => $record->bezeichnung,
                'layout' => $record->layout,
                'inputs' => $record->form_inputs,
                'user_layout' => $record->user_layout,
                'input_class' => $record->input_class,
                'form_class' => $record->form_class,
                'label_class' => $record->label_class,
                'class_aktiv' => $record->class_aktiv,
                'btn_class' => $record->btn_class,
                'btn_icon' => $record->btn_icon,
                'redirect_page' => $record->redirect_page,
                'redirect_aktiv' => $record->redirection_aktiv,
                'send_redirection_data_aktiv' => $record->send_redirection_data_aktiv,
            ),
            array('id' => $record->id),
            array(
                '%s','%s','%s','%s','%s','%s','%s','%d','%s','%s','%d','%d','%d',
            ),
            array('%d')
        );
    }

    /**
     * @param $id
     */
    public function deleteBsFormular($id): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        $wpdb->delete(
            $table,
            array(
                'id' => $id
            ),
            array('%d')
        );

        $table = $wpdb->prefix . $this->table_formular2_message;
        $wpdb->delete(
            $table,
            array(
                'formId' => $id
            ),
            array('%d')
        );
    }

    /**
     * @param $record
     */
    public function updateFormMeldungen($record): void
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        $wpdb->update(
            $table,
            array(
                'form_meldungen' => $record->form_meldungen,
            ),
            array('id' => $record->id),
            array(
                '%s'
            ),
            array('%d')
        );
    }

    /**
     * @param $args
     * @param bool $fetchMethod
     * @return object
     */
    public function bsFormFormularDataByJoin($args, bool $fetchMethod = true): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;
        $return->count = 0;
        $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
        $f = $wpdb->prefix . $this->table_formulare2;
        $fm = $wpdb->prefix . $this->table_formular2_message;
        $result = $wpdb->$fetch("SELECT {$f}.* ,
									  DATE_FORMAT({$f}.created_at, '%d.%m.%Y %H:%i:%s') AS created,
       								  {$fm}.betreff, {$fm}.email_at, {$fm}.email_cc, {$fm}.message,{$fm}.response_aktiv,
									  {$fm}.auto_betreff, {$fm}.auto_msg, {$fm}.email_template
									  FROM {$f} 
									  LEFT JOIN {$fm} ON {$f}.id = {$fm}.formId {$args}");
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
     * @param $args
     * @return object
     */
    public function bsFormGetFormulareInputsById($args): object
    {
        global $wpdb;
        $return = new stdClass();
        $return->status = false;

        $table = $wpdb->prefix . $this->table_formulare2;
        $result = $wpdb->get_row("SELECT inputs  FROM {$table} {$args->where}");
        if (!$result) {
            return $return;
        }

        $inputs = unserialize($result->inputs);
        foreach ($inputs as $tmp) {
            if ($tmp->inputId == $args->id) {
                $return->status = true;
                $return->record = $tmp;

                return $return;
            }
        }

        return $return;
    }


    /**
     * @param $id
     * @param $format
     * @param false $shortcode
     * @return object
     */
    public function get_bs_formular_message($id, $format, bool $shortcode = false): object
    {
        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        if($shortcode){
            $where = sprintf('WHERE shortcode="%s"', $id);
        } else {
            $where = sprintf('WHERE id=%d', $id);
        }

        $return = new stdClass();
        $return->$format = false;
        $result = $wpdb->get_row("SELECT form_meldungen FROM {$table} {$where}");
        if (!$result) {
            return $return;
        }
        $msg = '';
        $data = json_decode($result->form_meldungen);
        foreach ($data as $tmp) {
            if ($tmp->format == $format) {
                $msg = $tmp->msg;
            }
        }
        if (!$msg) {
            $msg = $data[5]->msg;
        }

        $return->$format = $msg;
        return $return;
    }

    /**
     * @param $record
     */
    public function updateRedirectData($record)
    {

        global $wpdb;
        $table = $wpdb->prefix . $this->table_formulare2;
        $wpdb->update(
            $table,
            array(
                'redirect_data' => $record->redirect_data
            ),
            array('shortcode' => $record->shortcode),
            array(
                '%s'
            ),
            array('%s')
        );
    }

}