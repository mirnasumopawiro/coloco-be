<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollInfo;
use App\Models\PayrollCalculation;
use Exception;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected $payroll;
    protected $payroll_info;
    protected $payroll_calculation;

    public function __construct($obj = null){
        if($obj){
            foreach (((object)$obj) as $key => $value) {
                if(isset($value) && in_array($key, array_keys(get_object_vars($this)))){
                    $this->$key = $value;
                }
            }
        } else {
            $this->payroll = new Payroll;
            $this->payroll_info = new PayrollInfo;
            $this->payroll_calculation = new PayrollCalculation;
        }
    }

    public function upsertPayroll(Request $request) {
        try { 
            $payroll = $this->payroll
                ->updateOrCreate(
                    [
                        'month' => $request->month,
                        'year' => $request->year,
                        'employee_id' => $request->employee_id
                    ],
                    [
                        'employee_id' => $request->employee_id,
                        'basic_salary' => $request->basic_salary,
                        'month' => $request->month,
                        'year' => $request->year
                    ]
                );

            $payroll = $this->payroll
                ->where("id", "=", $payroll->id)
                ->get();

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $payroll
            ], 201); 
        } catch(Exception $ex) {
            echo "FinanceController > createPayroll > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function addVariable(Request $request) {
        $payroll_calculation = [
            'payroll_id' => $request->payroll_id,
            'type' => $request->type,
            'name' => $request->name,
            'amount' => $request->amount
        ];

        try { 
            $payroll_calculation = $this->payroll_calculation->create($payroll_calculation); 

            return response()->json([
                'status' => 201,
                'message' => 'success',
                'data' => $payroll_calculation
            ], 201); 
        } catch(Exception $ex) {
            echo "FinanceController > addVariable > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function addPayrollInfo($employee_id, $data) {
        $payroll_info = [
            'employee_id' => $employee_id,
            'account_name' => $data['account_name'],
            'account_no' => $data['account_no'],
            'npwp' => $data['npwp'],
            'bpjs_kesehatan_no' => $data['bpjs_kesehatan_no'],
            'bpjs_ketenagakerjaan_no' => $data['bpjs_ketenagakerjaan_no']
        ];

        try { 
            $payroll_info = $this->payroll_info->create($payroll_info); 
            return $payroll_info;
        } catch(Exception $ex) {
            echo "FinanceController > addPayrollInfo > $ex";
            return null;
        }
    }

    public function getPayrollInfoByEmployeeId($employee_id) {
        try {
            $payroll_info = $this->payroll_info
                ->where("employee_id", "=", $employee_id)
                ->first();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $payroll_info
            ], 200); 
        } catch (Exception $ex) {
            echo "FinanceController > getPayrollInfoByEmployeeId > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getPayslipByPeriod(Request $request, $employee_id) {
        try {
            $payroll = $this->payroll
                ->where("employee_id", "=", $employee_id)
                ->where("year", "=", $request->year)
                ->where("month", "=", $request->month)
                ->select("id", "month", "year", "basic_salary")
                ->first();

            if (is_null($payroll)) {
                return response()->json([
                    'status' => 200,
                    'message' => 'success',
                    'data' => null
                ], 200); 
            }

            $benefits = $this->getVariablesByPayrollIdAndType($payroll->id, "Benefit");
            $deductions = $this->getVariablesByPayrollIdAndType($payroll->id, "Deduction");
            $allowances = $this->getVariablesByPayrollIdAndType($payroll->id, "Allowance");

            $take_home_pay = $payroll->basic_salary + $allowances["total_amount"] - $deductions["total_amount"];

            $res = [
                "month" => $payroll->month,
                "year" => $payroll->year,
                "basic_salary" => $payroll->basic_salary,
                "take_home_pay" => $take_home_pay,
                "benefits" => $benefits,
                "allowances" => $allowances,
                "deductions" => $deductions

            ];

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $res
            ], 200); 
        } catch (Exception $ex) {
            echo "FinanceController > getPayslipByPeriod > $ex";
            return response()->json([
                'status' => 400,
                'message' => $ex
            ], 400);
        }
    }

    public function getVariablesByPayrollIdAndType($payroll_id, $type) {
        try {
            $payroll_calculation = $this->payroll_calculation
                ->where("payroll_id", "=", $payroll_id)
                ->where("type", "=", $type)
                ->select("name", "amount")
                ->get();
            
            $total = 0;
            foreach ($payroll_calculation as $p) {
                $total += $p->amount;
            }
    
            $res = [
                "total_amount" => $total,
                "data" => $payroll_calculation
            ];

            return $res;
        } catch (Exception $ex) {
            echo "FinanceController > getVariablesByPayrollIdAndType > $ex";
            return null;
        }
    }
}
