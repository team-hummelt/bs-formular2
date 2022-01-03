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
    protected string $table_formulare = 'bs_formulare';
    protected string $table_form_message = 'bs_form_message';
    protected string $table_settings = 'bs_form_settings';
    protected string $table_email = 'bs_post_eingang';

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
            ]
        ];

        if ($args) {
            return $this->bs_formular2_default_values[$args];
        } else {
            return $this->bs_formular2_default_values;
        }
    }
}