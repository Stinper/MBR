<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'recipient_id',
        'message'
    ];

    protected $dates = ['send_at'];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
