<?php

namespace Karacraft\Logman\Traits;

use ReflectionClass;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Karacraft\Logman\Models\EventLogger;

/**
 * Class ModelEventLogger
 * @package App\Traits
 *
 *  Automatically Log Add, Update, Delete events of Model.
 */
trait ModelEventLogger
{
    /**
     * Automatically boot with Model, and register Events handler.
     */
    protected static function bootModelEventLogger()
    {
        foreach (static::getRecordActivityEvents() as $eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                try {
                    $reflect = new ReflectionClass($model);
                    $user = auth()->user();
                    
                    return EventLogger::create([
                        'action'      => static::getActionName($eventName),
                        'table' => get_class($model),
                        'rowid'   => $model->id,
                        'description' => ucfirst($eventName) . " " . $reflect->getShortName(),
                        'original'     => json_encode($model->getOriginal()),
                        'changes'     => json_encode($model->getDirty()),
                        'ipaddress' => static::getUserIpAddr(),
                        'user_id'     => $user ? $user->getAuthIdentifier() : null,
                        'user_name' => static::resolveUserName($user),
                    ]);
                } catch (Throwable $e) {
                    Log::error('Failed to write a model event log.', [
                        'exception' => $e,
                        'model' => get_class($model),
                        'event' => $eventName,
                    ]);
                }
            });
        }
    }

    /**
     * Set the default events to be recorded if the $recordEvents
     * property does not exist on the model.
     *
     * @return array
     */
    protected static function getRecordActivityEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        // Fall back to config default
        $config = config('logman', ['default_record_events' => ['created', 'updated', 'deleted']]);
        
        return array_filter($config['default_record_events'], fn ($event) => in_array($event, ['created', 'updated', 'deleted']));
    }

    /**
     * Return Suitable action name for Supplied Event
     *
     * @param $event
     * @return string
     */
    protected static function getActionName($event)
    {
        return(strtolower($event));

    }

    protected static function getUserIpAddr()
    {
        // First try Laravel's request helper (handles proxies/headers automatically)
        if ($request = \Illuminate\Support\Facades\Request::instance()) {
            return $request->ip();
        }

        // Fallback to raw $_SERVER variables
        $ipaddress = '';
        
        foreach ([
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ] as $header) {
            if (isset($_SERVER[$header])) {
                // HTTP_X_FORWARDED_FOR can contain multiple IPs - take the first one
                $ipaddress = preg_split('/[,;]+/', $_SERVER[$header], 1, 2);
                return !empty($ipaddress) ? reset($ipaddress) : 'UNKNOWN';
            }
        }
        
        $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Resolve the user's display name from configured attribute keys.
     *
     * @param \Illuminate\Auth\User|null $user
     * @return string|null
     */
    protected static function resolveUserName($user)
    {
        if (!$user) {
            return null;
        }

        $attributes = config('logman', ['user_attributes' => ['name', 'email']]);

        foreach ($attributes as $attribute) {
            if (has_property($user, $attribute)) {
                $value = getattr($user, $attribute);
                return is_string($value) && trim($value) !== '' ? $value : null;
            }
        }

        return null;
    }

}
