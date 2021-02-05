<?php

namespace App\Model\Transaction\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleAddon extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'sales_addons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'addon_id', 
        'sales_h', 
        'total_addon', 
        'price'
    ];

    protected static $logAttributes = 
    [
        'addon_id', 
        'sales_h', 
        'total_addon', 
        'price'
    ];
    protected static $logOnlyDirty = true;

    protected static $logName = 'Sale Addon';

    public function sale(){
        return $this->belongsTo('App\Model\Transaction\Sales\SalesH', 'id_sales');
    }

    public function addon(){
        return $this->belongsTo('App\Model\Master\Addon', 'addon_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A Sales Addon has been {$eventName}";
    }
}
