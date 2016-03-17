## Scaffolding - Laravel 5.2 & Metronic Admin Template

Library that simplifies creating CRUD, with which we will create Controllers, Models, Views, Migrations, Seeds & Routes in seconds.

## Instalación

To install the package you need to add dependency on our composer.json at the root of laravel.

```js
{
    "require": {
        "raalveco/scaffolding": "dev-master"
    }
}
```

```bash
    composer update
```

## Laravel 5.2

In the $providers array add the following service provider for this package.

```php
// config/app.php

'providers' => [
    Raalveco\Scaffolding\ScaffoldingServiceProvider::class,
];
```

## Usage

If you want to create a simple catalog for an entity, you must enter the console the following command:

```bash
    php artisan make:scaffold Customer --fields="rfc:string:13[required|maxlength:13|minlength:12], first_name:string:100[required|alpha], last_name:string:100[required|alpha], email:string:100[required|email]"
```

This will create the bass for a Book model structure, will be created the following files:

```txt
    Controller:
        app/Http/CustomersController.php
    Model:
        app/Models/Customer.php
    Migration:
        database/migrations/0000_00_00_000000_create_customers_table.php
    Seed:
        database/seeds/CustomersSeeder.php
    Lang:
        resources/lang/en/customers.php
        resources/lang/es/customers.php
    Views:
        resources/views/customers/index.blade.php
        resources/views/customers/new.blade.php
        resources/views/customers/edit.blade.php
    Routes:
        app/Http/routes.php (Edited)
```

After running the command: make:scaffold, we should run a migrate and if necessary run the seeder that just generated.

```bash
    php artisan migrate
```

```bash
    php artisan db:seed --class=CustomersSeeder
```