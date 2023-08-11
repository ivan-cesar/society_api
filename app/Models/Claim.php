<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'description',
    ];

    // Relation avec l'utilisateur (auteur de la réclamation)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
