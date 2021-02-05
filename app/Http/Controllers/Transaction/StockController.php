<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Transaction\Stock;
use App\Model\Master\Product;
use TJGazel\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class StockController extends Controller
{
    public function index()
    {
        return view('Transaction.Stock.index');
    }

    public function popup_media_product()
    {
        return view('Transaction.Stock.view_product');
    }

    public function update(Request $request){

        if($request->id_raw_product == null)
        {
            toastr()->warning('Please choose a product first.');
            return back();
        }

        $detail = new Stock();
        $detail->id_product = $request->id_raw_product;
        $detail->total = $request->total/1;
        $detail->information = "Stock Correction";
        $detail->type = "correction";
        $detail->save();

        $data = Product::find($request->id_raw_product);
        $data->stock_total = $request->total;

        if($data->save())
        {
            toastr()->success('Stock Correction successfully updated.');
            return view('Transaction.Stock.report');
        }

        toastr()->error('Stock correction failed to update.');
        return view('Transaction.Stock.report');
    }
 
    public function report(){
        return view('Transaction.Stock.report');
    }
}
