<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    protected $data;

    public function __construct(Job $data) {
        $this->data = $data;
    }

    public function register(Request $request) {
        $data = [
            "company_id" => $request->company_id,
            "name" => $request->name,
            "status" => $request->status,
        ];

        try { 
            $data = $this->data->create($data); 
            return response()->json($data,201);
        } catch(Exception $ex) {
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getByCompanyId(int $company_id) {
        try {
            $data = $this->data
                ->where("company_id", "=", "$company_id")
                ->where("status", "=", 10)
                ->select("id", "name")
                ->get();

            return $data;
        } catch (Exception $ex) {
            echo "JobController > getByCompanyId > $ex";
            return null;
        }
    }
}
