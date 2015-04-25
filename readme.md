## Starter App Rest API for Laravel 5

This is the rest API for the starter apps:

- [Starter App for Native iOS - Swift](https://github.com/joseph-montanez/starter-app-native-ios)

## Install

You will need composer to start. To download composer you can go to [GetComposer.org](https://getcomposer.org/download/).

	php composer.phar install

### !OSX Mcrypt Issue!
Note if you are on OSX Yosemite 10.10, then you might get an error about mcrypt being required. You can resolve with fixing the local installation from this tutorial [Install mcrypt for php on Mac OSX 10.10 Yosemite for a Development Server](http://coolestguidesontheplanet.com/install-mcrypt-php-mac-osx-10-10-yosemite-development-server/). Alternative you can use [MAMP](https://www.mamp.info/en/), [Vagrant](https://www.vagrantup.com/), or [Docker](http://www.docker.com/)
	
Next you need to copy and edit the example env file

	cp .env.example .env
	
Don't forget to edit **APP_KEY** and **JWT_SECRET**

After that you can run the migration

	php artisan migrate

## Run

This will run as [http://localhost:8000/](http://localhost:8000/)

	php artisan serve


## Remigrate Everything

	php artisan migrate:reset


### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
