<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'App\Http\Controllers\AuthController@login');

Route::post('/company/register', 'App\Http\Controllers\CompanyController@register');
Route::post('/department/register', 'App\Http\Controllers\DepartmentController@register');
Route::post('/job/register', 'App\Http\Controllers\JobController@register');
Route::post('/employee/register', 'App\Http\Controllers\EmployeeController@register');

Route::post('/announcement/create', 'App\Http\Controllers\DashboardController@createAnnouncement');
Route::post('/payroll/create', 'App\Http\Controllers\FinanceController@upsertPayroll');
Route::post('/payroll/add-variable', 'App\Http\Controllers\FinanceController@addVariable');

Route::post('/attendance/upsert/{employee_id}', 'App\Http\Controllers\AttendanceController@upsertAttendanceByEmployeeId');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/change-password', 'App\Http\Controllers\AuthController@changePassword');

    Route::get('/profile/{employee_id}', 'App\Http\Controllers\EmployeeController@getByEmployeeId');
    Route::post('/request-edit-profile', 'App\Http\Controllers\EmployeeProfileController@requestEditProfile');
    Route::post('/employee-directory', 'App\Http\Controllers\EmployeeController@getEmployeeDirectory');
    Route::post('/attendance/{employee_id}', 'App\Http\Controllers\AttendanceController@getAttendanceByPeriod');
    
    Route::get('/announcements/{company_id}', 'App\Http\Controllers\DashboardController@getAnnouncementsByCompanyId');

    Route::post('/reimbursement', 'App\Http\Controllers\ReimbursementController@getByIssuerId');
    Route::get('/reimbursement/{id}', 'App\Http\Controllers\ReimbursementController@getById');
    Route::post('/reimbursement/create', 'App\Http\Controllers\ReimbursementController@create');
    Route::post('/reimbursement/update/{id}', 'App\Http\Controllers\ReimbursementController@updateById');

    Route::post('/ticket', 'App\Http\Controllers\TicketController@getByIssuerId');
    Route::get('/ticket/{id}', 'App\Http\Controllers\TicketController@getById');
    Route::post('/ticket/create', 'App\Http\Controllers\TicketController@create');
    Route::post('/ticket/update/{id}', 'App\Http\Controllers\TicketController@updateById');

    Route::get('/payroll-info/{employee_id}', 'App\Http\Controllers\FinanceController@getPayrollInfoByEmployeeId');
    Route::post('/payslip/{employee_id}', 'App\Http\Controllers\FinanceController@getPayslipByPeriod');

    Route::get('/form-options/{company_id}', 'App\Http\Controllers\FormOptionsController@getByCompanyId');
});