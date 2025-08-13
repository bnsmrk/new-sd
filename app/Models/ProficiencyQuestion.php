<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProficiencyQuestion extends Model
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
