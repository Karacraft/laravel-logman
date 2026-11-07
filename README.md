# Karacraft Logman

Karacraft Logman records create, update, and delete events for Eloquent models that use its `ModelEventLogger` trait.

## Installation

```sh
composer require karacraft/logman
```

Publish the migration, then run it:

```sh
php artisan vendor:publish --provider="Karacraft\Logman\LogmanServiceProvider" --tag="logman-migrations"
php artisan migrate
```

## Usage

Add the package trait to each model you want to log:

```php
use Illuminate\Database\Eloquent\Model;
use Karacraft\Logman\Traits\ModelEventLogger;

class Post extends Model
{
    use ModelEventLogger;
}
```

By default, the trait logs the model's `created`, `updated`, and `deleted` events. To record a different set of Eloquent model events, define a protected static `$recordEvents` property on the model:

```php
class Post extends Model
{
    use ModelEventLogger;

    protected static $recordEvents = ['created', 'updated'];
}
```

Each log entry stores the model class and ID, event action, original attributes, dirty attributes, IP address, and the authenticated actor's ID and name when available. Without an authenticated user, `user_id` and `user_name` are `null`; the package does not require or look up a particular User model. If writing a log entry fails, the error is reported to Laravel's log and does not interrupt the model operation.

The log viewer is available at `/logman` and requires authentication. Its view uses the host application's `<x-app-layout>` component and expects Tabulator to be available in the layout.
