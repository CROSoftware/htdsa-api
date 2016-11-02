<?php

namespace Htdsa;

use Htdsa\Auth\CoreAuthHandler;

/**
 * Class Net
 * @package Htdsa\API
 */
Class Net{

    /**
     * @var string
     */
    private $identity = '';

    /**
     * @var string
     */
    private $private_key = '';

    /**
     * @var string
     */
    private $public_key = '';

    /**
     * @var array
     */
    private $options = [];

	/**
	 * @param      $identity
	 * @param      $private_key
	 * @param      $public_key
	 * @param bool $debug
	 */
	public function __construct($identity, $private_key, $public_key, $debug = false)
    {
        $this->identity = $identity;
        $this->private_key = $private_key;
        $this->public_key = $public_key;

        $this->options = [
            'auth' => new CoreAuthHandler($identity, $private_key, $public_key, $debug)
        ];
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function post($url, $data = [], $headers = [])
    {
        if(empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

	    $response = \Requests::post($url, $headers, $data, $this->options);

        return $response;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function put($url, $data = [], $headers = [])
    {
        if(empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

	    $response = \Requests::put($url, $headers, $data, $this->options);

        return $response;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function patch($url, $data = [], $headers = [])
    {
        if(empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

	    $response = \Requests::patch($url, $headers, $data, $this->options);

        return $response;
    }

    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function get($url, $data=[], $headers = [])
    {
        if(empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

        $data = http_build_query($data);
        if(strlen($data) > 0) $url .= "?";
        $url .= $data;
        $response = \Requests::get($url, $headers, $this->options);

        return $response;
    }

    public function delete($url, $data=[], $headers = [])
    {
        if(empty($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

        $data = http_build_query($data);
        if(strlen($data) > 0) $url .= "?";
        $url .= $data;
        $response = \Requests::delete($url, $headers, $this->options);

        return $response;
    }
}
