<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobController;
use App\Models\Department;
use App\Models\Job;
use Exception;

class FormOptionsController extends Controller
{
    public function getByCompanyId($company_id) {
        $departmentController = new DepartmentController(new Department);
        $departments =  $departmentController -> getByCompanyId($company_id);

        if (!is_null($departments)) {
            foreach ($departments as $value) {
                $departmentList[$value['id']] = $value['name'];
            };
        }
     
        $jobController = new JobController(new Job);
        $jobs =  $jobController -> getByCompanyId($company_id);

        if (!is_null($jobs)) {
            foreach ($jobs as $value) {
                $jobList[ $value['id']] =  $value['name'];
            };
        }

        $formOptions = [
            "statusList" => [
                "0" => "Inactive",
                "5" => "On Progress",
                "9" => "Done",
                "10" => "Active"
            ],
            "urgencyList" => [
                "0" => "Low",
                "5" => "Medium",
                "10" => "High"
            ],
            "ticketTypeList" => [
                "1" => "Access Request",
                "2" => "Document Request",
                "3" => "Lost and Found",
                "99" => "Others"
            ],
            "reimbursementTypeList" => [
                "1" => "Medical Expense",
                "2" => "Business Expense",
                "3" => "Travel Expense",
                "99" => "Others"
            ],
            "departmentList" => $departmentList,
            "jobList" => $jobList
        ];
       
        return response() -> json($formOptions, 200);
    }
}
