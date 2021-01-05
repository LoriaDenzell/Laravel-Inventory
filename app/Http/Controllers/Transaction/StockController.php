<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Transaction\Stock;
use App\Model\Master\Product;
use TJGazel\Toastr\Facades\Toastr;

class StockController extends Controller
{
    public function index(){
        return view('Transaction.Stock.index');
    }

    public function popup_media_product(){
        return view('Transaction.Stock.view_product');
    }

    public function update(Request $request){

        if($request->id_raw_product == null){
            Toastr::error('Please choose a product first.', 'Error');
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

        if($data->save()){
            Toastr::success('Stock Correction saved successfully.', 'Success');
            return view('Transaction.Stock.report');
        }

        Toastr::error('Stock correction failed to save.', 'Error');
        return view('Transaction.Stock.report');
    }
 
    public function report(){
        return view('Transaction.Stock.report');
    }
}
