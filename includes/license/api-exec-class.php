<?php

namespace BSFormular2APIExec\EXEC;

defined('ABSPATH') or die();

use stdClass;
use Hupa\BsFormular2License\Hupa_Server_WP_Remote_Handle;

if (!function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if (!function_exists('is_user_logged_in')) {
    require_once ABSPATH . 'wp-includes/pluggable.php';
}

/**
 * REGISTER HUPA PLUGIN|THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
final class Hupa_License_Exec_Api
{

    /**
     * Instance off Hupa_License_Exec_Api.
     *
     * @since    1.0.0
     * @access   private
     */
    private static $instance;

    /**
     * The current version off the DB-Version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $dbVersion The current version of the database Version.
     */
    protected string $dbVersion;

    /**
     * The current version off the Plugin-Version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version off the Plugin-Version.
     */
    protected string $version;

    /**
     * The BASENAME off this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $basename    The BASENAME off this plugin.
     */
    protected string $basename;

    /**
     * The plugin Slug Path.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_slug  plugin Slug Path.
     */
    protected string $plugin_slug;


    /**
     * The plugin dir.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_dir    plugin dir Path.
     */
    protected string $plugin_dir;

    /**
     * The plugin License Options ($this->basename . '_server_api') .
     *
     * @since    1.0.0
     * @access   protected
     * @var      array  $options   plugin License Options.
     */
    protected array $options;

    /**
     * WP-REMOTE Server API.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Hupa_Server_WP_Remote_Handle $api WP-REMOTE API.
     */
    protected Hupa_Server_WP_Remote_Handle $api;

    /**
     * @param string $dbVersion
     * @param string $version
     * @param string $plugin_name
     * @param string $plugin_slug
     * @return static
     */
    public static function instance(string $dbVersion, string $version, string $plugin_name, string $plugin_slug): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($dbVersion, $version, $plugin_name, $plugin_slug);
        }
        return self::$instance;
    }

    /**
     * @param string $db_version
     * @param string $version
     * @param string $plugin_name
     * @param string $plugin_slug
     */
    public function __construct(string $db_version, string $version, string $plugin_name, string $plugin_slug)
    {

        $this->dbVersion = $db_version;
        $this->version = $version;
        $this->basename = $plugin_name;
        $this->plugin_slug = $plugin_slug;
        $this->plugin_dir = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename;
        $this->options = get_option($this->basename . '_server_api');
        $this->api = Hupa_Server_WP_Remote_Handle::instance($this->basename, $this->version);


        if (is_user_logged_in() && is_admin()) {
            if (site_url() !== $this->options['license_url']) {
                $msg = 'Version: ' . $this->version . ' ungültige Lizenz URL: ' . $this->options['license_url'];
                $this->apiSystemLog('url_error', $msg);
            }
        }
    }

    public function make_api_exec_job($data): object
    {
        $return = new stdClass();
        $return->status = false;
        $getJob = $this->load_post_make_exec_job($data);

        if (!$getJob->status) {
            $return->msg = 'Exec Job konnte nicht ausgeführt werden!';
            return $getJob;
        }
        $getJob = $getJob->record;
        switch ($getJob->exec_id) {
            case '1':
                $this->options['license_url'] = site_url();
                update_option($this->basename . '_server_api', $this->options);
                $status = true;
                $msg = 'Lizenz Url erfolgreich geändert.';
                break;
            case '2':
                $this->options['client_id'] = $getJob->client_id;
                update_option($this->basename . '_server_api', $this->options);
                $status = true;
                $msg = 'Client ID erfolgreich geändert.';
                break;
            case '3':
                $this->options['client_secret'] = $getJob->client_secret;
                update_option($this->basename . '_server_api', $this->options);
                $status = true;
                $msg = 'Client Secret erfolgreich geändert.';
                break;
            case '4':
                $body = [
                    'version' => $this->version,
                    'type' => 'aktivierungs_file'
                ];

                $datei = $this->api->ApiDownloadFile(get_option('hupa_server_url').'download', $body);
                if($datei){
                    $file = $this->plugin_dir . DIRECTORY_SEPARATOR . $getJob->aktivierung_path;
                    file_put_contents($file, $datei);
                    $activate = activate_plugin( $this->plugin_slug );
                    if ( is_wp_error( $activate ) ) {
                        $status = false;
                        $msg = 'Plugin konnte nicht aktiviert werden.';
                    } else {
                        $status = true;
                        $msg = 'Plugin erfolgreich aktiviert.';
                        $this->options['client_id'] = $getJob->client_id;
                        $this->options['client_secret'] = $getJob->client_secret;
                        $this->options['license_url'] = site_url();
                        $this->options['product_install_authorize'] = true;
                        $this->options['license_message'] = '';
                        update_option($this->basename . '_server_api', $this->options);
                    }
                } else {
                    $status = false;
                    $msg = 'Plugin konnte nicht aktiviert werden.!';
                }
                break;
            case '5':
                deactivate_plugins( $this->plugin_slug );
                set_transient($this->basename . '_show_lizenz_info', true, 5);
                $this->options['client_id'] = '';
                $this->options['client_secret'] = '';
                $this->options['license_url'] = '';
                $this->options['product_install_authorize'] = false;
                $this->options['license_message'] = 'Das Plugin ' . strtoupper($this->basename).' wurde deaktiviert. Wenden Sie sich an den Administrator.';
                update_option($this->basename . '_server_api', $this->options);

                $status = true;
                $msg = strtoupper($this->basename) . ' erfolgreich deaktiviert.';
                break;
            case '6':
                $body = [
                    'version' => $this->version,
                    'type' => 'aktivierungs_file'
                ];
                $datei = $this->api->ApiDownloadFile(get_option('hupa_server_url').'download', $body);
                if($datei){
                    $file = $this->plugin_dir . DIRECTORY_SEPARATOR . $getJob->aktivierung_path;
                    file_put_contents($file, $datei);
                    $status = true;
                    $msg = 'Aktivierungs File erfolgreich kopiert.';
                } else {
                    $status = false;
                    $msg = 'Datei konnte nicht kopiert werden!';
                }
                break;
            case '7':
                $this->options['client_id'] = '';
                $this->options['client_secret'] = '';
                $this->options['license_url'] = '';
                $this->options['product_install_authorize'] = false;
                $this->options['license_message'] = 'Das Plugin ' . strtoupper($this->basename) . ' wurde deaktiviert. Wenden Sie sich an den Administrator.';
                update_option($this->basename . '_server_api', $this->options);

                $file = $this->plugin_dir . DIRECTORY_SEPARATOR . $getJob->file_path;
                $input = '';
                file_put_contents($file, $input);
                $status = true;
                $msg = 'Aktivierungs File erfolgreich gelöscht.';
                deactivate_plugins( $this->plugin_slug );
                break;
            case '8':
                update_option('hupa_server_url', $getJob->server_url);
                $status = true;
                $msg = 'Server URL erfolgreich geändert.';
                break;
            case '9':
                $body = [
                    'version' => $this->version,
                    'type' => 'update_version'
                ];
                apply_filters($this->basename . '/get_scope_resource', $getJob->uri, $body);
                $status = true;
                $msg = 'Version aktualisiert.';
                break;
            case'10':
                if($getJob->update_type == '1' || $getJob->update_type == '2'){
                   $updateUrl =  apply_filters($this->basename . '/scope_resource', 'hupa-update/url');
                   $url = $updateUrl->url;
                   $update_aktiv = true;
                } else {
                    $update_aktiv = false;
                    $url = '';
                }

                $this->options['update_aktiv'] = $update_aktiv;
                $this->options['update_type'] = $getJob->update_type;
                $this->options['update_url'] = $url;
                update_option($this->basename . '_server_api', $this->options);

                $status = true;
                $msg = 'Update Methode aktualisiert.';
                break;
            case'11':
               $updateUrl = apply_filters($this->basename . '/scope_resource', 'hupa-update/url');
               $this->options['update_url'] = $updateUrl->url;
               update_option($this->basename . '_server_api', $this->options);

                $status = true;
                $msg = 'URL Token aktualisiert.';
                break;
            default:
                $status = false;
                $msg = 'keine Daten empfangen';
        }

        $return->status = $status;
        $return->msg = $msg;
        return $return;
    }

    protected function load_post_make_exec_job($data, $body = []): object
    {
        $bearerToken = $data->access_token;
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'sslverify' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $bearerToken"
            ],
            'body' => $body
        ];

        $return = new stdClass();
        $return->status = false;
        $response = wp_remote_post($data->url, $args);
        if (is_wp_error($response)) {
            $return->msg = $response->get_error_message();
            return $return;
        }
        if (!is_array($response)) {
            $return->msg = 'API Error Response array!';
            return $return;
        }

        $return->status = true;
        $return->record = json_decode($response['body']);
        return $return;
    }

    public function apiSystemLog($type, $message)
    {

        $body = [
            'type' => $type,
            'version' => $this->version,
            'log_date' => date('m.d.Y H:i:s'),
            'message' => $message
        ];


        $sendErr = $this->api->POSTApiResource('error-log', $body);
    }

    public function get_post_scope_data($scope, $body = []) {
      return $this->api->POSTApiResource($scope, $body);
    }

} //endClass
