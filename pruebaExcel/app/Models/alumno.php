<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alumno extends Model
{
    protected $fillable = [
        'nombre',
        'apellidos',
        'edad',
        'FechaNacimiento',
    ];
}
