<?php

namespace Karacraft\Logman\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class EventLogger extends Model
{
    protected $table = "event_logger";
    protected $fillable = ['action','table','rowid','description','original','changes','user_id','user_name','ipaddress'];

    /*************************-METHODS-*****************/
    protected function serializeDate(DateTimeInterface $date){ return $date->format('d-m-Y H:i:s');}
    /*************************-GETTERS-*****************/
    function getTableAttribute($value){ return class_basename($value);}
    function getActionAttribute($value)
    {
        switch($value)
        {
            case 'created':
                return 'Create';
                break;
            case 'updated':
                return 'Update';
                break;
            case 'deleted':
                return 'Delete';
                break;
        }
    }

}
