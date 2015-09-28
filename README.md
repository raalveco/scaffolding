# Scaffold Generator - Laravel 5 &amp; Metronic Template

Documentation for the Laravel Framework can be found on the [Laravel website](http://laravel.com/docs).

## Instalation & Configuration

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `raalveco/scaffolding` and the publish the scaffolding with the instruction `php artisan vendor:publish --tag=scaffolding`.

	"require-dev": {
		"raalveco/scaffolding": "dev-master"
	}
	
	"scripts": {
		"post-update-cmd": [
			"php artisan vendor:publish --tag=scaffolding"
		]
  }

The next step is to add the service provider. Open `config/app.php`, and add a new item to the providers array.

    'Scaffolding\ScaffoldingServiceProvider',


Next, update Composer from the Terminal:

    composer update --dev
