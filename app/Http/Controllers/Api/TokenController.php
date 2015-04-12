<?php namespace App\Http\Controllers\Api;

use App;
use Input;
use Device;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
	/**
	 *
	 */
	public function authorize($uuid = null)
	{
		$token = App::make('user\Contracts\Token');
		if ($uuid === null) {
			$uuid = Input::get('uuid');
		}

		$authorized = false;
		if ($token->isAuthorize() && $token->validateUUID($uuid)) {
			$authorized = true;
		}

		return [
			'token' => $token->encode(),
			'authorized' => $authorized,
			'success' => true
		];
	}

	/**
	 *
	 */
	public function generate($uuid = null)
	{
		$token = App::make('user\Contracts\Token');
		if ($uuid === null) {
			$uuid = Input::get('uuid');
		}

		if ($uuid !== null) {
			$token->uuid = $uuid;

			//-- Create a place holder device if it does not exist
			$device = App\Device::firstOrCreate(['uuid' => $uuid, 'user_id' => 0]);

			return [
				'token' => $token->encode(),
				'success' => true
			];
		} else {
			return [
				'message' => 'A UUID is required to generate a token',
				'success' => false
			];
		}
	}
}