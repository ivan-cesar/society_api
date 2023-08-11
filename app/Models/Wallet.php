<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'balance',
    ];

    // Relation avec l'utilisateur (propriétaire du portefeuille)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
