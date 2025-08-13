<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question',
        'type',
        'options',
        'answer_key',
        'points',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
