<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Ban extends Model
{
    protected $table = 'bans';

    protected $fillable = [
        'user_id',
        'admin_id',
        'reason',
        'start_date',
        'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
