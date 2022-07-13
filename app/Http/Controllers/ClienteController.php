<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Rubro;
use App\Models\Categoria;
use App\Models\Monedas;
use App\Models\UnidadMedidas;
use App\Models\Empresas;
use App\Models\GrupoRubro;
use Illuminate\Support\Facades\DB;
class ClienteController extends Controller
{
    public function index(){
        return view('vistas.inicio');
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function listaProductos(){
        $productos = DB::table('productos')
        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
        ->select('productos.*', 'monedas.*', 'empresas.*')
        ->where([
            ['productos.estado', 'activo'],
            ['empresas.estado', 'activo']
        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();


        return view ('vistas.productos',[
            'productos' => $productos
        ]);
    }
    public function oneProducto($id){
        $detProducto = DB::table('productos')
                    ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                    ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                    ->select('productos.*', 'monedas.*', 'empresas.*')
                    ->where([
                        ['productos.estado', 'activo'],
                        ['empresas.estado', 'activo'],
                        ['productos.id_producto',$id]
                    ])->orderByDesc('productos.updated_at','empresas.updated_at')->first();
        $productos = DB::table('productos')
                        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                        ->select('productos.*', 'monedas.*', 'empresas.*')
                        ->where([
                            ['productos.estado', 'activo'],
                            ['empresas.estado', 'activo'],
                            ['productos.id_rubro',$detProducto->id_rubro]
                        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();
        return view('vistas.detalleProducto',[
            'detProducto' => $detProducto,
            'productos' => $productos
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function listaEmpresas(){
        $empresas = DB::table('empresas')
        ->where([
            ['estado', 'activo']
        ])->orderByDesc('updated_at')->get();


        return view ('vistas.empresas',[
            'empresas' => $empresas
        ]);
    }
    public function oneEmpresa($id){
        $detEmpresa = DB::table('empresas')
                    ->where([
                        ['estado', 'activo'],
                        ['id_empresa',$id]
                    ])->first();
        $productos = DB::table('productos')
                        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                        ->select('productos.*', 'monedas.*', 'empresas.*')
                        ->where([
                            ['productos.estado', 'activo'],
                            ['empresas.estado', 'activo'],
                            ['productos.id_empresa',$id]
                        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();
        return view('vistas.detalleEmpresa',[
            'detEmpresa' => $detEmpresa,
            'productos' => $productos
        ]);
    }
}