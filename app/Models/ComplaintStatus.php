<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintStatus extends Model
{
    protected $fillable = [
        'status',
        'complaint_logs_id',
    ];

    public function log() {
        return $this->belongsTo(ComplaintLog::class);
    }
}
