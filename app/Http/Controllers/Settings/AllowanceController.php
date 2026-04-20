<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator ;
use App\Models\settings\Allowance;

class AllowanceController extends Controller
{
    public function edit(Request $request){
        $id = $request->input('id'); 
        $allowance = Allowance::find($id) ; 
        $column_name = $request->input('name') ;
        $allowance->update([
            $column_name => $request->input('new_value')
        ]);
        return redirect()->back()->with('success' , 'Allowance Updated SuccessFully !');
    }

    public function index(){
      $allowance = Allowance::paginate(100) ; 
      return view('pages.allowance.index' , compact('allowance'));
    }
}
