<?php

namespace App\Exports;

use App\Model\Purchase\PurchaseH;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PurchaseH::where('active', '=', 1)->get();
    }
}
