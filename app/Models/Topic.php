<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = [
        'header',
        'body',
        'section_id',
        'creator_id',
        'is_restricted',
        'is_pinned',
        'is_closed',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
