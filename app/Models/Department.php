<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = ['company_id','name','status'];
    protected $guarded = [];
    public $timestamps = false;

    public function company() {
    	return $this->belongsTo('App\Models\Company');
    }

    public function employmentDetails() {
    	return $this->hasMany('App\Models\EmploymentDetails');
    }
}
