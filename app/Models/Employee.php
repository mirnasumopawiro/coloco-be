<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Employee extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = 'employees';
    protected $fillable = ['id', 'company_id', 'email', 'password', 'status', 'registration_date'];
    protected $guarded = [];
    // protected $hidden = [/'password'];    
    public $timestamps = false;
    public $incrementing = false;

    public function company() {
    	return $this->belongsTo('App\Models\Company');
    }

    public function employmentDetails() {
    	return $this->hasOne('App\Models\EmploymentDetails');
    }

    public function employeeProfile() {
    	return $this->hasOne('App\Models\EmployeeProfile');
    }

    public function payrollInfo() {
    	return $this->hasOne('App\Models\PayrollInfo');
    }

    public function payroll() {
    	return $this->hasMany('App\Models\Payroll');
    }

    public function reimbursement() {
    	return $this->hasMany('App\Models\Reimbursement');
    }

    public function ticket() {
    	return $this->hasMany('App\Models\Ticket');
    }

    public function attendance() {
    	return $this->hasMany('App\Models\Attendance');
    }

    public function announcement() {
    	return $this->hasMany('App\Models\Announcement');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
