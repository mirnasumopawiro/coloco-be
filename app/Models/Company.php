<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['name','icon_url'];
    protected $guarded = [];
    public $timestamps = false;

    public function employees() {
    	return $this->hasMany('App\Models\Employee');
    }

    public function departments() {
    	return $this->hasMany('App\Models\Department');
    }

    public function jobs() {
    	return $this->hasMany('App\Models\Job');
    }

    public function announcements() {
    	return $this->hasMany('App\Models\Announcement');
    }
}
