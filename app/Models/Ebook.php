<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $table = 'ebooks';

    protected $fillable = [
        'user_id',
        'titulo',
        'autor',
        'editora',
        'data_publicacao',
        'categoria',
        'resumo',
        'conteudo',
        'arquivo_pdf',
        'publico',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
        'publico' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
