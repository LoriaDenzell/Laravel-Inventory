<?php

namespace App\Model\Transaction\Sales;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesH extends Model
{
    use LogsActivity;
    protected $table = 'sales_h';

    protected $fillable = [
        'invoice_no', 'date', 'total', 'active', 'user_modified', 'shop_name', 'information',
    ];

    protected static $logAttributes = 
    [
        'invoice_no', 'date', 'total', 'active', 'user_modified', 'shop_name', 'information',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'SalesH';

    public function user_modify(){
        return $this->belongsTo('\App\User', 'user_modified');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A sales information has been {$eventName}";
    }
}
