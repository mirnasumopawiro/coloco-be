<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reimbursement;
use DB, Exception;
use Carbon\Carbon; 

class ReimbursementController extends Controller
{
    protected $reimbursement;

    public function __construct(Reimbursement $reimbursement) {
        $this->reimbursement = $reimbursement;
    }

    public function create(Request $request) {
        $reimbursement = [
            "issuer_id" => $request->issuer_id,
            "transaction_date" => $request->transaction_date,
            "type" => $request->type,
            "status" => 5,
            "notes" => $request->notes,
            "proof_file_url" => $request->proof_file_url,
            "date_created" => Carbon::now()->toDateTimeString()
        ];

        try { 
            $reimbursement = $this->reimbursement->create($reimbursement); 
            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $reimbursement
            ], 201);
        } catch(Exception $ex) {
            echo "ReimbursementController > register > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getByIssuerId(Request $request) {
        try { 
            $reimbursement = $this->reimbursement
                ->latest('reimbursement.date_created')
                ->where("issuer_id", "=", "$request->issuer_id")
                ->leftJoin(
                    "employee_profile", 
                    "employee_profile.employee_id", 
                    "=", 
                    "reimbursement.resolver_id"
                )
                ->select(
                    "reimbursement.id",
                    "reimbursement.transaction_date",
                    "reimbursement.date_created",
                    "reimbursement.type",
                    "reimbursement.status",
                    DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name) AS resolver_name") 
                )
                ->get();

                return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $reimbursement
            ], 200);
        } catch(Exception $ex) {
            echo "ReimbursementController > getByIssuerId > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getById($id) {
        try { 
            $reimbursement = $this->reimbursement
                ->where("reimbursement.id", "=", "$id")
                ->leftJoin(
                    "employee_profile", 
                    "employee_profile.employee_id", 
                    "=", 
                    "reimbursement.resolver_id"
                )
                ->select(
                    "reimbursement.id",
                    "reimbursement.transaction_date",
                    "reimbursement.date_created",
                    "reimbursement.type",
                    "reimbursement.status",
                    "reimbursement.proof_file_url",
                    "reimbursement.notes",
                    DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name) AS resolver_name") 
                )
                ->first();

                return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $reimbursement
            ], 200);
        } catch(Exception $ex) {
            echo "ReimbursementController > getById > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function updateById(Request $request, $id) {
        try { 
            $reimbursement = $this->reimbursement->find($id)->update([
                "status" => $request->status,
                "resolver_id" => $request->resolver_id,
            ]);
            $reimbursement = $this->reimbursement->where("id", "=", $id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $reimbursement
            ], 200);
        } catch(Exception $ex) {
            echo "TicketController > getById > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }
}
