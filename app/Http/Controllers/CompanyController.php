<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;


class CompanyController extends Controller
{

	public function __construct()
	{
        $this->middleware('auth');
	}


    // 読込
	public function index()
	{
		return Company::find(1);
	}

    // 読込
	public function read($id)
	{
		return Company::find($id);
	}

	// 更新
	public function update(Request $request, Company $company)
	{
			$company->update($request->all());
			return $company;
	}


}
