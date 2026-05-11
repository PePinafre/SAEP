<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome', 'marca', 'estoque'
    ];

    public function movimentos(){
        return $this->hasMany(Movimento::class);
    }
}
