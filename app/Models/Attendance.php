<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = [
        'employee_id',
        'date',
        'schedule_in',
        'schedule_out',
        'clock_in',
        'clock_out'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}
