<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use DB, Exception;
use Carbon\Carbon; 

class TicketController extends Controller
{
    protected $ticket;

    public function __construct(Ticket $ticket) {
        $this->ticket = $ticket;
    }

    public function create(Request $request) {
        $ticket = [
            "issuer_id" => $request->issuer_id,
            "department_id" => $request->department_id,
            "type" => $request->type,
            "status" => 5,
            "urgency" => $request->urgency,
            "title" => $request->title,
            "notes" => $request->notes,
            "date_created" => Carbon::now()->toDateTimeString()
        ];

        try { 
            $ticket = $this->ticket->create($ticket); 
            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $ticket
            ], 201);
        } catch(Exception $ex) {
            echo "TicketController > register > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getByIssuerId(Request $request) {
        try { 
            $ticket = $this->ticket
                ->latest('tickets.date_created')
                ->where("issuer_id", "=", "$request->issuer_id")
                ->leftJoin(
                    "employee_profile", 
                    "employee_profile.employee_id", 
                    "=", 
                    "tickets.resolver_id"
                )
                ->join(
                    "departments", 
                    "departments.id", 
                    "=", 
                    "tickets.department_id"
                )
                ->select(
                    "tickets.id",
                    "tickets.date_created",
                    "tickets.type",
                    "tickets.status",
                    "tickets.urgency",
                    "departments.name",
                    DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name) AS resolver_name") 
                )
                ->get();

                return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $ticket
            ], 200);
        } catch(Exception $ex) {
            echo "TicketController > getByIssuerId > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getById($id) {
        try { 
            $ticket = $this->ticket
            ->where("tickets.id", "=", "$id")
            ->leftJoin(
                "employee_profile", 
                "employee_profile.employee_id", 
                "=", 
                "tickets.resolver_id"
            )
            ->join(
                "departments", 
                "departments.id", 
                "=", 
                "tickets.department_id"
            )
            ->select(
                "tickets.id",
                "tickets.date_created",
                "tickets.type",
                "tickets.status",
                "tickets.urgency",
                "tickets.title",
                "tickets.notes",
                "departments.name",
                DB::raw("CONCAT(employee_profile.first_name, ' ', employee_profile.last_name) AS resolver_name") 
            )
            ->first();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $ticket
            ], 200);
        } catch(Exception $ex) {
            echo "TicketController > getById > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function updateById(Request $request, $id) {
        try { 
            $ticket = $this->ticket->find($id)->update([
                "status" => $request->status,
                "resolver_id" => $request->resolver_id,
            ]);
            $ticket = $this->ticket->where("id", "=", $id)->get();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $ticket
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
