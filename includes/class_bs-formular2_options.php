<?php

namespace BS\Formular2;
use stdClass;

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
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $basename    The ID of this plugin.
     */
    private string $basename;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private string $version;

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
    public function __construct(string $basename, string $version) {
        $this->default = $this->get_theme_default_settings('');
        $this->basename = $basename;
        $this->version = $version;
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
        $options = get_option($this->basename . '-get-options');
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

            'file_max_size' => $emailDef->file_max_size,
            'file_max_all_size' => $emailDef->file_max_all_size,
            'upload_max_files' => $emailDef->upload_max_files,
            'upload_mime_types' => $emailDef->upload_mime_types,
            'multi_upload' => $emailDef->multi_upload
        ];
        $options = wp_parse_args($options, $defaults);
        update_option($this->basename . '-get-options', $options);
    }


    /**
     * @param $args
     * @param int|null $id
     * @return stdClass
     */
    final public function get_bs_form2_default_settings($args, ?int $id = 0): stdClass
    {

        $defaults = new stdClass();
        $defaults->status = true;
        $meldungen = $this->get_theme_default_settings('default_formular_messages');
        switch ($args) {
            case 'set':
                $dbMeldungen = apply_filters('bs_form_get_settings_by_select', 'form_meldungen');
                if (!$dbMeldungen->status) {
                    apply_filters('set_formular2_settings','form_meldungen', json_encode($meldungen));
                   // $this->setDefaultSettings('form_meldungen', json_encode($meldungen));
                }

                return $defaults;
            case'by_id':
                foreach ($meldungen as $tmp) {
                    if ($id == $tmp['id']) {
                        return (object)$tmp;
                    }
                }
                break;
            case 'by_field':
                $msg = [];
                foreach ($meldungen as $tmp) {
                    if ($id == $tmp['format']) {
                        $msg[$id] = $tmp['msg'];
                        break;
                    }
                }
                if (!$msg) {
                    $msg[$id] = $meldungen[5]['msg'];
                }

                return (object)$msg;
            default:
                $defaults->meldungen = json_encode($meldungen);

                return $defaults;
        }

        return (object)[];

    }

    /**
     * @param null $args
     * @param null $id
     * @return object
     */
    public function bsFormSelectEmailTemplate($args = null, $id = null): object
    {
        $return = [];
        $select = [
            '0' => [
                'id' => 1,
                'bezeichnung' => 'Tabelle'
            ],
            '1' => [
                'id' => 2,
                'bezeichnung' => 'individuell'
            ]
        ];

        switch ($args) {
            case 'all':
                $return = $select;
                break;
            case'by_id':
                foreach ($select as $tmp) {
                    if ($tmp['id'] == $id) {
                        $return = $tmp;
                        break;
                    }
                }
                break;
        }

        return apply_filters('bs_array_to_object', $return);
    }


}