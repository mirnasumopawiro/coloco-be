<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeProfile;
use Exception;

class EmployeeProfileController extends Controller
{
    protected $employee_profile;

    public function __construct(EmployeeProfile $employee_profile) {
        $this->employee_profile = $employee_profile;
    }

    public function register($employee_id, $data) {
        $employee_profile = [
            "employee_id" => $employee_id,
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "phone_number" => $data['phone_number'],
            "profile_picture_url" => $data['profile_picture_url'],
            "place_of_birth" => $data['place_of_birth'],
            "date_of_birth" => $data['date_of_birth'],
            "gender" => $data['gender'],
            "marital_status" => $data['marital_status'],
            "religion" => $data['religion'],
            "current_address" => $data['current_address'],
            "identity_type" => $data['identity_type'],
            "identity_number" => $data['identity_number'],
            "identity_exp_date" => $data['identity_exp_date'],
            "identity_address" => $data['identity_address']
        ];

        try { 
            $employee_profile = $this->employee_profile->create($employee_profile); 
            return $employee_profile;
        } catch(Exception $ex) {
            echo "EmployeeProfileController > register > $ex";
            return null;
        }
    }

    public function getByEmail($email) {
        try {
            $employee_profile = $this->employee_profile
                ->where("email", "=", "$email")
                ->join(
                    "employees", 
                    "employees.id", 
                    "=", 
                    "employee_profile.employee_id"
                )
                ->select(
                    "employee_profile.employee_id",
                    "employee_profile.first_name",
                    "employee_profile.last_name",
                    "employee_profile.profile_picture_url",
                    "employees.company_id"
                )
                ->first();

            return $employee_profile;
        } catch (Exception $ex) {
            echo "EmployeeProfileController > getByEmail > $ex";
            return null;
        }
    }

    public function getByEmployeeId($employee_id) {
        try {
            $employee_profile = $this->employee_profile
                ->where("employee_id", "=", "$employee_id")
                ->first();

            return $employee_profile;
        } catch (Exception $ex) {
            echo "EmployeeProfileController > getByEmployeeId > $ex";
            return null;
        }
    }

    public function requestEditProfile(Request $request) {
        // TO-DO for next implementation
        // add JSON request string to HR/Admin database
        // in HR/Admin dashboard, they can view the changes made by employee
        // and accept or reject the changesß
        
        return response()->json([
            'status' => 200,
            'message' => 'success'
        ], 200);
    }
}
