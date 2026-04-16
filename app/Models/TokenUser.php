<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenUser extends Model
{
    protected $table = 'token_users';

    protected $fillable = [
        'token',
        'user_id',
        'valido_ate',
    ];

    protected $casts = [
        'valido_ate' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}

