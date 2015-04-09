<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Hash;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 
		'last_name',
		'email',
		'username',
		'password'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}
	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}
	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}
	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}
	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}
	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}
	public static function validateNewUser($data)
	{
		$requirements = [
			'first_name' => 'required',
			'last_name' => 'required',
			'password' => 'required|confirmed|min:3',
			'email' => 'required|email|unique:users'
		];
		$validator = \Validator::make($data, $requirements);
		return $validator;
	}
	/**
	 * Create a new user
	 * @return NewUserResponse
	 */
	public static function createNewUser($data) {
		$validator = self::validateNewUser($data);
		$response = new NewUserResponse();
		$response->validator = $validator;
		if (!$validator->fails()) {
			$data['username'] = $data['email'];
			$user = self::create($data);
			$response->user = $user;
		}
		return $response;
	}
}

class NewUserResponse {
	public $validator;
	public $user;
	public function isSuccessful() {
		return !$this->validator->failed() && $this->user != false;
	}
	public function getMessages() {
		return $this->validator->messages();
	} 
}