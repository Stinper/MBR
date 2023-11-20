<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'creator_id',
        'name',
        'is_restricted',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
