<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\v_invoice_detail;
use App\Models\Invoice_detail;


class InvoiceDetailController extends Controller
{

	public function __construct()
	{
        $this->middleware('auth');
	}    
    
	public function index()
	{
        return v_invoice_detail::get();
	}

	public function read($id)
	{
        return v_invoice_detail::find($id);
	}

    // 更新
    public function update(Request $request, Invoice_detail $detail)
    {
        $detail->update($request->all());
        return $detail;
    }

    // 削除
    public function destroy($id)
    {
        Invoice_detail::destroy($id);
        return $id;
    }



}
