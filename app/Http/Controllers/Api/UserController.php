<?php namespace App\Http\Controllers\Api;

use Input;
use Auth;
use Response;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller {
	function register() {
		$result = User::createNewUser(Input::all());

		$response = [];
		if ($result->isSuccessful()) {
			Auth::setUser($result->user);
			$response['success'] = true;
			$response['user'] = $result->user;
		} else {
			$response['success'] = false;
			$response['messages'] = $result->getMessages();
		}

		return $response;
	}

	function login() {
		$email = Input::get('email');
		$password = Input::get('password');
		$response = ['success' => false, 'message' => null];

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {
			$response['success'] = true;
			$response['message'] = '';

			$user = Auth::getUser();

			//-- Token
			$token = App::make('user\Contracts\Token');
			$token->setUserId($user->id);
		}
		else {
			$response['success'] = false;
			$response['message'] = 'Invalid email or password';
		}

		return $response;
	}

	function forgotPassword() {

		$response = Password::remind(Input::only('email'), function($message)
		{
		    $message->subject('Password Reminder');
		});

		switch ($response) {
			case Password::REMINDER_SENT:
				$success = true;
				$message = 'Please check your email for a reset link';
				break;
			case Password::INVALID_USER:
				$success = false;
				$message = 'No account found matching this email';
				break;
		}

		return ['success' => $success, 'message' => $message];
	}

	function user() {
		$user = Auth::getUser();
		return ['user' => $user];
	}
}