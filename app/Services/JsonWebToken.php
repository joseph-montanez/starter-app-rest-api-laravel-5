<?php namespace App\Services;

use Device;
use JWT;
use Illuminate\Support\Facades\Auth as Auth;

class JsonWebToken {
	// TODO: user specifics (like auth) should be moved somewhere else
	/**
	 * @var User
	 */
	protected $user = null;

	/**
	 * @var bool If the token is an authorized user token
	 */
	protected $authorized;

	/**
	 * @var bool If the token is valid
	 */
	protected $valid;

	/**
	 * @var array The data to encode
	 */
	protected $data;

	/**
	 * @var string the encoded results
	 */
	protected $token;

	/**
	 * @var string The key to encode with
	 */
	protected $secret;

	/**
	 * @var array Array of strings for error messages 
	 */
	protected $messages = [];

	public function __construct($secret, $token = null)
	{
		$this->data = array();

		$this->secret = $secret;

		if ($token !== null) {
			$this->token = $token;
			$this->decode();
			$this->authorize();
		}
	}

	public function setUser(User $user)
	{
		$this->user = $user;
		$this->setUserId($user->id);
		Auth::login($user);
	}

	public function getUser()
	{
		if ($this->user == null && isset($this->data['user']['id'])) {
			$this->user = User::find($this->data['user']['id']);
		}
		return $this->user;
	}

	public function setUserId($id)
	{
		if (!isset($this->data['user'])) {
			$this->data['user'] = [];
		}
		$this->data['user']['id'] = $id;

		//-- If there is a UUID in the token, then override the device with the new user id
		if (isset($this->uuid)) {
			$device = Device::firstOrCreate(array('uuid' => $this->uuid));
			$device->user()->associate(User::find($id));
			$device->save();
		}
		// Auth::login($user);
	}

	public function getUserId()
	{
		return isset($this->data['user']['id']) ? $this->data['user']['id'] : null;
	}

	public function encode() {
		return JWT::encode($this->data ?: array(), $this->secret);
	}

	public function decode() {
		$token = $this->token;

		try {
			$this->data = $this->object_to_array(JWT::decode($token, $this->secret));
			$this->valid = true;
		}
		catch(\UnexpectedValueException $e) {
			$this->valid = false;
			$this->messages []= 'Token is not valid';
		}
		catch(\DomainException $e) {
			$this->valid = false;
			$this->messages []= 'Token is not valid';
		}

		return $this->valid;
	}

	public function authorize() {
		$this->authorized = false;
		if (isset($this->data['user']['id'])) {
			$user = User::find($this->data['user']['id']);
			if ($user) {
				$this->user = $user;
				$this->authorized = true;
			}
		}

		if (!$this->authorized) {
			$this->messages['authorized'] = 'Token is not authorized';
		}

		return $this->authorized;
	}

	public function isAuthorize() {
		return $this->authorized;
	}

	public function isValid() {
		return $this->valid;
	}


	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return null;
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	public function __unset($name)
	{
		unset($this->data[$name]);
	}

	protected function object_to_array($obj) {
		if(is_object($obj)) $obj = (array) $obj;
		if(is_array($obj)) {
			$new = array();
			foreach($obj as $key => $val) {
				$new[$key] = $this->object_to_array($val);
			}
		}
		else $new = $obj;
		return $new;  
	}
}