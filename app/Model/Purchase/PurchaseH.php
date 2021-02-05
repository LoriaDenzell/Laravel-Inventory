<?php

namespace App\Model\Purchase;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseH extends Model
{
    use LogsActivity;
    protected $table = "purchase_h";

    protected $fillable = [
        'no_invoice', 'total', 'id_ven', 'active', 'user_modified', 'date', 'information',
    ];

    protected static $logAttributes = 
    [
        'no_invoice', 'total', 'id_ven', 'active', 'user_modified', 'date', 'information',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'PurchaseH';

    public function user_modify(){
        return $this->belongsTo('\App\User', 'user_modified');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A purchase information has been {$eventName}";
    }
}
