<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'creator_id',
        'topic_id',
        'body',
        'is_restricted',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
