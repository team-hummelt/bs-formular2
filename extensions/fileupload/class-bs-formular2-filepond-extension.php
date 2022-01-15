<?php
namespace BS\Formular2\Extension;

use BS\Formular2\BS_Formular2_Defaults_Trait;
use Bs_Formular2;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.hummelt-werbeagentur.de
 * @since      1.0.0
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bs_Formular2
 * @subpackage Bs_Formular2/admin
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Bs_Formular2_Filepond
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
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @var Bs_Formular2 $main The main class.
     */
    protected Bs_Formular2 $main;

    /**
     * TRAIT of Default Settings.
     *
     * @since    1.0.0
     */
    use BS_Formular2_Defaults_Trait;

    /**
     * @param string $basename
     * @param string $version
     * @param Bs_Formular2 $main
     * @return static
     */
    public static function instance(string $basename, string $version, Bs_Formular2 $main): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($basename, $version, $main);
        }
        return self::$instance;
    }

    /**
     * Initialize the class and set its properties.
     *
     * @param string $basename The name of this plugin.
     * @param string $version The version of this plugin.
     * @param Bs_Formular2 $main The version of this plugin.
     * @since    1.0.0
     */
    public function __construct(string $basename, string $version, Bs_Formular2 $main)
    {
        $this->basename = $basename;
        $this->version = $version;
        $this->main = $main;
    }

}