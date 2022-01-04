<?php

namespace BS\Formular2;

defined('ABSPATH') or die();

/**
 * ADMIN Settings TRAIT
 * @package Hummelt & Partner WordPress-Plugin
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 *
 * @Since 1.0.0
 */
trait BS_Formular2_Defaults_Trait
{

    //DATABASE TABLES
    protected string $table_formulare2 = 'bs_formulare2';
    protected string $table_formular2_message = 'bs_formular2_message';
    protected string $table_formular2_settings = 'bs_formular2_settings';
    protected string $table_formular2_post_eingang = 'bs_formular2_post_eingang';

    //SETTINGS DEFAULT OBJECT
    protected array $bs_formular2_default_values;

    //E-Mail Default Options
    protected int $email_empfang_aktiv = 1;
    protected string $email_abs_name = '';
    protected string $bs_abs_email = '';
    protected string $bs_form_smtp_host = '';
    protected int $bs_form_smtp_port = 587;
    protected string $bs_form_smtp_secure = 'tls';
    protected string $bs_form_email_benutzer = '';
    protected string $bs_form_email_passwort = '';
    protected int $bs_form_smtp_auth_check = 1;

    //Upload Options
    protected int $file_max_size = 2;
    protected int $file_max_all_size = 5;
    protected int $upload_max_files = 5;
    protected string $upload_mime_types = 'pdf';
    protected int $multi_upload = 0;

    /**
     * @param string $args
     * @return array|object
     */
    protected function get_theme_default_settings(string $args = ''): array|object
    {

        $this->bs_formular2_default_values = [

            // JOB E-MAIL DEFAULT SMTP AND Upload Settings
            'email_settings' => [
                'email_empfang_aktiv' => $this->email_empfang_aktiv,
                'email_abs_name' => $this->email_abs_name,
                'bs_abs_email' => $this->bs_abs_email,
                'bs_form_smtp_host' => $this->bs_form_smtp_host,
                'bs_form_smtp_port' => $this->bs_form_smtp_port,
                'bs_form_smtp_secure' => $this->bs_form_smtp_secure,
                'bs_form_email_benutzer' => $this->bs_form_email_benutzer,
                'bs_form_email_passwort' => $this->bs_form_email_passwort,
                'bs_form_smtp_auth_check' => $this->bs_form_smtp_auth_check,

                'file_max_size' => $this->file_max_size,
                'file_max_all_size' => $this->file_max_all_size,
                'upload_max_files' => $this->upload_max_files,
                'upload_mime_types' => $this->upload_mime_types,
                'multi_upload' => $this->multi_upload
            ],

            //JOB File-Upload AJAX DE Language
            'file_upload_language' => [
                //Datei auswählen
                'datei_select' => __('Select file', 'bs-formular2'),
                //Datei hier per Drag & Drop ablegen.
                'drag_file' => __('Drag and drop the file here.', 'bs-formular2'),
                //Fehler beim Upload
                'upload_err' => __('Upload error', 'bs-formular2'),
                //erneut versuchen
                'erneut_vers' => __('Try again', 'bs-formular2'),
                //zum Abbrechen antippen
                'tap_cancel' => __('Tap to cancel', 'bs-formular2'),
                //zum Löschen klicken
                'click_delete' => __('Click to delete', 'bs-formular2'),
                //entfernen
                'remove' => __('remove', 'bs-formular2'),

                //Datei ist zu groß
                'file_large' => __('File is too large', 'bs-formular2'),
                //Maximale Dateigröße ist {filesize}
                'max_filesize' => __('Maximum file size is {filesize}', 'bs-formular2'),
                //Maximale Gesamtgröße überschritten
                'max_total_size' => __('Maximum total size exceeded', 'bs-formular2'),
                //Maximale Gesamtgröße der Datei ist {filesize}
                'max_total_file' => __('Maximum total size of the file is {filesize}', 'bs-formular2'),
                //Ungültiger Dateityp
                'invalid_type' => __('Invalid file type', 'bs-formular2'),
                //Erwartet {allButLastType} oder {lastType}
                'expects' => __('Expects {allButLastType} or {lastType}', 'bs-formular2')
            ],
            // JOB Default Form Messages
            'default_formular_messages' => [
                '0' => [
                    'id' => 1,
                    'type' => 'success_message',
                    'format' => 'success_message',
                    'label' => 'Die Nachricht des Absenders wurde erfolgreich gesendet',
                    'msg' => 'Die Nachricht wurde erfolgreich gesendet.'
                ],
                '1' => [
                    'id' => 2,
                    'format' => 'error_message',
                    'type' => 'senden_error',
                    'label' => 'Die Nachricht des Absenders konnte nicht gesendet werden',
                    'msg' => 'Beim Versuch, Ihre Nachricht zu senden, ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.'
                ],
                '2' => [
                    'id' => 3,
                    'format' => 'form-message',
                    'type' => 'form_required_fehler',
                    'label' => 'Fehler beim Ausfüllen des Formulars',
                    'msg' => 'Ein oder mehrere Felder haben einen Fehler. Bitte überprüfen Sie es und versuchen Sie es erneut.'
                ],
                '3' => [
                    'id' => 4,
                    'format' => 'spam',
                    'type' => 'mail_spam',
                    'label' => 'Eingabe wurde als Spam erkannt',
                    'msg' => 'Beim Versuch, Ihre Nachricht zu senden, ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.'
                ],
                '4' => [
                    'id' => 5,
                    'format' => 'dataprotection',
                    'type' => 'akzept_check',
                    'label' => 'Es gibt Bedingungen, die der Absender akzeptieren muss',
                    'msg' => 'Sie müssen die Bedingungen akzeptieren, bevor Sie Ihre Nachricht senden.'
                ],
                '5' => [
                    'id' => 6,
                    'format' => 'required',
                    'type' => 'input_required_fehler',
                    'label' => 'Es gibt ein Feld, das der Absender ausfüllen muss',
                    'msg' => 'Dieses Feld muss ausgefüllt werden.'
                ],
                '6' => [
                    'id' => 7,
                    'format' => 'email',
                    'type' => 'email_format_error',
                    'label' => 'Die eingegebene E-Mail-Adresse des Absenders ist ungültig',
                    'msg' => 'Die eingegebene E-Mail-Adresse ist ungültig.'
                ],
                '7' => [
                    'id' => 8,
                    'format' => 'url',
                    'type' => 'url_format_error',
                    'label' => 'Die eingegebene URL des Absenders ist ungültig',
                    'msg' => 'Die URL ist unzulässig.'
                ],
                '8' => [
                    'id' => 9,
                    'format' => 'date',
                    'type' => 'date_format_error',
                    'label' => 'Das eingegebene Datumsformat ist ungültig',
                    'msg' => 'Das Datumsformat ist falsch.'
                ],
                '9' => [
                    'id' => 10,
                    'format' => 'number',
                    'type' => 'number_format_error',
                    'label' => 'Die eingegebene Zahlenformat ist ungültig',
                    'msg' => 'Das Zahlenformat ist ungültig.'
                ],
                '10' => [
                    'id' => 11,
                    'format' => 'select',
                    'type' => 'select_format_error',
                    'label' => 'Ein Feld aus einer Auswahlliste muss ausgewählt werden.',
                    'msg' => 'Es muss ein Feld ausgewählt werden.'
                ],
                '11' => [
                    'id' => 12,
                    'format' => 'checkbox',
                    'type' => 'checkbox_format_error',
                    'label' => 'Eine Checkbox muss ausgewählt sein.',
                    'msg' => 'Sie müssen dieser Bedingung zustimmen.'
                ],
                '12' => [
                    'id' => 13,
                    'format' => 'email-send-select',
                    'type' => 'email_select_format_error',
                    'label' => 'Eine E-Mail (E-Mail Select) muss aus einer Auswahlliste ausgewählt werden.',
                    'msg' => 'Die ausgewählte E-Mail-Adresse ist ungültig.'
                ],
                '13' => [
                    'id' => 14,
                    'format' => 'file',
                    'type' => 'file_upload_format_error',
                    'label' => 'Ein Dateianhang (File-Upload) muss ausgewählt sein.',
                    'msg' => 'Die ausgewählte Datei ist ungültig.'
                ]
            ]
        ];

        if ($args) {
            return $this->bs_formular2_default_values[$args];
        } else {
            return $this->bs_formular2_default_values;
        }
    }
}