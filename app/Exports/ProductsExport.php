<?php

namespace App\Exports;

use App\Model\Master\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::where('product_active', '=', 1)->get();
    }
}
