<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'cms';
    protected $primaryKey = 'id';

    protected $fillable = [
        'org_name', 
        'org_contact', 
        'org_email', 
        'org_address', 
        'high_pct', 
        'ave_pct',
        'low_pct',
        'tax_pct',
        'max_activities'
    ];

    protected static $logAttributes = 
    [
        'org_name', 
        'org_contact', 
        'org_email', 
        'org_address', 
        'high_pct', 
        'ave_pct',
        'low_pct',
        'tax_pct',
        'max_activities'
    ];
    protected static $logOnlyDirty = true;

    protected static $logName = 'Content';

    public function user_modify()
    {
        return $this->belongsTo('\App\User', 'user_modified');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A Content has been {$eventName}";
    }

}
