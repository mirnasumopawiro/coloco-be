<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    protected $data;

    public function __construct(Company $data) {
        $this->data = $data;
    }

    public function register(Request $request) {
        $data = [
            "name" => $request->name,
            "icon_url" => $request->icon_url,
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

    public function getById(int $company_id) {
        try {
            $data = $this->data
                ->where("id", "=", "$company_id")
                ->select("id", "name", "icon_url")
                ->first();

            return $data;
        } catch (Exception $ex) {
            echo "CompanyController > getById > $ex";
            return null;
        }
    }
}
