<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintLog extends Model
{
    protected $fillable = [
        'update_date',
        'ComplaintStaus',
        'note_content',
        'actor_type',
        'complaint_id',
        'user_id'
    ];
    public function complaint() {
        return $this->belongsTo(Complaint::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function status() {
        return $this->hasMany(ComplaintStatus::class);
    }
}
