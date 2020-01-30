# laravelizer
### Create Models, Migrations, and Factories for any existing MySQL database

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



## Known Limitations (Help Wanted)

**Enum and Geometry Types:** I just convert them to strings. So migrations and factories involving those types of fields are incorrect. I'm not sure how to approach location, but I do have an idea for enums. We could, in theory, run a query to get the distinct values from the column and assume that those are the values that should be used to create the enum column in the migration.
