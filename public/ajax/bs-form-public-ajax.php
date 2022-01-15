<?php

namespace BS\BSFormular2;
defined('ABSPATH') or die();

use BS\Formular2\BS_Formular2_Defaults_Trait;
use stdClass;

/**
 * The PUBLIC AJAX RESPONSE plugin class.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */

final class BS_Formular_Public_Ajax_Handle
{

    private static $instance;

    /**
     * The AJAX METHOD
     *
     * @since    1.0.0
     * @access   private
     * @var      string $method The AJAX METHOD.
     */
    protected string $method;


    /**
     * The plugin Slug Path.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_dir  plugin Slug Path.
     */
    protected string $plugin_dir;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $basename The ID of this plugin.
     */
    private string $basename;

    /**
     * The AJAX DATA
     *
     * @since    1.0.0
     * @access   private
     * @var      array|object $data The AJAX DATA.
     */
    private array|object $data;


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
     * @var      string $version The current DB_Version of this plugin.
     */
    private string $version;

    /**
     * The Settings ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      int $settings_id The Settings ID of this plugin.
     */
    private int $settings_id;

    /**
     * @param string $version
     * @param string $basename
     * @return static
     */
    public static function instance(string $version, string $basename): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($version, $basename);
        }
        return self::$instance;
    }

    /**
     * @param string $version
     * @param string $basename
     */
    public function __construct(string $version, string $basename)
    {
        $this->version = $version;
        $this->basename = $basename;
        $this->plugin_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename . DIRECTORY_SEPARATOR;
    }

    /**
     * PUBLIC AJAX RESPONSE.
     * @since    1.0.0
     * @return array|object
     */
    public function bs_formular_public_ajax_handle(): array|object
    {
        global $wpdb;
        $record = new stdClass();
        $responseJson = new stdClass();

        $record->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->formId = filter_input(INPUT_POST, 'formId', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        isset( $_POST['terms'] ) && is_string( $_POST['terms'] ) ? $record->terms = 1 : $record->terms = 0;

        isset($_POST['dscheck']) && is_string($_POST['dscheck']) ? $record->dscheck = 1 : $record->dscheck = 0;
        $_POST['repeat_email'] ? $record->repeat_email = $_POST['repeat_email'] : $record->repeat_email = false;

        if (!$record->id) {
            $msg = apply_filters('bs_formular_message', $record->id,'error_message', true);
            $responseJson->status = false;
            $responseJson->show_error = true;
            $responseJson->formId = $record->formId;
            $responseJson->msg = $msg->error_message;

            return $responseJson;
        }

        if ($record->terms || $record->repeat_email) {
            $msg = apply_filters('bs_formular_message', $record->id,'spam', true);
            $responseJson->status = false;
            $responseJson->show_error = true;
            $responseJson->formId = $record->formId;
            $responseJson->msg = $msg->spam;

            return $responseJson;
        }

        $table = $wpdb->prefix . $this->table_formulare2;
        $args = sprintf('WHERE %s.shortcode="%s"', $table, $record->id);

        $formular = apply_filters('get_formulare_by_args', $args, false, 'id');

        if (!$formular->status) {
            $msg = apply_filters('bs_formular_message', $record->id,'error_message', true);
            $responseJson->status = false;
            $responseJson->show_error = true;
            $responseJson->formId = $record->formId;
            $responseJson->msg = $msg->error_message;

            return $responseJson;
        }


        $args = sprintf('WHERE %s.shortcode="%s"', $table, $record->id);
        $form = apply_filters('bs_form_formular_data_by_join', $args, false);

        if (!$form->status) {
            $msg = apply_filters('bs_formular_message', $record->id,'error_message', true);
            $responseJson->status = false;
            $responseJson->show_error = true;
            $responseJson->formId = $record->formId;
            $responseJson->msg = $msg->error_message;

            return $responseJson;
        }

        $form->record->redirect_page && $form->record->send_redirection_data_aktiv && $form->record->redirect_aktiv ? $redirect = true : $redirect = false;

        $argsData = new stdClass();
        $argsData->shortcode = $record->id;
        $argsData->where = sprintf('WHERE shortcode="%s"', $record->id);
        $send_arr = array();
        $attachments = [];

        foreach ($_POST as $key => $val) {
            $argsData->id = $key;
            $result = apply_filters('get_formular_inputs_by_id', $argsData);

            if (!$result->status) {
                continue;
            }

            $validate = apply_filters('bs_formular_validate_message_inputs', $result->record, $val, $form);
            if (!$validate->status) {
                $responseJson->status = false;
                $responseJson->msg = $validate->msg;
                $responseJson->show_error = true;
                $responseJson->formId = $record->formId;

                return $responseJson;
            }
            $send_arr[] = $validate;
        }

        return $responseJson;
    }
}