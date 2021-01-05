<?php

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    //
    use LogsActivity;
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_code', 
        'product_name', 
        'product_type', 
        'product_brand', 
        'purchase_price', 
        'product_selling_price',
        'product_information',
        'product_active',
        'product_image',
        'user_modified',
        'stock_available',
        'stock_total',
    ];

    protected static $logAttributes = 
    [
        'product_code', 
        'product_name', 
        'product_type', 
        'product_brand', 
        'purchase_price', 
        'product_selling_price',
        'product_information',
        'product_active',
        'product_image',
        'user_modified',
        'stock_available',
        'stock_total',
    ];
    protected static $logOnlyDirty = true;

    protected static $logName = 'Product';

    public function user_modify()
    {
        return $this->belongsTo('\App\User', 'user_modified');
    }

    public function product_category()
    {
        return $this->belongsTo('\App\Model\Master\Category', 'category_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A product has been {$eventName}";
    }

}
