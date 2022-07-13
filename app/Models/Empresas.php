<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;



    protected $fillable = [
        'id_empresa',
        'razon_social_empresa',
        'descripcion_empresa',
        'nit',
        'matricula',
        'telefono',
        'celular_1',
        'nombre_1',
        'celular_2',
        'nombre_2',
        'email',
        'pag_web',
        'ruex',
        'estado_ruex',
        'imagen_empresa',
        'logo_empresa',
        'id_estado_empresa',
        'estado',
    ];
}
