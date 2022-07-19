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
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(){
        $productos = DB::table('productos')->get();
        $fechaActual = Carbon::now();
        $anioActual = substr($fechaActual,0,4);
        $mesActual = substr($fechaActual,5,2);
        $diaActual = substr($fechaActual,8,2);
        foreach ($productos as $producto) {
            $anio = substr($producto->created_at,0,4);
            $mes = substr($producto->created_at,5,2);
            $dia = substr($producto->created_at,8,2);
            if ($anio != $anioActual && $mes == $mesActual){
                DB::table('productos')
                ->where('id_producto',$producto->id_producto) 
                ->update([
                    'estado'           => 'eliminado',
                ]);
            }
        }
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
        $idDes = Crypt::decryptString($id);
        $detProducto = DB::table('productos')
                    ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                    ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                    ->select('productos.*', 'monedas.*', 'empresas.*')
                    ->where([
                        ['productos.estado', 'activo'],
                        ['empresas.estado', 'activo'],
                        ['productos.id_producto',$idDes]
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
            'productos' => $productos,
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
        $idDes = Crypt::decryptString($id);
        $detEmpresa = DB::table('empresas')
                    ->where([
                        ['estado', 'activo'],
                        ['id_empresa',$idDes]
                    ])->first();
        $productos = DB::table('productos')
                        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                        ->select('productos.*', 'monedas.*', 'empresas.*')
                        ->where([
                            ['productos.estado', 'activo'],
                            ['empresas.estado', 'activo'],
                            ['productos.id_empresa',$idDes]
                        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();
        return view('vistas.detalleEmpresa',[
            'detEmpresa' => $detEmpresa,
            'productos' => $productos
        ]);
    }
    public function listaProductosEmpresa($id){
        $idDes = Crypt::decryptString($id);
        $productos = DB::table('productos')
        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
        ->select('productos.*', 'monedas.*', 'empresas.*')
        ->where([
            ['productos.estado', 'activo'],
            ['empresas.estado', 'activo'],
            ['empresas.id_empresa',$idDes]
        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();


        return view ('vistas.productos_emp_rub',[
            'productos' => $productos,
            'titulo' => 'Empresas'
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////    

    public function listaRubros(){
        $empresas = DB::table('empresas')
        ->where([
            ['estado', 'activo']
        ])->orderByDesc('updated_at')->get();

        $rubros = DB::table('rubro')
        ->where([
            ['estado', 'activo']
        ])->orderByDesc('updated_at')->paginate(3, ['*'], 'page', null);
        // $rubros = DB::table('productos')
        //                 ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
        //                 ->join('rubro', 'rubros.id_rubro', '=', 'productos.id_rubro')
        //                 ->select('rubro.*')
        //                 ->where([
        //                     ['productos.estado', 'activo'],
        //                     ['empresas.estado', 'activo'],
        //                     ['rubro.estado','activo'],
        //                 ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();

        return view ('vistas.listarubro',[
            'empresas' => $empresas,
            'rubros' => $rubros
        ]);
    }
    public function oneRubro($id){
        $idDes = Crypt::decryptString($id);
        $detEmpresa = DB::table('empresas')
                    ->where([
                        ['estado', 'activo'],
                        ['id_empresa',$idDes]
                    ])->first();
        $productos = DB::table('productos')
                        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
                        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
                        ->select('productos.*', 'monedas.*', 'empresas.*')
                        ->where([
                            ['productos.estado', 'activo'],
                            ['empresas.estado', 'activo'],
                            ['productos.id_empresa',$idDes]
                        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();
        return view('vistas.detalleEmpresa',[
            'detEmpresa' => $detEmpresa,
            'productos' => $productos
        ]);
    }
    public function listaProductosRubro($id){
        $idDes = Crypt::decryptString($id);
        $productos = DB::table('productos')
        ->join('empresas', 'empresas.id_empresa', '=', 'productos.id_empresa')
        ->join('monedas', 'monedas.id_moneda', '=', 'productos.id_moneda')
        ->join('rubro', 'rubro.id_rubro', '=', 'productos.id_rubro')
        ->select('productos.*', 'monedas.*', 'empresas.*')
        ->where([
            ['productos.estado', 'activo'],
            ['empresas.estado', 'activo'],
            ['productos.id_rubro',$idDes]
        ])->orderByDesc('productos.updated_at','empresas.updated_at')->get();

        return view ('vistas.productos_emp_rub',[
            'productos' => $productos,
            'titulo' => 'Rubros'
        ]);
    }
}
