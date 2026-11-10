# ![Karacraft Logo](https://avatars.githubusercontent.com/u/42495441?s=24&v=4)  Karacraft Logman


A generic, production-ready model event logger for Laravel applications.

Karacraft Logman records create, update, and delete events for Eloquent models that use its `ModelEventLogger` trait. It's designed to be lightweight, easy to integrate, and safe for large-scale deployments.

## Installation

```bash
composer require karacraft/logman
```

Publish the migration, then run it:

```bash
php artisan vendor:publish --provider="Karacraft\Logman\LogmanServiceProvider" --tag="logman-migrations"
php artisan migrate
```

## Usage

### Basic Usage

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

### Configuration

Copy `.env.example` to `.env` and customize as needed:

```bash
cp .env.example .env
```

Available environment variables:

| Variable | Description | Default |
|----------|-------------|---------|
| `LOGMAN_DEFAULT_RECORD_EVENTS` | Comma-separated list of events to record (e.g., `created,updated`) | `created,updated,deleted` |
| `LOGMAN_USER_ATTRIBUTES` | Comma-separated user attribute keys to look up for `user_name` (e.g., `name,email`) | `name,email` |

### Using Config File

You can also configure via `config/logman.php`:

```php
return [
    'default_record_events' => ['created', 'updated'],
    'user_attributes' => ['name', 'email'],
];
```

## Log Viewer

The log viewer is available at `/logman` and requires authentication. Its view uses the host application's `<x-app-layout>` component and expects Tabulator to be available in the layout.

### Adding Tabulator

If your app doesn't already include Tabulator, add it to your layout:

```blade
@head
    <script src="https://cdn.jsdelivr.net/npm/tabulator/dist/tabulator.min.js"></script>
</head>
```

## Production Readiness Checklist

Before deploying to production, ensure the following:

### Performance

- [ ] Use `json` columns for `original` and `changes` (already configured)
- [ ] Consider adding database indexes on frequently queried fields (`table`, `action`, `created_at`)
- [ ] For very large log volumes, implement periodic archiving or partitioning of the `event_logger` table

### Security

- [ ] Ensure `/logman` route is protected with authentication (already configured)
- [ ] Review who has access to the log viewer — consider restricting by role/permission
- [ ] Add rate limiting if logging high-volume models:
  ```php
  Route::middleware(['auth', 'throttle:10,1'])->group(function () {
      Route::get('/logman', [LogController::class, 'index']);
  });
  ```

### Monitoring

- [ ] Verify that failed log writes are captured in Laravel's log (already implemented)
- [ ] Set up alerts for unusual spikes in log volume
- [ ] Consider adding a backup strategy for the `event_logger` table if it grows large

### File Permissions

- [ ] Ensure the web server can read/write to the storage directory if using file-based logging
- [ ] Verify that the database user has write permissions on the `event_logger` table

## Troubleshooting

| Issue | Cause | Solution |
|-------|-------|----------|
| Migration fails | Missing migration published | Run `php artisan vendor:publish --provider="Karacraft\Logman\LogmanServiceProvider" --tag="logman-migrations"` then `php artisan migrate` |
| `user_name` is null | No authenticated user or wrong attribute key | Check that a user is logged in and that the correct attribute is configured in `.env` or `config/logman.php` |
| IP address shows as "UNKNOWN" | Running outside web context (e.g., console job) | The trait gracefully handles this — logs will still be created with `ipaddress: UNKNOWN` |
| Log viewer not loading | Tabulator JS not available in layout | Add the Tabulator CDN link to your `<head>` section |
| JSON parsing errors on large models | Very large attributes stored as JSON | Consider filtering out sensitive/large fields before logging, or use a smaller subset of attributes |

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## License

Karacraft Logman is licensed under the MIT License.
