<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $table = 'reimbursement';
    protected $fillable = [
        'issuer_id',
        'resolver_id',
        'transaction_date',
        'status',
        'type',
        'notes',
        'proof_file_url',
        'date_created'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function employee() {
    	return $this->belongsTo('App\Models\Employee');
    }
}
