<?php

use App\Http\Controllers\Settings\AllowanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Documents\DocumentHubController;
use App\Http\Controllers\Documents\ExperienceLetterController;
use App\Http\Controllers\Documents\JoiningLetterController;
use App\Http\Controllers\Documents\OfferLetterController;
use App\Http\Controllers\Documents\SalarySlipController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\Department;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/department' , [Department::class , 'index'])->name('department.index');

    // all document routes
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentHubController::class, 'index'])->name('index');

        Route::get('/offer-letters', [OfferLetterController::class, 'index'])->name('offer-letters.index');
        Route::get('/offer-letters/create', [OfferLetterController::class, 'create'])->name('offer-letters.create');
        Route::post('/offer-letters', [OfferLetterController::class, 'store'])->name('offer-letters.store');
        Route::get('/offer-letters/{offerLetter}/preview', [OfferLetterController::class, 'preview'])->name('offer-letters.preview');
        Route::get('/offer-letters/{id}/deleter', [OfferLetterController::class, 'delete'])->name('offer-letters.delete');
        Route::get('/offer-letter/{id}/custom' , [OfferLetterController::class , 'custom'])->name('offer-letter.custom');

        Route::get('/appointment-letters', [JoiningLetterController::class, 'index'])->name('joining-letters.index');
        Route::get('/appointment-letters/create', [JoiningLetterController::class, 'create'])->name('joining-letters.create');
        Route::post('/appointment-letters', [JoiningLetterController::class, 'store'])->name('joining-letters.store');
        Route::get('/appointment-letters/{joiningLetter}/preview', [JoiningLetterController::class, 'preview'])->name('joining-letters.preview');

        Route::get('/experience-letters', [ExperienceLetterController::class, 'index'])->name('experience-letters.index');
        Route::get('/experience-letters/create', [ExperienceLetterController::class, 'create'])->name('experience-letters.create');
        Route::post('/experience-letters', [ExperienceLetterController::class, 'store'])->name('experience-letters.store');
        Route::get('/experience-letters/{experienceLetter}/preview', [ExperienceLetterController::class, 'preview'])->name('experience-letters.preview');

        Route::get('/salary-slips', [SalarySlipController::class, 'index'])->name('salary-slips.index');
        Route::get('/salary-slips/create', [SalarySlipController::class, 'create'])->name('salary-slips.create');
        Route::post('/salary-slips', [SalarySlipController::class, 'store'])->name('salary-slips.store');
        Route::get('/salary-slips/{salarySlip}/preview', [SalarySlipController::class, 'preview'])->name('salary-slips.preview');
    });

    // allowance routes
    Route::prefix('allowance')->name('allowance.')->group(function(){
        Route::post('/edit' , [AllowanceController::class , 'edit'])->name('edit');
    });

    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| UI preview (no database, no auth) — remove or protect in production
|--------------------------------------------------------------------------
*/
Route::get('/preview/dashboard', function () {
    return view('dashboard.index', [
        'employeeCount' => 42,
        'departmentCount' => 6,
        'attendanceToday' => 18,
    ]);
})->name('preview.dashboard');