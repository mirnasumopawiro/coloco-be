<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Exception;
use Carbon\Carbon; 

class AttendanceController extends Controller
{
    protected $attendance;

    public function __construct(Attendance $attendance) {
        $this->attendance = $attendance;
    }
    
    public function mapAttendanceRecord($start_of_month, $end_of_month, $attendance) {
        $total_days = $end_of_month->diffInDays($start_of_month);
        $res = [];
    
        for ($i = 0; $i <= $total_days; $i++) {
            $record = [
                "date" => $start_of_month->format('Y-m-d'),
                "schedule_in" => null,
                "schedule_out" => null,
                "clock_in" => null,
                "clock_out" => null,
                "overtime" => null
            ];

            foreach ($attendance as &$value) {
                if ($start_of_month == $value->date) {
                    $record = [
                        "date" => $start_of_month->format('Y-m-d'),
                        "schedule_in" => $value->schedule_in,
                        "schedule_out" => $value->schedule_out,
                        "clock_in" => $value->clock_in,
                        "clock_out" => $value->clock_out,
                        "overtime" => $this->calculateOvertime($value->schedule_out, $value->clock_out)
                    ];
                }   
            }

            $res[] = $record;
            $start_of_month->addDay();
        }    

        return $res;
    }

    public function calculateOvertime($schedule_out, $clock_out) {
        $schedule_out = Carbon::parse($schedule_out);
        $clock_out = Carbon::parse($clock_out);
    
        $overtime = null;

        if ($schedule_out < $clock_out) {
            $overtime =  $schedule_out->diff($clock_out)->format('%H:%I');
        }

        return $overtime;
    }

    public function getAttendanceByPeriod(Request $request, $employee_id) {

        $start_of_month = Carbon::create($request->year, $request->month)->startOfMonth();
        $end_of_month = Carbon::create($request->year, $request->month)->endOfMonth();
        try {
            $attendance = $this->attendance
                ->whereBetween("date", [$start_of_month, $end_of_month])
                ->where("employee_id", "=", $employee_id)
                ->orderBy("date", "ASC")
                ->get();

            $attendance = $this->mapAttendanceRecord($start_of_month, $end_of_month, $attendance);

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $attendance
            ], 200);     
        } catch (Exception $ex) {
            echo "AttendanceController > getAttendanceByPeriod > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);    
        }
    }

    public function upsertAttendanceByEmployeeId(Request $request, $employee_id) {
        try {
            $attendance = $this->attendance
                ->updateOrCreate(
                    [
                        'date' => $request->date,
                        'employee_id' => $employee_id
                    ],
                    [
                        'schedule_in' => $request->schedule_in,
                        'schedule_out' => $request->schedule_out,
                        'clock_in' => $request->clock_in,
                        'clock_out' => $request->clock_out
                    ]
                );
        
            $attendance = $this->attendance
                ->where("id", "=", $attendance->id)
                ->get();

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $attendance
            ], 201);     
        } catch (Exception $ex) {
            echo "AttendanceController > upsertAttendanceByEmployeeId > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);    
        }
    }
}
