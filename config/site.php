<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Site Specific
	|--------------------------------------------------------------------------
	|
	| This file is for storing the site specific configuration.
	|
	*/

	'jwt' => [
		'secret' => env('JWT_SECRET', 'SomeRandomString'),
	],
];