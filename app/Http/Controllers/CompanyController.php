<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = auth()->user()->companies;
        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $fields = $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'phone' => 'required|string'
        ]);
        $fields['user_id'] = auth()->id();

        $company = Company::create($fields);

        return response()->json($company);
    }
}
