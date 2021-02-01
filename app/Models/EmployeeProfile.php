<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profile';
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'phone_number',
        'profile_picture_url',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'marital_status',
        'religion',
        'current_address',
        'identity_type',
        'identity_number',
        'identity_exp_date',
        'identity_address'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}