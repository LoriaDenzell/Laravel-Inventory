<?php

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'addons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'addon_name', 
        'addon_cost', 
        'addon_active'
    ];

    protected static $logAttributes = 
    [
        'addon_name', 
        'addon_cost', 
        'addon_active'
    ];
    protected static $logOnlyDirty = true;

    protected static $logName = 'Addon';

    public function user_modify()
    {
        return $this->belongsTo('\App\User', 'user_modified');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "An Addon has been {$eventName}";
    }

}
