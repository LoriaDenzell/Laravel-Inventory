<?php

namespace App\Model\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Model\Transaction\Sales\SalesD;
use App\Model\Transaction\Sales\SalesH;

class Category extends Model
{
    use Notifiable, LogsActivity;
    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'category_status',
        'user_modified',
    ];

    protected static $logAttributes = 
    [
        'category_name',
        'category_status',
        'user_modified',
    ];

    protected static $logOnlyDirty = true;
    protected static $logName = 'Category';

    public function user_modify()
    {
        return $this->hasOne('\App\User', 'user_modified');
    }

    public function product(){
        return $this->belongsTo('\App\Model\Master\Product', 'product_id');
    }

    public function product_sales()
    {
        //$DateToday = date('yy-m-d', strtotime("yesterday"));
        $DateToday = date('yy-m-d');

        return $this->hasManyThrough(
            SalesD::class,
            Product::class,
            "product_type" // Foreign key on products table
            , "id_product" // Foreign key on sales_d table
            , "category_id" // Primary key on categories table
            , "product_id" // Primary key on products table
        )
            ->whereDate("sales_d.date_order", $DateToday)
            ->orderBy("sales_d.date_order");
    }

    public function category_sales()
    {
        //$DateToday = date('yy-m-d', strtotime("yesterday"));
        $DateToday = date('yy-m-d');

        return $this->hasManyThrough(
            SalesD::class,
            Product::class,
            "product_type" // Foreign key on products table
            , "id_product" // Foreign key on sales_d table
            , "category_id" // Primary key on categories table
            , "product_id" // Primary key on products table
        )
            ->orderBy("sales_d.date_order");
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A category has been {$eventName}";
    }
}
