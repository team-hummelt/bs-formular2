<?php
namespace BS\BSFormular2;
defined('ABSPATH') or die();

use Bs_Formular2;
use Exception;
use BS\Formular2\BS_Formular2_Defaults_Trait;

/**
 * The BS-Formular2 Helper Class.
 *
 * @since      1.0.0
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */

class BS_Formular2_Helper {

    private static $instance;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private string $version;

    /**
     * The DB-Version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $dbVersion    The current DB_Version of this plugin.
     */
    private string $dbVersion;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $basename    The ID of this plugin.
     */
    private string $basename;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @var Bs_Formular2 $main          The main class.
     */
    protected Bs_Formular2 $main;

    /**
     * TRAIT of Default Settings.
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * @param string $basename
     * @param string $version
     * @param string $dbVersion
     * @param Bs_Formular2 $main
     * @return static
     */
    public static function instance(string $basename, string $version, string $dbVersion, Bs_Formular2 $main): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $version, $dbVersion, $main);
        }
        return self::$instance;
    }

    /**
     * @param string $basename
     * @param string $version
     * @param string $dbVersion
     * @param Bs_Formular2 $main
     */
    public function __construct(string $basename, string $version, string $dbVersion, Bs_Formular2 $main) {
        $this->basename = $basename;
        $this->version = $version;
        $this->dbVersion = $dbVersion;
        $this->main = $main;
    }

    /**
     * @param $array
     * @since 1.0.0
     * @return object
     */
    final public function bsFormular2ArrayToObject($array): object
    {
        foreach ($array as $key => $value)
            if (is_array($value)) $array[$key] = self::bsFormular2ArrayToObject($value);
        return (object)$array;
    }

    /**
     * @param string|null $args
     * @throws Exception
     * @return string
     * @access  final public
     */
    final public function bs_formular2_load_random_string(string $args = null): string
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(16);
            $str = bin2hex($bytes);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(16);
            $str = bin2hex($bytes);
        } else {
            $str = md5(uniqid('wp_bs_formulare', true));
        }

        return $str;
    }

    /**
     * @param int $passwordlength
     * @param int $numNonAlpha
     * @param int $numNumberChars
     * @param bool $useCapitalLetter
     * @return string
     * @access final public
     */
    public function getBSFormular2GenerateRandomId(int $passwordlength = 12, int $numNonAlpha = 1, int $numNumberChars = 4, bool $useCapitalLetter = true): string
    {
        $numberChars = '123456789';
        //$specialChars = '!$&?*-:.,+@_';
        $specialChars = '!$%&=?*-;.,+~@_';
        $secureChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $stack = $secureChars;
        if ($useCapitalLetter) {
            $stack .= strtoupper($secureChars);
        }
        $count = $passwordlength - $numNonAlpha - $numNumberChars;
        $temp = str_shuffle($stack);
        $stack = substr($temp, 0, $count);
        if ($numNonAlpha > 0) {
            $temp = str_shuffle($specialChars);
            $stack .= substr($temp, 0, $numNonAlpha);
        }
        if ($numNumberChars > 0) {
            $temp = str_shuffle($numberChars);
            $stack .= substr($temp, 0, $numNumberChars);
        }

        return str_shuffle($stack);
    }

    /**
     * @param float $bytes
     * @return string
     * @access final public
     */
    final public function BsFormular2FileSizeConvert(float $bytes): string
    {
        $result = '';
        $arBytes = array(
            0 => array("UNIT" => "TB", "VALUE" => pow(1024, 4)),
            1 => array("UNIT" => "GB", "VALUE" => pow(1024, 3)),
            2 => array("UNIT" => "MB", "VALUE" => pow(1024, 2)),
            3 => array("UNIT" => "KB", "VALUE" => 1024),
            4 => array("UNIT" => "B", "VALUE" => 1),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }
}