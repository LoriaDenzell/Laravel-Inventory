<?php

namespace App\Model\Transaction\Sales;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesD extends Model
{
    use LogsActivity;
    protected $table = "sales_d";

    protected $fillable = [
        'id_sales', 'id_product', 'total', 'price',
    ];

    protected static $logAttributes = 
    [
        'id_sales', 'id_product', 'total', 'price',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'SalesD'; 

    public function sale(){
        return $this->belongsTo('App\Model\Transaction\Sales\SalesH', 'id_sales');
    }

    public function product(){
        return $this->belongsTo('\App\Model\Master\Product', 'id_product');
    }

    public function category(){
        return $this->belongsTo('\App\Model\Master\Category', 'category_id');
    }
 
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A sales product/s information has been {$eventName}";
    }
}
