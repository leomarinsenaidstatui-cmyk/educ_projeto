<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'nascimento',
        'genero',
    ];

    protected $hidden = [
        'senha',
    ];

    public function ebooks()
    {
        return $this->hasMany(Ebook::class, 'user_id');
    }
}

