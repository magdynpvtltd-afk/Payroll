<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OfferLetter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Department ;
use App\Models\Designation;
use App\Models\settings\Allowance ;

class OfferLetterController extends Controller
{
    private const SESSION_PREFIX = 'offer_letter_extra_';

    public function index(): View
    {
        $letters = OfferLetter::query()
            ->with(['employee.department', 'employee.designation' , 'employee.salaryStructures'])
            ->orderByDesc('issued_date')
            ->orderByDesc('id')
            ->paginate(15);
        return view('documents.offer-letters.index', compact('letters'));
    }

    public function create(): View
    {
        $employees = Employee::query()
            ->with(['department', 'designation', 'latestSalaryStructure'])
            ->orderBy('full_name')
            ->get();

        $designations = Designation::all();
        $allowance = Allowance::all()->toArray() ;
        $allowance_mapings = [] ; 
        foreach ($allowance as $row) {
            $allowance_mapings[$row['type']] =$row['value'];
        } 

        return view('documents.offer-letters.create', compact('employees' , 'designations' , 'allowance_mapings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employee,id'],
            'offered_salary' => ['nullable', 'numeric', 'min:0'],
            'issued_date' => ['required', 'date'],
            'compensation_line' => ['nullable', 'string', 'max:500'],
            'conveyance' => ['nullable', 'numeric', 'min:0'],
            'vehicle_maintenance' => ['nullable', 'numeric', 'min:0'],
            'production_incentive' => ['nullable', 'numeric', 'min:0'],
            'pf_esi' => ['nullable', 'numeric', 'min:0'],
            'signatory_name' => ['nullable', 'string', 'max:120'],
        ]);

      
        $letter = OfferLetter::query()->create([
            'employee_id' => $data['employee_id'],
            'offered_salary' => $data['offered_salary'] ?? null,
            'file_path' => '',
            'issued_date' => $data['issued_date'],
            'accepted' => false,
        ]);

        $request->session()->put(self::SESSION_PREFIX.$letter->id, [
            'compensation_line' => $data['compensation_line'] ?? null,
            'conveyance' => $data['conveyance'] ?? null,
            'vehicle_maintenance' => $data['vehicle_maintenance'] ?? null,
            'production_incentive' => $data['production_incentive'] ?? null,
            'pf_esi' => $data['pf_esi'] ?? null,
            'signatory_name' => $data['signatory_name'] ?? null,
        ]);

        return redirect()
            ->route('documents.offer-letters.preview', $letter)
            ->with('success', 'Offer letter generated. Use Print to save or print.');
    }

    public function preview(OfferLetter $offerLetter): View
    {
        $allowance = Allowance::all()->toArray() ;

        $allowance_mapings = [] ; 

        foreach ($allowance as $row) {
            $allowance_mapings[$row['type']] =$row['value'];
        } 
   

        $offerLetter->load(['employee.department', 'employee.designation', 'employee.latestSalaryStructure']);
        $employee = $offerLetter->employee;
        $ctc = $employee?->latestSalaryStructure?->ctc ;

        $conveyance_string = str_replace('ctc' , $ctc , $allowance_mapings['conveyance'] ); 
        $conveyance = eval("return $conveyance_string;");

        $vehicle_maintenance_string = str_replace('ctc' , $ctc , $allowance_mapings['vehicle_maintenance'] ); 
        $vehicle_maintenance = eval("return $vehicle_maintenance_string;");

        $production_incentive_string = str_replace('ctc' , $ctc , $allowance_mapings['production_incentive'] ); 
        $production_incentive = eval("return $production_incentive_string;");

        $pf_esi_string = str_replace('ctc' , $ctc , $allowance_mapings['pf_esi'] ); 
        $pf_esi = eval("return $pf_esi_string;");

        $basic_stirng =  str_replace('ctc' , $ctc , $allowance_mapings['Basic'] ); 
        $basic = eval("return $basic_stirng;");

        $hra_stirng =  str_replace('ctc' , $ctc , $allowance_mapings['Hra'] ); 
        $hra = eval("return $hra_stirng;");
        $grossPay = $basic + $hra   + $vehicle_maintenance + $production_incentive  + $conveyance;
        

        

        $footerLine = config('company.footer_address')
            ?? trim(implode(', ', array_filter([
                config('company.name'),
                config('company.address_line1'),
                config('company.address_line2'),
            ])));

        return view('documents.print.offer-letter', [
            'letter' => $offerLetter,
            'employee' => $employee,
            'company' => config('company'),
            'footerLine' => $footerLine,
            'annex' => [
                'basic' => $basic,
                'hra' => $hra,
                'conveyance' => $conveyance,
                'vehicle_maintenance' => $vehicle_maintenance,
                'production_incentive' => $production_incentive,
                'gross_pay' => $grossPay,
                'pf_esi' => $pf_esi,
                'total_ctc' => $ctc,
            ],
        ]);
    }


    public function delete($id){
        OfferLetter::find($id)?->delete();
        return back();
    }

    public function custom($id){
        dd($id);
    }
}
