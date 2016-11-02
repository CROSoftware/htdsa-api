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
	function __construct($endpoint, $identity, $private_key, $public_key, $debug = false, $append_slash = false)
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
		$this->append_slash = $append_slash;
	}

	// subclass the API endpoint
	public function __get($name)
	{
		// append endpoint and return new API object
		$endpoint = $this->endpoint.'/'.$name;
		$endpoint .= ($this->append_slash == true ? '/' : '');

		return new API($endpoint, $this->identity, $this->private_key, $this->public_key, $this->debug, $this->append_slash);
	}

	public function call($args, $method='GET', $headers=array())
	{
		// setup requests layer if its not built yet
		if($this->net === null)
		{
			$this->net = new Net($this->identity, $this->private_key, $this->public_key, $this->debug);
		}

		$response = new \stdClass();

		if($method == 'POST') {
			$response = $this->net->post($this->endpoint.($this->append_slash == true ? '/' : ''), $args, $headers);
		} elseif($method == 'GET') {
			$response = $this->net->get($this->endpoint.($this->append_slash == true ? '/' : ''), $args, $headers);
		} else {
			throw new \Exception('Invalid request method.');
		}

		if($response === false)
		{
			throw new \Exception('Method Failed');
		}

		// check return status code
		//if($response->status_code < 200 || $response->status_code >= 300)
		//{
		//	throw new \Exception('Method Failed('.$response->status_code.'): '.$response->body);
		//}

		// response looks good, return body after converting from json
		return $response;
		//return json_decode($response->body);
	}

	public function get($args=array(), $headers=array())
	{
		return $this->call($args, 'GET', $headers);
	}

	public function post($args=array(), $headers=array())
	{
		return $this->call($args, 'POST', $headers);
	}
}
