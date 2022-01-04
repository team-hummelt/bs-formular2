<?php

namespace BSFormular2\License;

defined('ABSPATH') or die();

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class Hupa_License_Register
{
    private static $instance;


    /**
     * The current Plugin Basename.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $basename The Plugin Basename.
     */
    protected string $basename;

    /**
     * The current version off the Plugin-Version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version off the Plugin-Version.
     */
    protected string $version;

    /**
     * @param string $basename
     * @param string $version
     * @return static
     */
    public static function instance(string $basename, string $version): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $version);
        }
        return self::$instance;
    }

    /**
     * @param string $basename
     * @param string $version
     */
    public function __construct(string $basename, string $version){
        $this->basename = $basename;
        $this->version = $version;

        if(!get_option($this->basename . '_server_api')){
            $serverApi = [
                'update_aktiv' => true,
                'update_type' =>  1,
                'update_url' => 'https://github.com/team-hummelt/'. $this->basename,
                'product_install_authorize' => '',
                'client_id' => '',
                'client_secret' => '',
                'license_message' => '',
                'access_token' => '',
                'install_time' => '',
                'license_url' => '',
            ];
            update_option($this->basename . '_server_api', $serverApi);

        }
    }

    /**
     * ==================================================
     * =========== REGISTER PLUGIN ADMIN MENU ===========
     * ==================================================
     */

    public function register_hupa_license_menu(): void
    {
        $hook_suffix = add_menu_page(
            __('BS-Formular2', 'bs-formular2'),
            __('BS-Formular2', 'bs-formular2'),
            'manage_options',
            $this->basename . '-license',
            array($this, 'hupa_license_page'),
            'dashicons-lock', 2
        );
        add_action('load-' . $hook_suffix, array($this, 'hupa_license_load_ajax_admin_options_script'));
    }


    public function hupa_license_page(): void
    {
        require 'activate-hupa-license-page.php';
    }


    /**
     * =========================================
     * =========== ADMIN AJAX HANDLE ===========
     * =========================================
     */

    public function hupa_license_load_ajax_admin_options_script(): void
    {
        $baseName = str_replace(['-', ' '], '_', $this->basename);
        add_action('admin_enqueue_scripts', array($this, 'load_hupa_license_admin_style'));
        $title_nonce = wp_create_nonce($baseName . '_license_handle');
        wp_register_script($baseName . '-selector-ajax-script', '', [], '', true);
        wp_enqueue_script($baseName . '-selector-ajax-script');
        wp_localize_script($baseName . '-selector-ajax-script', $baseName . '_license_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce,
            'base'=> $baseName
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_BsFormular2LicenceHandle(): void {
        $baseName = str_replace(['-', ' '], '_', $this->basename);
        $responseJson = null;
        check_ajax_referer( $baseName. '_license_handle' );
        require 'hupa-license-ajax.php';
        wp_send_json( $responseJson );
    }

    /*===============================================
       TODO GENERATE CUSTOM SITES
    =================================================
    */
    public function hupa_license_site_trigger_check(): void {
        global $wp;
        $wp->add_query_var( $this->basename );
    }

    function hupa_license_callback_trigger_check(): void {
       if ( get_query_var( $this->basename ) === $this->basename) {
            require 'api-request-page.php';
            exit;
        }
    }

    /**
     * ====================================================
     * =========== THEME ADMIN DASHBOARD STYLES ===========
     * ====================================================
     */

    public function load_hupa_license_admin_style(): void
    {
        wp_enqueue_style($this->basename . '-license-style',plugins_url('bs-formular2') . '/includes/license/assets/license-backend.css', array(), $this->version);
        wp_enqueue_script($this->basename . '-license-script', plugins_url('bs-formular2') . '/includes/license/license-script.js', array(), $this->version, true );
    }
}
