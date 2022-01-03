<?php

namespace Hupa\FormLicense;

defined('ABSPATH') or die();

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class Bs_Formular2_Register
{
    private static $instance;

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){}

    /**
     * ==================================================
     * =========== REGISTER PLUGIN ADMIN MENU ===========
     * ==================================================
     */

    public function register_license_bs_formular2_plugin(): void
    {
        $hook_suffix = add_menu_page(
            __('BS-Formular', 'bs-formular2'),
            __('BS-Formular', 'bs-formular2'),
            'manage_options',
            'bs-formular-license',
            array($this, 'bs_formular_license'),
            'dashicons-lock', 2
        );
        add_action('load-' . $hook_suffix, array($this, 'bs_formular_load_ajax_admin_options_script'));
    }


    public function bs_formular_license(): void
    {
        require 'activate-bs-formular-page.php';
    }


    /**
     * =========================================
     * =========== ADMIN AJAX HANDLE ===========
     * =========================================
     */

    public function bs_formular_load_ajax_admin_options_script(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'load_bs_formular_admin_style'));
        $title_nonce = wp_create_nonce('bs_formular_license_handle');
        wp_register_script('bs-formular-selector-ajax-script', '', [], '', true);
        wp_enqueue_script('bs-formular-selector-ajax-script');
        wp_localize_script('bs-formular-selector-ajax-script', 'bs_formulare_license_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_BsFormularLicenceHandle(): void {
        $responseJson = null;
        check_ajax_referer( 'bs_formular_license_handle' );
        require 'bs-formular-license-ajax.php';
        wp_send_json( $responseJson );
    }

    /*===============================================
       TODO GENERATE CUSTOM SITES
    =================================================
    */
    public function bs_formular_license_site_trigger_check(): void {
        global $wp;
        $wp->add_query_var( BS_FORMULAR_BASENAME );
    }

    function bs_formular_license_callback_trigger_check(): void {
       if ( get_query_var( BS_FORMULAR_BASENAME ) === BS_FORMULAR_BASENAME) {
            require 'api-request-page.php';
            exit;
        }
    }

    /**
     * ====================================================
     * =========== THEME ADMIN DASHBOARD STYLES ===========
     * ====================================================
     */

    public function load_bs_formular_admin_style(): void
    {
        wp_enqueue_style('bs-formular-license-style',plugins_url('bs-formular') . '/inc/license/assets/license-backend.css', array(), '');
        wp_enqueue_script('js-bs-formular-license', plugins_url('bs-formular') . '/inc/license/license-script.js', array(), '', true );
    }
}
