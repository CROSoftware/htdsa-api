<?php

namespace Htdsa;

/**
 * Class Core
 * @package Htdsa\API
 */
class API{

	private $net;
	private $endpoint = '';
	private $identity = '';
	private $private_key = '';
	private $public_key = '';
	private $debug = false;

	/**
	 * @param $endpoint
	 * @param $identity
	 * @param $private_key
	 * @param $public_key
	 */
	function __construct($endpoint, $identity, $private_key, $public_key, $debug = false)
	{
		// remove trailing slashes in endpoints
		if(substr($endpoint, -1) == '/'){
			$endpoint = substr($endpoint, 0, -1);
		}

		$this->endpoint = $endpoint;
		$this->identity = $identity;
		$this->private_key = $private_key;
		$this->public_key = $public_key;
		$this->debug = $debug;
	}

	// subclass the API endpoint
	public function __get($name)
	{
		// append endpoint and return new API object
		$this->endpoint .= '/'.$name;

		return new API($this->endpoint, $this->identity, $this->private_key, $this->public_key, $this->debug);
	}

	public function __call($name, $args)
	{
		// setup requests layer if its not built yet
		if($this->net === null)
		{
			$this->net = new Net($this->identity, $this->private_key, $this->public_key, $this->debug);
		}

		// remove array wrapping from __call() method
		$args = $args[0];

		$response = $this->net->post($this->endpoint.'/'.$name, $args);

		if($response === false)
		{
			throw new \Exception('Method Failed');
		}

		// check return status code
		if($response->status_code !== 200)
		{
			throw new \Exception('Method Failed('.$response->status_code.'): '.$response->body);
		}

		// response looks good, return body after converting from json
		return json_decode($response->body);
	}
}
