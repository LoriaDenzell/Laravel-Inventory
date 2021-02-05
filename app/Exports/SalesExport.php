<?php

namespace App\Exports;

use App\Model\Transaction\Sales\SalesH;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SalesH::where('active', '=', 1)->get();
    }
}
