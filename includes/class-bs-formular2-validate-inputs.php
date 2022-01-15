<?php

namespace BS\Formular2;
use stdClass;

defined('ABSPATH') or die();

/**
 * Define the BS-Formular2 functionality
 *
 * FORMULAR Input Validate
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */
class BS_Formular2_Form_Validate_Inputs
{

    private static $instance;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $basename The ID of this plugin.
     */
    private string $basename;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private string $version;

    /**
     * The plugin path.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_dir plugin Path.
     */
    protected string $plugin_dir;

    /**
     * The plugin Settings ID.
     *
     * @since    1.0.0
     * @access   protected
     * @var      int $settings_id Settings ID.
     */
    protected int $settings_id;

    /**
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * @param string $basename
     * @param string $version
     * @param int $settings_id
     * @return static
     */
    public static function instance(string $basename, string $version, int $settings_id): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $version, $settings_id);
        }
        return self::$instance;
    }

    /**
     * @param string $basename
     * @param string $version
     * @param int $settings_id
     */
    public function __construct(string $basename, string $version, int $settings_id)
    {

        $this->basename = $basename;
        $this->version = $version;
        $this->settings_id = $settings_id;
        $this->plugin_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename . DIRECTORY_SEPARATOR;

    }

    /**
     * @param $record
     * @param $input
     * @param null $form
     * @return object
     */
    public function bsFormularValidateMessageInputs($record, $input, $form = null): object
    {

        $return = new stdClass();
        $type = $record->type;
        $form_id = $form->record->id;
        switch ($record->type) {
            case'text':
            case'password':
            case'textarea':
                isset($input) && is_string($input) ? $postValue = sanitize_text_field($input) : $postValue = '';
                if ($record->required && !$postValue) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->label = $record->label;
                $return->type = $record->type;
                $return->eingabe = $input;

                return $return;
            case'number':
                if ($record->required && !$input) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                if ($input && !filter_var($input, FILTER_VALIDATE_INT)) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);
                $return->eingabe = $input;

                return $return;
            case'email':
                $email = sanitize_text_field($input);
                if ($record->required && !$email) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);
                $input ? $return->eingabe = '<a href=mailto:' . $input . '>' . $input . '</a>' : $return->eingabe = false;

                return $return;
            case'date':
                if ($record->required && !$input) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                $regEx = '@^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$@m';
                $date = filter_var($input, FILTER_VALIDATE_REGEXP,
                    array("options" => array("regexp" => $regEx)));
                if ($input && !$date) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);
                $return->eingabe = $date;

                return $return;
            case'select':
                $input = sanitize_text_field($input);
                $select = unserialize($record->values);
                $eingabe = '';
                if ($select) {
                    foreach ($select as $tmp) {
                        if ($tmp['id'] == $input) {
                            $eingabe = $tmp['bezeichnung'];
                            break;
                        } else {
                            $eingabe = false;
                        }
                    }
                }

                if ($record->required && !$eingabe) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                if ($record->required) {
                    $return->eingabe = str_replace('*', '', $eingabe);
                } else {
                    $return->eingabe = $eingabe;
                }
                if (!$return->eingabe) {
                    $return->eingabe = __('Nothing selected', 'bs-formular2');
                }
                $return->status = true;

                $return->user_value = '[' . $record->label . ' - select]';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);

                return $return;
            case 'email-send-select':

                $selInput = json_decode(base64_decode($input));
                $select = unserialize($record->values);

                isset($selInput->email) ? $eingabe = $selInput->email : $eingabe = false;

                if ($record->required && !$eingabe) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                if (isset($selInput->email) && !filter_var($selInput->email, FILTER_VALIDATE_EMAIL)) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                if ($record->required) {
                    $return->eingabe = str_replace('*', '', $eingabe);
                } else {
                    $return->eingabe = $eingabe;
                }
                if (!$return->eingabe) {
                    $return->eingabe = __('Nothing selected', 'bs-formular2');
                }

                $return->status = true;

                $return->user_value = '[' . $record->label . ' - select]';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);

                return $return;
            case 'file':
                $dir = BS_FILE_UPLOAD_DIR . $record->inputId . DIRECTORY_SEPARATOR;
                $fileArr = [];
                foreach (scandir($dir) as $file) {
                    if ($file == "." || $file == "..")
                        continue;
                    $regEx = '/.{9}(.*)$/i';
                    preg_match($regEx, $file, $matches);
                    if ($matches) {
                        $oldName = $dir . $file;
                        $newName = $dir . $matches[1];
                        if (rename($oldName, $newName)) {
                            $name = $newName;
                        } else {
                            $name = $oldName;
                        }
                        $fileArr[] = $name;
                    }
                }

                if ($record->required && !$fileArr) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                $fileArr ? $return->eingabe = $fileArr : $return->eingabe = [];
                $return->status = true;
                $return->user_value = '';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);

                return $return;

            case'url':
                $url = sanitize_text_field($input);
                if ($record->required && !$url) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                if ($input && !filter_var($url, FILTER_VALIDATE_URL)) {
                    $return->status = false;
                    $msg = apply_filters('bs_formular_message', $form_id, $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);
                $url ? $return->eingabe = '<a href="' . $url . '">' . $url . '</a>' : $return->eingabe = false;

                return $return;
            case'checkbox':
                $checked = sanitize_text_field($input);
                $return->status = true;
                $return->user_value = '[' . $record->values . ']';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);
                $return->eingabe = __('selected' ,'bs-formular2');

                return $return;
            case'radio':
                $input = sanitize_text_field($input);
                $select = unserialize($record->values);
                $eingabe = '';
                foreach ($select as $tmp) {
                    if ($tmp['id'] == $input) {
                        $eingabe = $tmp['bezeichnung'];
                        break;
                    } else {
                        $eingabe = false;
                    }
                }
                $return->eingabe = str_replace('*', '', $eingabe);
                $return->status = true;
                $return->user_value = '[' . $record->label . ' - radio]';
                $return->inputId = $record->inputId;
                $return->type = $record->type;
                $return->label = sanitize_text_field($record->label);

                return $return;
        }

        return (object)[];
    }

    /**
     * @param $post
     * @param $inputArr
     * @param $type
     * @return object
     */
    public function validateFormularRadioCheckbox($post, $inputArr, $type): object
    {
        $return = new stdClass();
        switch ($type) {
            case 'checkbox':
                $postArr = array_keys($post);
                if (in_array($inputArr->inputId, $postArr)) {
                    $return->is_check = true;

                    return $return;
                }
                $return->is_check = false;
                if ($inputArr->required == 'required') {
                    $return->status = false;
                    $msg = apply_filters('bs_form_default_settings', 'by_field', $type);
                    $return->msg = $msg->$type;

                    return $return;
                }
                $return->user_value = '[' . $inputArr->values . ']';
                $return->inputId = $inputArr->inputId;
                $return->type = $inputArr->type;
                $return->status = true;
                $return->label = sanitize_text_field($inputArr->label);
                $return->eingabe = __('Nothing selected', 'bs-formular2');

                return $return;
            case'radio':
                $postArr = array_keys($post);
                if (in_array($inputArr->inputId, $postArr)) {
                    $return->is_check = true;

                    return $return;
                }

                $return->is_check = false;
                if ($inputArr->required == 'required') {
                    $return->status = false;
                    $msg = apply_filters('bs_form_default_settings', 'by_field', $type);
                    $return->msg = $msg->$type;

                    return $return;
                }

                $label = sanitize_text_field($inputArr->label);
                $return->user_value = '[' . $label . ' - radio]';
                $return->inputId = $inputArr->inputId;
                $return->type = $inputArr->type;
                $return->status = true;
                $return->label = $label;
                $return->eingabe = __('Nothing selected', 'bs-formular2');

                return $return;
        }

        return (object)[];
    }


}