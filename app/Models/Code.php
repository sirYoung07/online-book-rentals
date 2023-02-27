<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Code extends Model
{
    use HasFactory;
    protected $fillable = [
        'token'
    ];

    protected 
    $casts = [
        'token_expires_at' => 'datetime',
    ];

    public function codeable(): MorphTo {
        return $this->morphTo();
    }

}   
