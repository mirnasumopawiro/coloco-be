<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmploymentDetails;
use App\Models\PayrollInfo;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\EmploymentDetailsController;
use App\Http\Controllers\FinanceController;
use Exception, Hash;
use Carbon\Carbon; 

class EmployeeController extends Controller
{
    protected $employee;

    public function __construct(Employee $employee) {
        $this->employee = $employee;
    }

    public function register(Request $request) {
        $employee = [
            "id" => $request->employee['id'],
            "company_id" => $request->employee['company_id'],
            "email" => $request->employee['email'],
            "password" => Hash::make($request->employee['password']),
            "status" => 10,
            "registration_date" => Carbon::now()->toDateTimeString()
        ];

        try { 
            $employee = $this->employee->create($employee);

            $employeeProfileController = new EmployeeProfileController(new EmployeeProfile);
            $employmentDetailsController = new EmploymentDetailsController(new EmploymentDetails);
            $financeController = new FinanceController([
                'payroll_info' => new PayrollInfo
            ]);

            $employee_profile=  $employeeProfileController -> register($request->employee['id'], $request->employee_profile);
            $employment_details =  $employmentDetailsController -> register($request->employee['id'], $request->employment_details);
            $payroll_info =  $financeController -> addPayrollInfo($request->employee['id'], $request->payroll_info);

            $response = [
                "employee" => $employee,
                "employee_profile" => $employee_profile,
                "employment_details" => $employment_details,
                "payroll_info" => $payroll_info
            ];

            return response()->json($response,201);
        } catch(Exception $ex) {
            return response("Employee is not registered: $ex", 400);
        }
    }

    public function getByEmployeeId($employee_id) {
        $employeeProfileController = new EmployeeProfileController(new EmployeeProfile);
        $employmentDetailsController = new EmploymentDetailsController(new EmploymentDetails);

        $employee_profile=  $employeeProfileController -> getByEmployeeId($employee_id);
        $employment_details =  $employmentDetailsController -> getByEmployeeId($employee_id);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'employee_profile' => $employee_profile,
                'employment_details' => $employment_details
            ]
        ], 200);    
    }

    public function getEmployeeDirectory(Request $request) {   
        try {
            $employee = $this->employee
                ->where("employees.company_id", "=", "$request->company_id")
                ->where('employee_profile.first_name', 'LIKE', "%$request->name%")
                ->orWhere('employee_profile.last_name', 'LIKE', "%$request->name%")
                ->join(
                    "employee_profile", 
                    "employee_profile.employee_id", 
                    "=", 
                    "employees.id"
                )
                ->join(
                    "employment_details", 
                    "employment_details.employee_id", 
                    "=", 
                    "employees.id"
                )
                ->select(
                    "employees.id",
                    "employees.email",
                    "employee_profile.first_name",
                    "employee_profile.last_name",
                    "employee_profile.profile_picture_url",
                    "employment_details.department_id",
                    "employment_details.job_id"
                )
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'employee_list' => $employee
            ], 200);
        } catch (Exception $ex) {
            echo "EmploymentController > getEmployeeDirectory > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);   
        }
    }
}
