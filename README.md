## Scaffold Generator - Laravel 5

Documentation for the Laravel Framework can be found on the [Laravel website](http://laravel.com/docs/5.0).

It is mandatory to buy the regular license for the use of "metronic web template", you can do it here [Metronic Purchase Web] (http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469).

### Instalation & Configuration

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

    composer update

### Usage

If you want to create a simple catalog for an entity, you must enter the console the following command:

    php artisan make:scaffold Book

This will create the basis for a Book model structure, will be created the following files:

    model
    migration
    seed
    controller
    translate
    views
        index
        new
        edit

Additionally, the following sentences are added to file routes.php

    Route::group([], function()
    {
        Route::get("/books", "BooksController@index");
        Route::get("/books/create", "BooksController@create");
        Route::post("/books/store", "BooksController@store");
        Route::get("/books/{id}/edit", "BooksController@edit");
        Route::post("/books/update", "BooksController@update");
        Route::get("/books/{id}/active", "BooksController@active");
        Route::get("/books/{id}/deactive", "BooksController@deactive");
        Route::post("/books/delete", "BooksController@destroy");
    });

If we want to specify the fields we want to use in the state I must specify when running the command: `make:scaffold`

    php artisan make:scaffold Book --fields="isbn:string:required:unique,title:string:required,author:string:required,edition:integer,active:boolean"

If you want to add a prefix to the sights and routes, you can do so by adding as shown in the following command:

    php artisan make:scaffold Book --prefix=admin
