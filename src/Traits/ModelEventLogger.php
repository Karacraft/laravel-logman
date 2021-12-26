<?php

namespace Karacraft\Logman\Traits;

use Exception;
use ReflectionClass;
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
                    
                    return EventLogger::create([
                        'action'      => static::getActionName($eventName),
                        'table' => get_class($model),
                        'rowid'   => $model->id,
                        'description' => ucfirst($eventName) . " " . $reflect->getShortName(),
                        'original'     => json_encode($model->getOriginal()),
                        'changes'     => json_encode($model->getDirty()),
                        'ipaddress' => static::getUserIpAddr(),
                        'user_id'     => auth()->user()->id ,
                        'user_name' => auth()->user()->name,
                    ]);
                } catch (Exception $e) {
                    Log::error($e);
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

        return [
            'created',
            'updated',
            'deleted',
        ];
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

    protected static function getUserIpAddr(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
