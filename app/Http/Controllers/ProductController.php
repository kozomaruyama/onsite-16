<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\v_product;

use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ExcelExport; 
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{

    public function __construct()
	{
        $this->middleware('auth');
	}

    // 読込
	public function index()
	{
        return v_product::get();
	}
	public function read($id)
	{
		return Product::find($id);
	}

    // 登録
    public function store(Request $request)
    {
        $product = $request;
        $product['cost_1'] = round((( $product['cost'] * 0.26)/75)/10,1);
        return Product::create($product->all());
    }

    // 更新
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return $product;
    }

    // 削除
    public function destroy(Product $product)
    {

        try {
            $product->delete();
            return ['status'=>true, 'value'=>$product];
        } catch(\Throwable $e) {
            return ['status'=>false, 'value'=>$e];
        }

        // $product->delete();
        // return $product;
    }

    // 一括更新
    public function group(Request $request) {

        $targetItems = $request->targetItems;
        $updateValues = $request->updateValues;

        $sql ="UPDATE products SET ";

        $keys ="-1";
        foreach  ($targetItems as $item) {
            $keys = $keys . "," . $item["id"] ;
        }

        $fieldNames = array_keys($updateValues);
        $i = 0;
        foreach  ($updateValues as $value) {
            if ($value != "") {
                $sql = $sql . $fieldNames[$i] ." = '" . $value . "',";
            }
            $i++;
        }
        $sql = substr($sql,0, strlen($sql) - 1). " WHERE id IN (" . $keys . ")";
// file_put_contents("sql.txt",  $sql);
        DB::update($sql);

        return $sql;
    }

    public function export()
    {

        $headers = Schema::getColumnListing('products');
        $details = Product::get();
        $view = \view('export/products_list', ['headers'=>$headers,'details'=>$details]);
        return Excel::download(new ExcelExport($view), 'products.xlsx');

    }       

}
