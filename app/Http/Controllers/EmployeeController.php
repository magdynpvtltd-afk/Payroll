<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SalaryStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::query()
            ->with(['department', 'latestSalaryStructure'])
            ->orderBy('full_name')
            ->paginate(20);

        return view('employees.index', compact('employees'));
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
        $salary = $employee->salaryStructures()->orderByDesc('effective_from')->first()
            ?? new SalaryStructure([
                'effective_from' => now()->startOfMonth()->toDateString(),
                'basic' => 0,
                'hra' => 0,
                'gross' => 0,
                'net' => 0,
            ]);

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

            $existing = $employee->salaryStructures()->orderByDesc('effective_from')->first();

            if ($existing) {
                $existing->update($data['salary']);
            } else {
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
        if ($employee !== null) {
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
            'status' => ['nullable', 'string', 'max:100'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'probation_end_date' => ['nullable', 'date'],
            'reporting_manager_id' => ['nullable', 'exists:employee,id'],
        ];

        $validated = $request->validate(array_merge($employeeRules, [
            'basic' => ['required', 'numeric', 'min:0'],
            'hra' => ['nullable', 'numeric', 'min:0'],
            'gross' => ['nullable', 'numeric', 'min:0'],
            'net' => ['nullable', 'numeric', 'min:0'],
            'ctc' => ['nullable', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
        ]));

        if (! empty($validated['reporting_manager_id']) && (int) $validated['reporting_manager_id'] === (int) ($request->route('employee')?->id)) {
            abort(422, 'Reporting manager cannot be the same employee.');
        }

        $basic = (float) $validated['basic'];
        $hra = (float) ($validated['hra'] ?? 0);
        $gross = isset($validated['gross']) && $validated['gross'] !== '' && $validated['gross'] !== null
            ? (float) $validated['gross']
            : $basic + $hra;
        $net = isset($validated['net']) && $validated['net'] !== '' && $validated['net'] !== null
            ? (float) $validated['net']
            : $gross;

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
            'basic' => $basic,
            'hra' => $hra,
            'gross' => $gross,
            'net' => $net,
            'ctc' => isset($validated['ctc']) && $validated['ctc'] !== '' ? (float) $validated['ctc'] : null,
            'effective_from' => $validated['effective_from'],
        ];

        return ['employee' => $employee, 'salary' => $salary];
    }


    

}
