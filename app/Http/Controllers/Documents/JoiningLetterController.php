<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JoiningLetter;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class JoiningLetterController extends Controller
{
    public function index(): View
    {
        return view('documents.joining-letters.index');
    }

    public function getData()
    {
        $joiningLetter = JoiningLetter::query()
        ->with(['employee.department'])->get();

        $data = $joiningLetter->map(function ($letter) {
            $previewLetter = '<a href="'.route('documents.joining-letters.preview', $letter).'">Offer Letter</a>';
            $deleteLetter = '<a href="'.route('documents.joining-letters.delete', $letter).'">Delete Letter</a>';
            // $previewLetter = '';
            // $deleteLetter = '';

            return [
                $letter?->employee->employee_code,
                $letter?->employee->full_name,
                $letter?->employee->email,
                $letter?->employee->department?->name,
                $letter?->issued_date->format('Y-m-d'),
                $letter?->joining_date->format('Y-m-d'),
                '<div class="dropdown">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Actions
                     </button>
                    <ul class="dropdown-menu">
                        <li>
                            '.$previewLetter.'
                        </li>
                        <li>
                            '.$deleteLetter.'
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
            ->with(['department', 'designation'])
            ->orderBy('full_name')
            ->get();

        return view('documents.joining-letters.create', compact('employees'));
    }

    public function store($id): RedirectResponse
    {
        $employee = Employee::find($id)->first();
        $letter = JoiningLetter::query()->create([
            'employee_id' => $employee->id,
            'joining_date' => $employee->joining_date,
            'issued_date' => date('Y-m-d'),
            'salary' => $employee?->salary?->ctc,
            'file_path' => '',
        ]);

        return redirect()
            ->route('documents.joining-letters.preview', $letter)
            ->with('success', 'Appointment letter generated. Use Print to save or print.');
    }

    public function preview(JoiningLetter $joiningLetter): View
    {
        $joiningLetter->load(['employee.department', 'employee.designation']);

        return view('documents.print.joining-letter', [
            'letter' => $joiningLetter,
            'employee' => $joiningLetter->employee,
            'company' => config('company'),
        ]);
    }

    public function delete(JoiningLetter $joiningLetter)
    {
        try {
            // Delete the record from the database
            $joiningLetter->delete();

            return redirect()->back()->with('success', 'Joining letter deleted successfully.');
        } catch (\Exception $e) {
            // Handle cases where deletion might fail (e.g. database constraints)
            return redirect()->back()->with('error', 'Failed to delete the letter.');
        }
    }
}
