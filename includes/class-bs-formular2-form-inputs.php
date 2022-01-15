<?php

namespace BS\Formular2;
use Exception;
use stdClass;

defined('ABSPATH') or die();

/**
 * Define the BS-Formular2 functionality
 *
 * FORMULAR Input Create And Validate
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */
class BS_Formular2_Form_Inputs
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
     * @var      string    $plugin_dir  plugin Path.
     */
    protected string $plugin_dir;

    /**
     * The plugin Settings ID.
     *
     * @since    1.0.0
     * @access   protected
     * @var      int    $settings_id  Settings ID.
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
     * @param object $create
     * @return object
     */
    public function bs_form_create_formular_fields(object $create): object
    {
        global $bs_formular2_helper;
        $record = new stdClass();
        $record->status = true;
        $html = '';
        $id = $bs_formular2_helper->bs_formular2_load_random_string();
        $id = substr($id, 0, 12);
        if ($create->input_class) {
            $inputStart = '<div class="' . $create->input_class . '">';
            $inputEnd = '</div>';
        } else {
            $inputStart = '';
            $inputEnd = '';
        }

        switch ($create->case) {
            case'select':
                if (strpos($create->type, '*')) {
                    $stern = '<span class="text-danger"> *</span>';
                    $require = 'required';
                    $field = trim(str_replace('*', '', $create->type));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback">' . $invalidMsg->$field . '</div>';
                } else {
                    $require = false;
                    $invDiv = '';
                    $stern = '';
                }

                $valArr = array();
                $html = $inputStart;
                if (!$create->class_aktiv) {
                    $html .= '<label class="form-label ' . $create->label_class . '" for="' . $id . '">' . $create->label . ' ' . $stern . '</label>';
                }

                $html .= '<select onchange="this.blur()" name="' . $id . '" class="form-control" id="' . $id . '" ' . $require . '>';
                $html .= '<option value="">' . __('select', 'bs-formular2') . '...</option>';
                foreach ($create->values as $tmp) {
                    $random = $bs_formular2_helper->bs_formular2_load_random_string();
                    $random = substr($random, 0, 12);
                    $valItem = array(
                        "id" => $random,
                        "bezeichnung" => $tmp
                    );
                    $valArr[] = $valItem;
                    if (strpos($tmp, '*')) {
                        $sel = 'selected';
                        $tmp = str_replace('*', '', $tmp);
                    } else {
                        $sel = '';
                    }
                    $html .= '<option value="' . $random . '" ' . $sel . '> ' . $tmp . '</option>';
                }
                $html .= '</select>' . $invDiv . $inputEnd;
                $record->html = esc_textarea($html);
                $record->values = serialize($valArr);
                $record->inputId = $id;
                $record->label = $id;
                $record->type = $create->case;
                $record->label = $create->label;
                $record->required = $require;

                return $record;

            case'email-send-select':
                if (strpos($create->type, '*')) {
                    $stern = '<span class="text-danger"> *</span>';
                    $require = 'required';
                    $field = trim(str_replace('*', '', $create->type));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback">' . $invalidMsg->$field . '</div>';
                } else {
                    $require = false;
                    $invDiv = '';
                    $stern = '';
                }

                $valArr = array();
                $html = $inputStart;
                if (!$create->class_aktiv) {
                    $html .= '<label class="form-label ' . $create->label_class . '" for="' . $id . '">' . $create->label . ' ' . $stern . '</label>';
                }

                $html .= '<select onchange="this.blur()" name="' . $id . '" class="form-control email-send-select" id="' . $id . '" ' . $require . '>';
                $html .= '<option value="">' . __('select', 'bs-formular2') . '...</option>';
                foreach ($create->values as $tmp) {

                    $random = $bs_formular2_helper->bs_formular2_load_random_string();
                    $random = substr($random, 0, 12);
                    $valItem = array(
                        "id" => $random,
                        "bezeichnung" => $tmp
                    );

                    $tmp = trim($tmp);
                    $valArr[] = $valItem;
                    if (strpos($tmp, '*')) {
                        $sel = 'selected';
                        $tmp = str_replace('*', '', $tmp);
                    } else {
                        $sel = '';
                    }

                    $regEx = '@#(.+)#@i';
                    preg_match($regEx, $tmp, $matches);
                    if ($matches) {
                        $sendData = [
                            'status' => true,
                            'id' => $random,
                            'email' => $matches[1]
                        ];

                    } else {
                        $sendData = [
                            'status' => false
                        ];
                    }

                    $value = base64_encode(json_encode($sendData));
                    $tmp = str_replace($matches[0], '', $tmp);
                    $html .= '<option value="' . $value . '" ' . $sel . '> ' . $tmp . '</option>';
                }
                $html .= '</select>' . $invDiv . $inputEnd;
                $record->html = esc_textarea($html);
                $record->values = serialize($valArr);
                $record->inputId = $id;
                $record->label = $id;
                $record->type = $create->case;
                $record->label = $create->label;
                $record->required = $require;

                return $record;

            case'radio-inline':
            case'radio-default':
                $valArr = array();
                $inpType = substr($create->type, 0, strpos($create->type, '-'));
                $format = substr($create->type, strpos($create->type, '-') + 1);
                $html = '';
                foreach ($create->values as $tmp) {
                    $random = $bs_formular2_helper->bs_formular2_load_random_string();
                    $random = substr($random, 0, 12);
                    $valItem = array(
                        "id" => $random,
                        "bezeichnung" => $tmp
                    );
                    $valArr[] = $valItem;
                    $record->required = false;
                    if (strpos($tmp, '*')) {
                        $check = 'checked';
                        $record->required = $id;
                        $tmp = str_replace('*', '', $tmp);
                    } else {
                        $check = false;
                    }

                    $format == 'default' ? $formType = '' : $formType = 'form-check-inline';
                    $html .= $inputStart;
                    $html .= '<div class="form-check ' . $formType . '">';
                    $html .= '<input onclick="this.blur()" class="form-check-input" type="radio" name="' . $id . '" id="' . $random . '" value="' . $random . '" ' . $check . '>';
                    $html .= '<label class="form-check-label" for="' . $random . '">';
                    $html .= $tmp;
                    $html .= '</label>';
                    $html .= '</div>';
                    $html .= $inputEnd;
                }
                $record->html = esc_textarea($html);
                $record->values = serialize($valArr);
                $record->inputId = $id;
                $record->label = $create->label;
                $record->type = $inpType;

                return $record;

            case'text':
            case'email':
            case'url':
            case'number':
            case'date':
            case'password':
                if (strpos($create->type, '*')) {
                    $require = 'required';
                    $stern = '<span class="text-danger"> *</span>';
                    $field = trim(str_replace('*', '', $create->type));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback">' . $invalidMsg->$field . '</div>';
                } else {
                    $require = false;
                    $stern = '';
                    $invDiv = '';
                }

                $create->case == 'password' ? $autocomplete = 'autocomplete="cc-number"' : $autocomplete = '';
                $html .= $inputStart;
                if ($create->class_aktiv) {
                    $stern = strip_tags($stern);
                    $placeholder = 'placeholder="' . $create->label . ' ' . $stern . '"';
                } else {
                    $placeholder = '';
                    $html .= '<label class="form-label ' . $create->label_class . '" for="' . $id . '">' . $create->label . ' ' . $stern . '</label>';
                }
                $html .= '<input type="' . $create->case . '" class="form-control" ' . $placeholder . ' name="' . $id . '" id="' . $id . '"  ' . $require . ' ' . $autocomplete . '/>' . $invDiv;
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->required = $require;
                $record->label = $create->label;
                $record->values = $create->values;
                $record->type = $create->case;

                return $record;
            case 'file':
                if (strpos($create->type, '*')) {
                    $require = 'required';
                    $stern = '<span class="text-danger"> *</span>';
                    $field = trim(str_replace('*', '', $create->type));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback mt-n2 mb-2">' . $invalidMsg->$field . '</div>';
                } else {
                    $require = false;
                    $stern = '';
                    $invDiv = '';
                }

                $mimeTypes = '';
                $regEx = '@#(.+?)#@i';
                $label = '';
                preg_match($regEx, $create->label, $matches);
                if ($matches) {
                    $types = preg_replace("/\s+/", "", $matches[1]);
                    $label = str_replace($matches[0], '', $create->label);
                } else {
                    $types = preg_replace("/\s+/", "", get_option('upload_mime_types'));
                }
                $html .= $inputStart;
                if ($create->class_aktiv) {
                    $stern = strip_tags($stern);
                    $placeholder = 'placeholder="' . $label . ' ' . $stern . '"';
                } else {
                    $placeholder = '';
                    $html .= '<label class="form-label ' . $create->label_class . '" for="' . $id . '">' . $label . ' ' . $stern . '</label>';
                }

                $fileType = str_replace([',', ';'], '#', $types);
                $mimes = explode('#', $fileType);
                if ($mimes) {
                    $x = count($mimes);
                    for ($i = 0; $i < count($mimes); $i++) {
                        $i == $x - 1 ? $dot = '' : $dot = ',';
                        $mimeTypes .= '.' . $mimes[$i] . $dot;
                    }
                }

                get_option('multi_upload') ? $multi = ' multiple' : $multi = '';
                $html .= '<div class="filePondWrapper">';
                $html .= '<input data-id="' . $id . '" type="' . $create->case . '"class="bsFiles files' . $id . '" ' . $placeholder . ' name="' . $id . '" id="' . $id . '" accept="' . $mimeTypes . '"  ' . $require . ' ' . $multi . '/>' . $invDiv;
                $html .= '</div>';
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->required = $require;
                $record->label = $label;
                $record->values = $create->values;
                $record->type = $create->case;

                return $record;

            case'textarea':
                $rowLines = substr($create->type, strrpos($create->type, '#') + 1);

                $rowLines ? $row = 'rows="' . $rowLines . '"' : $row = '';

                if (strpos($create->type, '*')) {
                    $stern = '<span class="text-danger"> *</span>';
                    $require = 'required';
                    $field = trim(str_replace('*', '', $create->type));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback">' . $invalidMsg->$field . '</div>';
                } else {
                    $require = false;
                    $stern = '';
                    $invDiv = '';
                }

                $html .= $inputStart;
                if ($create->class_aktiv) {
                    $stern = strip_tags($stern);
                    $placeholder = 'placeholder="' . $create->label . ' ' . $stern . '"';
                } else {
                    $placeholder = '';
                    $html .= '<label class="form-label ' . $create->label_class . '" for="' . $id . '">' . $create->label . ' ' . $stern . '</label>';
                }

                $html .= '<textarea ' . $placeholder . ' name="' . $id . '" class="form-control" id="' . $id . '" ' . $row . ' ' . $require . '></textarea>' . $invDiv;
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->required = $require;
                $record->label = $create->label;
                $record->values = $create->values;
                $record->type = $create->case;

                return $record;

            case'checkbox':

                $label = '';
                if (strpos($create->label, '*')) {
                    $required = 'required';
                    $stern = '<span class="text-danger"> *</span>';
                    $label = str_replace('*', '', $create->label);
                    $field = trim(str_replace('*', '', $label));
                    $invalidMsg = apply_filters('bs_formular_message', $create->form_id, $field);
                    $invDiv = '<div class="invalid-feedback">' . $invalidMsg->$field . '</div>';
                } else {
                    $required = false;
                    $stern = '';
                    $invDiv = '';
                }
                if (strpos($create->type, '*')) {
                    $checked = 'checked';
                } else {
                    $checked = false;
                }

                $html = $inputStart;
                $html .= '<div class="form-check">';
                $html .= '<input onclick="this.blur()" class="form-check-input" name="' . $id . '" type="checkbox" id="' . $id . '" ' . $checked . ' ' . $required . '>';
                $html .= '<label class="form-check-label" for="' . $id . '">';
                $html .= $label;
                $html .= $stern . '</label>';
                $html .= $invDiv;
                $html .= '</div>';
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->values = $create->values;
                $record->checked = $checked;
                $record->required = $required;
                $record->label = $label;
                $record->type = $create->case;

                return $record;

            case'button':
                $create->button_class ? $btn = $create->button_class : $btn = 'btn-outline-secondary';
                $html = $inputStart;
                $html .= '<div class="bs-btn-wrapper">';
                $html .= ' <button id="' . $id . '" name="' . $id . '" type="' . $create->values . '" class="btn ' . $btn . '">' . $create->faIcon . $create->label . '</button>';
                $html .= '<div class="bs-form-sending"><span class="sending-text">Daten werden gesendet </span><span class="dot-pulse"></span></div>';
                $html .= '</div>';
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->values = $create->values;
                $record->bezeichnung = $create->label;
                $record->type = $create->case;

                return $record;

            case'dataprotection':
                strpos($create->type, '*') ? $checked = 'checked' : $checked = false;
                $invalidMsg = apply_filters('bs_formular_message', $create->form_id, 'dataprotection');
                $invDiv = '<div class="invalid-feedback">' . $invalidMsg->dataprotection . '</div>';
                $regEx = '@#(.+)#@i';
                preg_match($regEx, $create->label, $matches);
                if ($matches) {
                    $labelUrl = '<a href="' . $create->values . '" target="_blank">' . $matches[1] . '</a>';
                    $dataProtectLabel = str_replace($matches[0], $labelUrl, $create->label);
                } else {
                    $dataProtectLabel = $create->label;
                }

                $html = $inputStart;
                $html .= '<div class="form-check dscheck">';
                $html .= '<input class="form-check-input" data-id="' . $id . '" name="dscheck" type="checkbox" id="' . $id . '" ' . $checked . ' required>';
                $html .= '<label class="form-check-label" for="' . $id . '">';
                $html .= $dataProtectLabel;
                $html .= '<span class="text-danger"> *</span> </label>';
                $html .= $invDiv;
                $html .= '</div>';
                $html .= $inputEnd;
                $record->html = esc_textarea($html);
                $record->inputId = $id;
                $record->url = $create->values;
                $record->label = $create->label;
                $record->checked = $checked;
                $record->type = $create->case;
                return $record;

            default:
                $record->status = false;

                return $record;
        }
    }



}