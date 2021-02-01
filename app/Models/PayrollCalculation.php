<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollCalculation extends Model
{
    protected $table = 'payroll_calculation';
    protected $fillable = [
        'payroll_id',
        'type',
        'name',
        'amount'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function payroll() {
    	return $this->belongsTo('App\Models\Payroll');
    }
}
