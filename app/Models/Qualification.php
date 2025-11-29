<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    protected $table = 'qualifications';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'qualification',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
