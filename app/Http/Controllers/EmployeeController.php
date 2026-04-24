<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\JoiningLetter;
use App\Models\OfferLetter;
use App\Models\SalaryStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index(): view
    {
        return view('employees.index');
    }

    public function getData()
    {
        $employees = Employee::query()
            ->with(['department', 'salary', 'designation', 'offerLetters'])->get();
        $data = $employees->map(function ($employee) {
            $letters = OfferLetter::query()
                ->with(['employee.department', 'employee.designation', 'employee.salaryStructures'])
                ->where('employee_id', $employee->id)->first()
            ;
            $offerLetterLink = $letters
                ? '<a href="' . route('documents.offer-letters.preview', $letters->id) . '">Offer Letter</a>'
                : '<a href="' . route('documents.offer-letters.store' , $employee->id) . '">Offer Letter</a>';
            
            $joining_letter = JoiningLetter::query()->where('employee_id' , $employee->id)->first();
            
            $joining_letter_link = $joining_letter ? '<a href="' .route('documents.joining-letters.preview' , $joining_letter) . '">Confirmation Letter</a>' : '<a href="' . route('documents.joining-letters.store' , $employee->id) . '">Confirmation Letter</a>' ;
            
            
            return [
                $employee->employee_code,
                $employee->full_name,
                $employee->email,
                $employee?->department?->name,
                $employee?->designation?->title,
                $employee?->salary?->ctc,
                $employee?->salary?->variable,
                $employee?->status,
                '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        options
                     </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a  href="' . route('employees.edit', $employee->id) . '"><i class="fa fa-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            ' . $offerLetterLink . '
                        </li>
                        <li>
                            '.$joining_letter_link.'
                        </li>
                    </ul>
                </div>'
            ];
        });

        return DataTables::of($data)
            ->rawColumns([8])
            ->make(true);
    }

    public function create(): View
    {
        return view('employees.form', [
            'employee' => new Employee,
            'salary' => new SalaryStructure(['effective_from' => now()->startOfMonth()->toDateString()]),
            'departments' => Department::query()->orderBy('name')->get(),
            'designations' => Designation::query()->orderBy('title')->get(),
            'managers' => Employee::query()->orderBy('full_name')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedEmployeeAndSalary($request);
        DB::transaction(function () use ($data) {
            $employee = Employee::query()->create($data['employee']);
            SalaryStructure::query()->create(array_merge(
                ['employee_id' => $employee->id],
                $data['salary']
            ));
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee and salary structure saved.');
    }

    public function edit(Employee $employee): View
    {
        $salary = $employee->salaryStructures()->first();
        return view('employees.form', [
            'employee' => $employee,
            'salary' => $salary,
            'departments' => Department::query()->orderBy('name')->get(),
            'designations' => Designation::query()->orderBy('title')->get(),
            'managers' => Employee::query()->where('id', '!=', $employee->id)->orderBy('full_name')->get(),
            'mode' => 'edit',

        ]);
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $data = $this->validatedEmployeeAndSalary($request, $employee);

        DB::transaction(function () use ($data, $employee) {
            $employee->update($data['employee']);
            $existing = $employee->salaryStructures()->first();
            if ($existing)
            {
                $existing->update($data['salary']);
            } else
            {
                SalaryStructure::query()->create(array_merge(
                    ['employee_id' => $employee->id],
                    $data['salary']
                ));
            }
        });

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated.');
    }

    /**
     * @return array{employee: array<string, mixed>, salary: array<string, mixed>}
     */
    protected function validatedEmployeeAndSalary(Request $request, ?Employee $employee = null): array
    {
        $codeRule = Rule::unique('employee', 'employee_code');
        if ($employee !== null)
        {
            $codeRule->ignore($employee->id);
        }

        $employeeRules = [
            'employee_code' => ['required', 'string', 'max:191', $codeRule],
            'full_name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:M,F,O'],
            'phone' => ['required', 'integer'],
            'email' => ['required', 'email', 'max:255'],
            'joining_date' => ['nullable', 'date'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'status' => ['string', 'not_in:null'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'probation_end_date' => ['nullable', 'date'],
            'reporting_manager_id' => ['nullable', 'exists:employee,id'],
        ];

        $validated = $request->validate(array_merge($employeeRules, [
            'fixed' => ['numeric', 'required'],
            'variable' => ['numeric', 'required']
        ]));

        if (!empty($validated['reporting_manager_id']) && (int) $validated['reporting_manager_id'] === (int) ($request->route('employee')?->id))
        {
            abort(422, 'Reporting manager cannot be the same employee.');
        }

        $employee = [
            'employee_code' => $validated['employee_code'],
            'full_name' => $validated['full_name'],
            'dob' => $validated['dob'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'joining_date' => $validated['joining_date'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'status' => $validated['status'] ?? null,
            'designation_id' => $validated['designation_id'] ?? null,
            'reporting_manager_id' => $validated['reporting_manager_id'] ?? null,
            'probation_end_date' => $validated['probation_end_date'] ?? null,
        ];

        $salary = [
            'ctc' => $validated['fixed'],
            'variable' => $validated['variable']
        ];

        return ['employee' => $employee, 'salary' => $salary];
    }




}
