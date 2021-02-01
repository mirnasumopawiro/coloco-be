<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\Company;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeProfileController;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash, Exception;

class AuthController extends Controller
{
    protected $employee;

    public function __construct(Employee $employee){
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'changePassword']]);
        $this->employee = $employee;
    }
    
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token, $request->email);
        }

        return response()->json([
            'status' => 401,
            'message' => 'unauthorized'
        ], 401);
    }

    public function logout() {
        $this->guard()->logout();
        return response()->json([
            'message' => 'success',
            'status' => 200
        ], 200);
    }

    protected function respondWithToken($token, $email) {
        $employeeProfileController = new EmployeeProfileController(new EmployeeProfile);
        $companyController = new CompanyController(new Company);

        $employee_profile =  $employeeProfileController -> getByEmail($email);
        $company_details =  $companyController -> getById($employee_profile['company_id']);

        return response()->json([
            'message' => 'success',
            'status' => 200,
            'access_token' => $token,
            'token_type' => 'bearer',
            'data' => [
                'employee_profile' => $employee_profile,
                'company_details' => $company_details
            ]
        ], 200);
    }

    public function changePassword(Request $request){
        try {
            $employee = $this->employee->where("id", "=", $request->id)->first();
            if (Hash::check($request->current_password, $employee['password'])) {
                $employee = $this->employee->find($request->id)->update([
                    "password" => Hash::make($request->new_password)
                ]);
    
                return response()->json([
                    'status' => 200,
                    'message' => 'success'
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Current Password is not correct. Try again?'
                ], 400);
            }
        } catch (Exception $ex) {
            echo "AuthController > changePassword > $ex";

            return response()->json([
                'status' => 500,
                'message' => $ex
            ], 500);
        }
    }

    public function guard() {
        return Auth::guard('api');
    }
}
