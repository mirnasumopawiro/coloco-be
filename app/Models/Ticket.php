<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        'issuer_id',
        'department_id',
        'resolver_id',
        'status',
        'title',
        'type',
        'notes',
        'urgency',
        'date_created'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}
