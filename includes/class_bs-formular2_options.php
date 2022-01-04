<?php

namespace BS\Formular2;
defined('ABSPATH') or die();

/**
 * Define the BS-Formular2 functionality
 *
 * Loads and defines get_options
 * For the PHP-Mailer and Options.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */
class BS_Formular2_Options
{

    private static $instance;

    /**
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * The Default Settings.
     *
     * @since    1.0.0
     * @access   private
     * @var      array|object $default The current version of the database Version.
     */
     private $default;

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

    public function __construct() {
        $this->default = $this->get_theme_default_settings('');
    }

    /**
     * Load the plugin BS-Formular2 Default Options.
     *
     * @since    1.0.0
     */
    public function bs_formular2_set_default_options()
    {
        global $bs_formular2_helper;
        $emailDef = $bs_formular2_helper->bsFormular2ArrayToObject($this->default['email_settings']);
        $options = get_option('bs_formular2_options');
        $defaults = [
            'email_empfang_aktiv' => $emailDef->email_empfang_aktiv,
            'email_abs_name' => $emailDef->email_abs_name,
            'bs_abs_email' => $emailDef->bs_abs_email,
            'bs_form_smtp_host' => $emailDef->bs_form_smtp_host,
            'bs_form_smtp_auth_check' => $emailDef->bs_form_smtp_auth_check,
            'bs_form_smtp_port' => $emailDef->bs_form_smtp_port,
            'bs_form_email_benutzer' => $emailDef->bs_form_email_benutzer,
            'bs_form_email_passwort' => $emailDef->bs_form_email_passwort,
            'bs_form_smtp_secure' => $emailDef->bs_form_smtp_secure,
        ];
        $options = wp_parse_args($options, $defaults);
        return apply_filters('bs-formular2/get_options', $options);
    }

    /**
     * @param string $method
     * @param string $settings
     * @param int|null $id
     */
    final public function func_get_bs_form2_default_settings(string $method, string $settings, int $id = NULL) {

        $defaultSettings = $this->get_theme_default_settings();
        switch ($method) {
            case 'set':
                switch ($settings) {
                    case 'message':


                        break;
                }
                break;
        }
    }
}