<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\JsonWebToken;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Config;

class JsonWebTokenServiceProvider extends ServiceProvider {
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('user\Contracts\Token', function($app, $parameters)
        {
        	if (isset($parameters['token'])) {
        		$encodedToken = $parameters['token'];
        	} else {
        		$encodedToken = Input::get('token', Request::header('X-Token'));
        	}

            $secret = Config::get('site.jwt.secret');

            $token = new JsonWebToken($secret, $encodedToken);
            return $token;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['user\Contracts\Token'];
    }
}