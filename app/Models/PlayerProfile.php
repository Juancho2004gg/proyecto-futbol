<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'positions',
        'preferred_foot',
        'current_clubs',
        'previous_clubs',
        'leagues_seasons',
        'stats',
        'achievements',
        'about',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
