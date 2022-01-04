<?php

namespace Hupa\BsFormular2License;

use stdClass;

defined('ABSPATH') or die();

/**
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
class Hupa_Server_WP_Remote_Handle
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
     * The plugin License Options.
     *
     * @since    1.0.0
     * @access   private
     * @var      array  $options    plugin License Options.
     */
    private array $options;

    /**
     * The current version of the Plugin-Version.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the database Version.
     */
    protected string $version;

    /**
     * Hupa_Server_WP_Remote_Handle Instance
     * @return static
     * @since 1.0.0
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
        $this->options = get_option($this->basename . '_server_api');
    }

    public function wp_loaded_wp_remote() {
        //TODO Endpoints URL's
        add_filter($this->basename . '/api_urls', array($this, 'GetApiUrl'));
        //TODO JOB POST Resources Endpoints
        add_filter($this->basename . '/scope_resource', array($this, 'POSTApiResource'), 10, 2);
        //TODO JOB GET Resources Endpoints
        add_filter($this->basename . '/get_scope_resource', array($this, 'GETApiResource'), 10, 2);
        //TODO JOB VALIDATE SOURCE BY Authorization Code
        add_filter($this->basename . '/get_resource_authorization_code', array($this, 'InstallByAuthorizationCode'));
        //TODO JOB SERVER URL ÄNDERN FALLS NÖTIG
        add_filter($this->basename . '/update_server_url', array($this, 'UpdateServerUrl'));
    }

    public function UpdateServerUrl($url)
    {
        update_option('hupa_server_url', $url);
    }

    public function GetApiUrl($scope): string
    {
        return match ($scope) {
            'authorize_url' => get_option('hupa_server_url') . 'authorize?response_type=code&client_id=' . $this->options['client_id'],
            default => '',
        };
    }

    public function InstallByAuthorizationCode($authorization_code): object
    {
        $error = new stdClass();
        $error->status = false;
        $client_id = $this->options['client_id'];
        $client_secret = $this->options['client_secret'];
        $token_url = get_option('hupa_server_url') . 'token';
        $authorization = base64_encode("$client_id:$client_secret");

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic {$authorization}"
            ),
            'body' => [
                'grant_type' => "authorization_code",
                'code' => $authorization_code
            ]
        );

        $response = wp_remote_post($token_url, $args);
        if (is_wp_error($response)) {
            $error->message = $response->get_error_message();
            return $error;
        }

        $apiData = json_decode($response['body']);
        if (isset($apiData->error)) {
            $apiData->status = false;
            return $apiData;
        }
        
        $this->options['access_token'] = $apiData->access_token;
        update_option($this->basename . '_server_api', $this->options);
        $body = [
            'version' => $this->version,
        ];

        return $this->POSTApiResource('install', $body);
    }

    public function POSTApiResource($scope, $body = false)
    {
        $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->ApiPostArgs($body));
        if (is_wp_error($response)) {
            return $response->get_error_message();
        }
        if (is_array($response)) {
            $query = json_decode($response['body']);
            if (isset($query->error)) {
                if ($this->get_error_message($query)) {
                    $this->GetApiClientCredentials();
                }
                $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->ApiPostArgs($body));
                if (is_array($response)) {
                    return json_decode($response['body']);
                }
            } else {
                return $query;
            }
        }
        return false;
    }

    public function ApiPostArgs($body = []): array
    {
        $bearerToken = $this->options['access_token'];
        return [
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
    }

    private function get_error_message($error): bool
    {
        $return = false;
        switch ($error->error) {
            case 'invalid_grant':
            case 'insufficient_scope':
            case 'invalid_request':
                $return = false;
                break;
            case'invalid_token':
                $return = true;
                break;
        }
        return $return;
    }

    private function GetApiClientCredentials(): void
    {
        $token_url = get_option('hupa_server_url') . 'token';
        $client_id = $this->options['client_id'];
        $client_secret = $this->options['client_secret'];
        $authorization = base64_encode("$client_id:$client_secret");
        $error = new stdClass();
        $error->status = false;
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => true,
            'blocking' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Basic $authorization"
            ],
            'body' => [
                'grant_type' => 'client_credentials'
            ]
        ];
        $response = wp_remote_post($token_url, $args);
        if (!is_wp_error($response)) {
            $apiData = json_decode($response['body']);
            $this->options['access_token'] = $apiData->access_token;
            update_option($this->basename . '_server_api', $this->options);
        }
    }

    public function bsServerPOSTApiResource($scope, $body = false)
    {
        $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->ApiPostArgs($body));
        if (is_wp_error($response)) {
            return $response->get_error_message();
        }
        if (is_array($response)) {
            $query = json_decode($response['body']);
            if (isset($query->error)) {
                if ($this->get_error_message($query)) {
                    $this->GetApiClientCredentials();
                }
                $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->ApiPostArgs($body));
                if (is_array($response)) {
                    return $response['body'];
                }
            } else {
                return $response['body'];
            }
        }
        return false;
    }

    public function GETApiResource($scope, $body = false)
    {
        $response = wp_remote_get(get_option('hupa_server_url') . $scope, $this->GETApiArgs($body));
        if (is_wp_error($response)) {
            return $response->get_error_message();
        }
        if (is_array($response)) {
            $query = json_decode($response['body']);
            if (isset($query->error)) {
                if ($this->get_error_message($query)) {
                    $this->GetApiClientCredentials();
                }
                $response = wp_remote_get(get_option('hupa_server_url') . $scope, $this->GETApiArgs($body));
                if (is_array($response)) {
                    return json_decode($response['body']);
                }
            } else {
                return $query;
            }
        }
        return false;
    }

    private function GETApiArgs($body = []): array
    {
        $bearerToken = $this->options['access_token'];
        return [
            'method' => 'GET',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'sslverify' => true,
            'blocking' => true,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $bearerToken"
            ],
            'body' => $body
        ];
    }

    public function ApiDownloadFile($url, $body = [])
    {

        $bearerToken = $this->options['access_token'];
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

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            $this->GetApiClientCredentials();
        }

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            print_r($response->get_error_message());
            exit();
        }

        if (!is_array($response)) {
            exit('Download Fehlgeschlagen!');
        }
        return $response['body'];
    }

}
