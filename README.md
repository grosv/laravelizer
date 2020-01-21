# laravelizer
### Create Models, Migrations, and Factories for any existing MySQL database

## Installation
Create the Laravel installation into which you want to add model, migrations, and factories for your existing (presumably non-Laravel) database. Then install this package in that Laravel installation like so:

```bash
composer require edgrosvenor/laravelizer --dev
```

## Configuration
By default, models will be created in your app root, factories in database/factories, and migrations in database/migrations. Optionally Nova resources can be added at app/Nova and tests can be generated at tests/unit/. You can choose whether to disable any of these or change the paths at which they're created by publishing `php artisan vendor:publish` and editing config/laravelizer.php.

## Usage

```bash
php artisan laravelize:table {table_name} {connection?}
```

### Options
`--force` Overwrite any existing files related to the table.

`--silent` Accept the default names and paths rather than prompting for confirmations.

`--explain` Just tells you what the operation would create and where if you were to actually run it.

## Contributing
This package is tested against [a sample database referenced in the MySQL documentation](https://github.com/datacharmer/test_db) so grab that and use it for the default connection when you are working locally and testing this package.



## Known Limitations (Help Wanted)

**Enum and Geometry Types:** I just convert them to strings because my brief attempt to implement custom types ended in tears. So migrations and factories involving those types of fields are incorrect.
