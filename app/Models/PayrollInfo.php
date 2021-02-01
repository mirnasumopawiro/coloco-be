<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollInfo extends Model
{
    protected $table = 'employee_payment_details';
    protected $fillable = [
        'employee_id',
        'account_name',
        'account_no',
        'npwp',
        'bpjs_kesehatan_no',
        'bpjs_ketenagakerjaan_no'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}
