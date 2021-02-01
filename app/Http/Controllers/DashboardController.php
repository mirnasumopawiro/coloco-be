<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Exception, Hash;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    protected $announcement;

    public function __construct(Announcement $announcement) {
        $this->announcement = $announcement;
    }

    public function createAnnouncement(Request $request) {
        $announcement = [
            "employee_id" => $request->employee_id,
            "company_id" => $request->company_id,
            "subject" => $request->subject,
            "content" => $request->content,
            "status" => 10,
            "date_created" => Carbon::now()->toDateTimeString()
        ];

        try { 
            $announcement = $this->announcement->create($announcement); 
            return response()->json($announcement,201);
        } catch(Exception $ex) {
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getAnnouncementsByCompanyId($company_id) {
        try {
            $announcement = $this->announcement
                ->where("company_id", "=", $company_id)
                ->where("status", "=", 10)
                ->select("subject", "content", "date_created")
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'announcements' => $announcement
            ], 200); 
        } catch (Exception $ex) {
            echo "DashboardController > getAnnouncementsByCompanyId > $ex";
            
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }
}
