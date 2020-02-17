# laravelizer (a work in progress)
### Create Models, Migrations, and Factories for any existing MySQL database
[![Latest Version on Packagist](https://img.shields.io/packagist/v/edgrosvenor/laravelizer.svg?style=flat-square)](https://packagist.org/packages/edgrosvenor/laravelizer)
[![StyleCI](https://github.styleci.io/repos/234602553/shield?branch=master)](https://github.styleci.io/repos/234602553)
![Build Status](https://app.chipperci.com/projects/f7090772-1ef1-443e-acfc-8cb77cb84b51/status/master)

Whether you want to migrate away from another framework to Laravel or you just want to connect a Laravel installation to your database, this package makes it really easy.

Just define the connection to your database as you would any Laravel database. It doesn't have to be the default connection, but it can be. Then install this package and follow the instructions to create migrations, models, factories, tests, and / or Nova resources based on the structure of and existing data within your database.

## Installation
Create the Laravel installation into which you want to add model, migrations, and factories for your existing (presumably non-Laravel) database. Then install this package in that Laravel installation like so:

```bash
composer require edgrosvenor/laravelizer --dev
```

## Configuration
By default, models will be created in your app root, factories in database/factories, and migrations in database/migrations. Optionally Nova resources can be added at app/Nova and tests can be generated at tests/unit/. You can choose whether to disable any of these or change the paths at which they're created by publishing `php artisan vendor:publish` and editing config/laravelizer.php.

When you publish assets, the stubs used for each component are also published. We use blade templates for our stubs so they are pretty easy to edit if you want to change things up a bit.

## Usage

```bash
php artisan laravelize {table_name?} {--connection=} {--force}
```
If you do not specify a table name, we'll just do all of them.

### Options
`--force` Overwrite any existing files related to the table.
`--connection=` Name of the database connection you want to use if not the default connection.

## Contributing

I'd love some help to make this package super useful for the community. Submit a PR to improve the code of the README or just open an issue letting me know what you'd like to see added.

