<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentDetails;
use Exception;

class EmploymentDetailsController extends Controller
{
    protected $employment_details;

    public function __construct(EmploymentDetails $employment_details) {
        $this->employment_details = $employment_details;
    }

    public function register($employee_id, $data) {
        $employment_details = [
            "employee_id" => $employee_id,
            "job_id" => $data['job_id'],
            "department_id" => $data['department_id'],
            "type" => $data['type'],
            "join_date" => $data['join_date'],
            "end_date" => $data['end_date']
        ];

        try { 
            $employment_details = $this->employment_details->create($employment_details); 
            return $employment_details;
        } catch(Exception $ex) {
            echo "EmploymentDetailsController > register > $ex";
            return null;
        }
    }

    public function getByEmployeeId($employee_id) {
        try {
            $employment_details = $this->employment_details
                ->where("employee_id", "=", "$employee_id")
                ->first();

            return $employment_details;
        } catch (Exception $ex) {
            echo "EmploymentDetailsController > getByEmployeeId > $ex";
            return null;
        }
    }
}
