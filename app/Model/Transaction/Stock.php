<?php

namespace App\Model\Transaction;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Stock extends Model
{
    use LogsActivity;
    protected $table = 'stock';

    protected static $logAttributes = 
    [
        'id_product', 'information','total','type',
    ];

    protected static $logOnlyDirty = true;
    protected static $logName = 'Stock';

    public function product(){
        return $this->belongsTo('\App\Model\Master\Product', 'id_product');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A Stock Correction has been {$eventName}";
    }
}
