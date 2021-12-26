<?php

namespace Karacraft\Logman\Models;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class EventLogger extends Model
{
    protected $table = "event_logger";
    protected $fillable = ['action','table','rowid','description','original','changes','user_id','user_name','ipaddress'];
    /**    Relationships  */
    //================================//
    public function user(){ return $this->belongsTo(User::class);}
    /*************************-METHODS-*****************/
    protected function serializeDate(DateTimeInterface $date){ return $date->format('d-m-Y H:i:s');}
    /*************************-GETTERS-*****************/
    function getContentTypeAttribute($value){ return Str::substr($value, 4,Str::length($value));}
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
