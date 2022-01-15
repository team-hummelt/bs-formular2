<?php

namespace BS\Formular2;

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


defined('ABSPATH') or die();

/**
 * Define the BS-Formular2 SMTP Test functionality
 *
 * SMTP Test
 * For the PHP-Mailer and Options.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 */
class BS_Formular2_SMTP_Test
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
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * @param string $basename
     * @return static
     */
    public static function instance(string $basename): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename);
        }
        return self::$instance;
    }

    /**
     * @param string $basename
     */
    public function __construct(string $basename)
    {
        $this->basename = $basename;
    }

    /**
     * @return array
     */
    public function bs_formular_smtp_test():array {
        $status = false;
        $msg = '';
        $smtp = new SMTP;
        $options = get_option($this->basename . '-get-options');
        try {
            //Connect to an SMTP server
            if (!$smtp->connect($options['bs_form_smtp_host'], $options['bs_form_smtp_port'])) {
                throw new Exception('Connect failed');
            }
            //Say hello
            if (!$smtp->hello(gethostname())) {
                throw new Exception('EHLO failed: ' . $smtp->getError()['error']);
            }
            $e = $smtp->getServerExtList();
            if (is_array($e) && array_key_exists('STARTTLS', $e)) {
                $tlsok = $smtp->startTLS();
                if (!$tlsok) {
                    throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
                }
                if (!$smtp->hello(gethostname())) {
                    throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
                }
                $e = $smtp->getServerExtList();
            }
            if (is_array($e) && array_key_exists('AUTH', $e)) {
                if ($smtp->authenticate($options['bs_form_email_benutzer'], $options['bs_form_email_passwort'])) {
                    $msg .= "Connected ok!";
                    $status = true;
                } else {
                    throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
                }
            }
        } catch (exception $e) {
            $msg .= 'SMTP error: ' . $e->getMessage() . "\n";
        }
        $smtp->quit(true);
        return array("status" => $status, "msg" => $msg);
    }
}