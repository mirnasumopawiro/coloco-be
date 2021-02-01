<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payroll';
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'month',
        'year'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }

    public function payrollCalculation() {
    	return $this->hasMany('App\Models\PayrollCalculation');
    }
}
