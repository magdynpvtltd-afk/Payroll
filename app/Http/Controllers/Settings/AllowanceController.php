<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;
use App\Models\settings\Allowance;
use Yajra\DataTables\Facades\DataTables;

class AllowanceController extends Controller
{
    public function index()
    {
        return view('pages.allowance.index');
    }
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $allowance = Allowance::find($id);
        $column_name = $request->input('name');
        $allowance->update([
            $column_name => $request->input('new_value')
        ]);
        return redirect()->back()->with('success', 'Allowance Updated SuccessFully !');
    }
    public function getData()
    {
        $allowance = Allowance::all();
        $data = $allowance->map(function ($row) {
            return [
                $row->id,
                $row->type,
                $row->value
            ];
        });
        return DataTables::of($data)->make(true);
    }


}
