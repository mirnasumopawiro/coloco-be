<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentDetails extends Model
{
    protected $table = 'employment_details';
    protected $fillable = [
        'employee_id',
        'job_id',
        'department_id',
        'type',
        'join_date',
        'end_date'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function job() {
    	return $this->belongsTo('App\Models\Job');
    }

    public function department() {
    	return $this->belongsTo('App\Models\Department');
    }

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}
