<?php

namespace App\Model\Purchase;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseD extends Model
{
    use LogsActivity;
    protected $table = "purchase_d";

    protected $fillable = [
        'id_purchase', 'id_product', 'total', 'price',
    ];

    protected static $logAttributes = 
    [
        'id_purchase', 'id_product', 'total', 'price',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'PurchaseD';

    public function purchase(){
        return $this->belongsTo('\App\Model\Purchase\PurchaseH', 'id_purchase');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A purchase product/s information has been {$eventName}";
    }

}
