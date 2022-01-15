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
    protected string $table_formular2_extensions = 'bs_formular2_extensions';
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
     * @return array
     */
    protected function get_theme_default_settings(string $args = ''): array
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
            'meldungen_site_language' => [
                //Formular Meldungen
                '1' => __('Form messages', 'bs-formular2'),
                //zurück zum Formular
                '2' => __('back to the form', 'bs-formular2'),
                //E-Mail
                '3' => __('E-mail', 'bs-formular2'),
                //Meldungen
                '4' => __('Messages', 'bs-formular2'),
                //Formular bearbeiten
                '5' => __('Edit form', 'bs-formular2'),
                //erstellt am
                '6' => __('created on', 'bs-formular2'),
                //um
                '7' => __('at', 'bs-formular2'),
                //Shortcode:
                '8' => __('Shortcode:', 'bs-formular2'),
                //Hier können Meldungen bearbeitet werden, die in verschiedenen Situationen verwendet werden.
                '9' => __('Messages that are used in various situations can be edited here.', 'bs-formular2'),
                //Änderungen speichern
                '10' => __('Save changes', 'bs-formular2'),

                //Formular
                '11' => __('Form', 'bs-formular2'),
                //bearbeiten
                '12' => __('edit', 'bs-formular2'),
                //erstellen
                '13' => __('create', 'bs-formular2'),
                //zurück zur Übersicht
                '14' => __('back to the overview', 'bs-formular2'),
                //Formularbezeichnung:
                '15' => __('Form description:', 'bs-formular2'),
                //passwort
                '16' => __('password', 'bs-formular2'),
                //Datenschutz prüfen
                '17' => __('Check data protection', 'bs-formular2'),
                //Extra CSS Settings
                '18' => __('Extra CSS Settings', 'bs-formular2'),
                //Button Settings
                '19' => __('Button Settings', 'bs-formular2'),
                //Formular Settings
                '20' => __('Form Settings', 'bs-formular2'),
                //CSS Klassen für Formular und Input Elemente hinzufügen?
                '21' => __('Add CSS classes for form and input elements?', 'bs-formular2'),
                //HTML Elemente und CSS Klassen können auch im Eingabefeld individuell hinzugefügt werden.
                '22' => __('HTML elements and CSS classes can also be added individually in the input field.', 'bs-formular2'),
                //Label ausblenden
                '23' => __('Hide label', 'bs-formular2'),
                //Ist Label ausblenden aktiv, wird die Bezeichnung für Input Felder als Platzhalter angezeigt.
                '24' => __('If Hide Label is active, the label for input fields is displayed as a placeholder.', 'bs-formular2'),
                //Formular Wrapper Klasse:
                '25' => __('Form wrapper class:', 'bs-formular2'),
                //z.B. für ein Responsive 2 spaltiges Layout.
                '26' => __('e.g. for a Responsive 2-column layout.', 'bs-formular2'),
                //DIV Klasse für Input Elemente:
                '27' => __('DIV class for input elements:', 'bs-formular2'),
                //z.B.
                '28' => __('e.g.', 'bs-formular2'),
                //DIV Klasse für Label:
                '29' => __('DIV class for label:', 'bs-formular2'),
                //Die CSS Klassen für Labels werden <span class="text-danger strong-font-weight">nicht</span> bei Radio oder Checkboxen hinzugefügt.
                '30' => __('The CSS classes for labels are <span class="text-danger strong-font-weight">not</span> added for radio or checkboxes.', 'bs-formular2'),
                //CSS Klassen für Button hinzufügen?
                '31' => __('Add CSS classes for button?', 'bs-formular2'),
                //Die Klasse (<code><b class="strong-font-weight">btn</b></code>) wird automatisch hinzugefügt.
                '32' => __('The class (<code><b class="strong-font-weight">btn</b></code>)  will be added automatically.', 'bs-formular2'),
                //Button CSS Klassen:
                '33' => __('Button CSS classes:', 'bs-formular2'),
                //Icon hinzufügen
                '34' => __('Add icon', 'bs-formular2'),
                //Icon löschen
                '35' => __('Delete icon', 'bs-formular2'),
                //Weiterleitung nach dem Senden des Formulars
                '36' => __('Forwarding after sending the form', 'bs-formular2'),
                //Redirection aktiv
                '37' => __('Redirection active', 'bs-formular2'),
                //Redirect Page
                '38' => __('Redirect page', 'bs-formular2'),
                //auswählen...
                '39' => __('select...', 'bs-formular2'),
                //Formulardaten an Seite übergeben
                '40' => __('Transfer form data to page', 'bs-formular2'),
                //Es können Daten an die Redirect-Page übergeben werden. <span class="text-danger"> Passwörter und Uploads werden nicht weitergeleitet.</span> <p>Die Daten stehen unter dem Javascript Object <code>bs_form_ajax_obj.bs_form_redirect_data['shortcode']</code> zur Verfügung.</p> Ein Bespiel für die Ausgabe eines <i class="text-danger">"redirect_data Objekts"</i> ist unter " <i class="fa fa-life-ring"></i> Hilfe " zu finden.
                '41' => __('Data can be transferred to the redirect page. <span class="text-danger"> Passwords and uploads are not redirected.</span> <p>The data is under the Javascript Object  <code>bs_form_ajax_obj.bs_form_redirect_data[\'shortcode\']</code> available.</p> An example of the output of a <i class="text-danger">"redirect_data object"</i> is shown in " <i class="fa fa-life-ring"></i> Help " to find.', 'bs-formular2'),
                //Formular erstellen
                '42' => __('Create form', 'bs-formular2'),
                //Reset
                '43' => __('Reset', 'bs-formular2'),
                //Bei aktiver Weiterleitung muss eine Seite ausgewählt werden, die nach dem Absenden aufgerufen werden soll.
                '44' => __('If forwarding is active, a page must be selected that is to be called up after sending.', 'bs-formular2'),
                //Nachrichten Settings
                '45' => __('News Settings', 'bs-formular2'),
                //E-Mail senden an:
                '46' => __('Send e-mail to:', 'bs-formular2'),
                //Mehrere Empfänger mit Komma oder Semikolon trennen.
                '47' => __('Separate multiple recipients with a comma or semicolon.', 'bs-formular2'),
                //Betreff:
                '48' => __('Subject:', 'bs-formular2'),
                //Verfügbare Formular Platzhalter
                '49' => __('Available form placeholders', 'bs-formular2'),
                //Nachricht
                '50' => __('Message', 'bs-formular2'),
                //Speichern
                '51' => __('Save', 'bs-formular2'),
                //Automatische Antwort
                '52' => __('Automatic reply', 'bs-formular2'),
                //anzeigen
                '53' => __('show', 'bs-formular2'),
                //Auto-Responder
                '54' => __('Auto-responder', 'bs-formular2'),
                //aktiv
                '55' => __('Active', 'bs-formular2'),
                //nicht aktiviert
                '56' => __('Not activated', 'bs-formular2'),
                //Auto-Responder speichern
                '57' => __('Save auto-responder', 'bs-formular2'),
                //Empfangen
                '58' => __('Received', 'bs-formular2'),
                //Empfänger
                '59' => __('Recipient', 'bs-formular2'),
                //Absender IP
                '60' => __('Sender IP', 'bs-formular2'),
                //Alle E-Mail löschen
                '61' => __('Delete all e-mail', 'bs-formular2'),
                //E-Mail Template
                '62' => __('E-mail template', 'bs-formular2'),
                //Received messages
                '63' => __('Received messages', 'bs-formular2'),
            ],
            // JOB Default Form Messages
            'default_formular_messages' => [
                '0' => [
                    'id' => 1,
                    'type' => 'success_message',
                    'format' => 'success_message',
                    //'Die Nachricht des Absenders wurde erfolgreich gesendet'
                    'label' => __('The sender\'s message was sent successfully', 'bs-formular2'),
                    //Die Nachricht wurde erfolgreich gesendet.
                    'msg' => __('The message was sent successfully.', 'bs-formular2'),
                ],
                '1' => [
                    'id' => 2,
                    'format' => 'error_message',
                    'type' => 'senden_error',
                    //Die Nachricht des Absenders konnte nicht gesendet werden
                    'label' => __('The sender\'s message could not be sent', 'bs-formular2'),
                    //Beim Versuch, Ihre Nachricht zu senden, ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.
                    'msg' => __('An error occurred while trying to send your message. Please try again later.', 'bs-formular2'),
                ],
                '2' => [
                    'id' => 3,
                    'format' => 'form-message',
                    'type' => 'form_required_fehler',
                    //Fehler beim Ausfüllen des Formulars
                    'label' => __('Error filling out the form', 'bs-formular2'),
                    //Ein oder mehrere Felder haben einen Fehler. Bitte überprüfen Sie es und versuchen Sie es erneut.
                    'msg' => __('One or more fields have an error. Please check and try again.', 'bs-formular2'),
                ],
                '3' => [
                    'id' => 4,
                    'format' => 'spam',
                    'type' => 'mail_spam',
                    //Eingabe wurde als Spam erkannt
                    'label' => __('Input was detected as spam', 'bs-formular2'),
                    //Beim Versuch, Ihre Nachricht zu senden, ist ein Fehler aufgetreten. Bitte versuchen Sie es später noch einmal.
                    'msg' => __('An error occurred while trying to send your message. Please try again later.', 'bs-formular2'),
                ],
                '4' => [
                    'id' => 5,
                    'format' => 'dataprotection',
                    'type' => 'akzept_check',
                    //Es gibt Bedingungen, die der Absender akzeptieren muss
                    'label' => __('There are conditions that the sender must accept', 'bs-formular2'),
                    //Sie müssen die Bedingungen akzeptieren, bevor Sie Ihre Nachricht senden.
                    'msg' => __('You must accept the conditions before sending your message.', 'bs-formular2'),
                ],
                '5' => [
                    'id' => 6,
                    'format' => 'required',
                    'type' => 'input_required_fehler',
                    //Es gibt ein Feld, das der Absender ausfüllen muss
                    'label' => __('There is a field that the sender must fill in.', 'bs-formular2'),
                    //Dieses Feld muss ausgefüllt werden.
                    'msg' =>  __('This field must be filled in.', 'bs-formular2'),
                ],
                '6' => [
                    'id' => 7,
                    'format' => 'email',
                    'type' => 'email_format_error',
                    //Die eingegebene E-Mail-Adresse des Absenders ist ungültig
                    'label' => __('The sender\'s email address entered is invalid', 'bs-formular2'),
                    //Die eingegebene E-Mail-Adresse ist ungültig.
                    'msg' => __('The e-mail address entered is invalid.', 'bs-formular2'),
                ],
                '7' => [
                    'id' => 8,
                    'format' => 'url',
                    'type' => 'url_format_error',
                    //Die eingegebene URL des Absenders ist ungültig
                    'label' => __('The sender\'s URL entered is invalid', 'bs-formular2'),
                    //Die URL ist unzulässig.
                    'msg' => __('The URL is invalid.', 'bs-formular2'),
                ],
                '8' => [
                    'id' => 9,
                    'format' => 'date',
                    'type' => 'date_format_error',
                    //Das eingegebene Datumsformat ist ungültig
                    'label' => __('The date format entered is invalid', 'bs-formular2'),
                    //Das Datumsformat ist falsch.
                    'msg' => __('The date format is incorrect.', 'bs-formular2'),
                ],
                '9' => [
                    'id' => 10,
                    'format' => 'number',
                    'type' => 'number_format_error',
                    //Die eingegebene Zahlenformat ist ungültig
                    'label' => __('The number format entered is invalid', 'bs-formular2'),
                    //Das Zahlenformat ist ungültig.
                    'msg' => __('The number format is invalid.', 'bs-formular2'),
                ],
                '10' => [
                    'id' => 11,
                    'format' => 'select',
                    'type' => 'select_format_error',
                    //Ein Feld aus einer Auswahlliste muss ausgewählt werden.
                    'label' => __('A field from a selection list must be selected.', 'bs-formular2'),
                    //Es muss ein Feld ausgewählt werden.
                    'msg' => __('A field must be selected.', 'bs-formular2'),
                ],
                '11' => [
                    'id' => 12,
                    'format' => 'checkbox',
                    'type' => 'checkbox_format_error',
                    //Eine Checkbox muss ausgewählt sein.
                    'label' => __('A checkbox must be selected.', 'bs-formular2'),
                    //Sie müssen dieser Bedingung zustimmen.
                    'msg' =>  __('You must agree to this condition.', 'bs-formular2'),
                ],
                '12' => [
                    'id' => 13,
                    'format' => 'email-send-select',
                    'type' => 'email_select_format_error',
                    //Eine E-Mail (E-Mail Select) muss aus einer Auswahlliste ausgewählt werden.'
                    'label' => __('An email (Email Select) must be selected from a drop-down list.', 'bs-formular2'),
                    //Die ausgewählte E-Mail-Adresse ist ungültig.
                    'msg' => __('The selected e-mail address is invalid.', 'bs-formular2'),
                ],
                '13' => [
                    'id' => 14,
                    'format' => 'file',
                    'type' => 'file_upload_format_error',
                    //Ein Dateianhang (File-Upload) muss ausgewählt sein.
                    'label' => __('A file attachment (File Upload) must be selected.', 'bs-formular2'),
                    //Die ausgewählte Datei ist ungültig.
                    'msg' => __('The selected file is invalid.', 'bs-formular2'),
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