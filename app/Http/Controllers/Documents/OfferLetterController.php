<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OfferLetter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Salary;
use App\Models\settings\Allowance;
use Yajra\DataTables\Facades\DataTables;

class OfferLetterController extends Controller
{
    private const SESSION_PREFIX = 'offer_letter_extra_';

    public function index(): View
    {
        return view('documents.offer-letters.index');
    }

    public function getData()
    {
        $letters = OfferLetter::query()
            ->with(['employee.department', 'employee.designation', 'employee.salaryStructures'])->get();

        $data = $letters->map(function ($letter) {
            return [
                $letter->employee->employee_code,
                $letter->employee->full_name,
                $letter->employee->department->name,
                $letter->employee->designation->title,
                $letter->employee->salary->ctc,
                $letter->employee->salary->variable,
                '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        options
                     </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a  href="' . route('documents.offer-letters.delete', $letter->id) . '"><i class="fa fa-trash"></i> Delete
                            </a>
                        </li>
                        <li>
                           <a  href="' . route('documents.offer-letters.preview', $letter) . '"><i class="fa fa-eye"></i> Preview
                            </a>
                        </li>
                       
                    </ul>
                </div>',
            ];
        });

        return DataTables::of($data)
            ->rawColumns([6])
            ->make(true);

    }

    public function create(): View
    {
        $employees = Employee::query()
            ->with(['department', 'designation', 'latestSalaryStructure'])
            ->orderBy('full_name')
            ->get();

        $designations = Designation::all();
        $allowance = Allowance::all()->toArray();
        $allowance_mapings = [];
        foreach ($allowance as $row)
        {
            $allowance_mapings[$row['type']] = $row['value'];
        }

        return view('documents.offer-letters.create', compact('employees', 'designations', 'allowance_mapings'));
    }

    public function store($employee_id): RedirectResponse
    {
        $salary = Employee::find($employee_id)->with(['salary'])->first();

        $letter = OfferLetter::query()->create([
            'employee_id' => $employee_id,
            'offered_salary' => $salary?->salary?->ctc,
            'file_path' => '',
            'issued_date' => date('Y-m-d'),
            'accepted' => false,
        ]);

        return redirect()
            ->route('documents.offer-letters.preview', $letter)
            ->with('success', 'Offer letter generated. Use Print to save or print.');
    }

    public function preview(OfferLetter $offerLetter): View
    {

        $allowance = Allowance::all()->toArray();

        $allowance_mapings = [];
        $pf_esi = null;
        $gross_pay = 0;


        $offerLetter->load(['employee.department', 'employee.designation', 'employee.salary']);
        $employee = $offerLetter->employee;
        $ctc = $employee->salary->ctc;
        foreach ($allowance as $row)
        {
            $values = str_replace('ctc', $ctc, $row['value']);
            if ($row['type'] == 'pf_esi')
            {
                $pf_esi = eval (" return $values;");
                continue;
            }
            $allowance_mapings[$row['type']] = eval ("return $values;");
            $gross_pay += eval ("return $values;");
        }

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
                'allowance' => $allowance_mapings,
                'pf_esi' => $pf_esi,
                'total_ctc' => $ctc,
                'gross_pay' => $gross_pay
            ],
        ]);
    }


    public function delete($id)
    {
        OfferLetter::find($id)?->delete();
        return back();
    }


}
