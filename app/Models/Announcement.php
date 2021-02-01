<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcement';
    protected $fillable = [
        'employee_id',
        'company_id',
        'subject',
        'content',
        'status',
        'date_created'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }

    public function company() {
    	return $this->belongsTo('App\Models\Company');
    }
}
