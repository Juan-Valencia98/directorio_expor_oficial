<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Productos;
use App\Models\Rubro;
use App\Models\Categoria;
use App\Models\Monedas;
use App\Models\UnidadMedidas;
use App\Models\Empresas;
use App\Models\GrupoRubro;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $empresa = Empresa::where('id_user', Auth::id(),)->get(); 
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();

        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();
        return view('admin.inicio', [
            'empresas' => $empresas,
            'roles'=>$rol
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    public function listProd($id)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();



        $productos = Productos::where('id_empresa', $id)->get();
        $rubros = Rubro::all();
        $categorias = Categoria::all();
        $monedas = Monedas::all();
        $medidas = UnidadMedidas::all();
        return view('admin.listproducto', [
            'empresas' => $empresas,
            'roles' => $rol,
            'productos' => $productos,
            'rubros' => $rubros,
            'categorias' => $categorias,
            'monedas' => $monedas,
            'medidas' => $medidas
        ]);
    }
    public function agreProd($id, Request $data)
    {

        $Emp = Empresas::where('id_empresa', $id)->first();

        $direcion = '';
        if ($data->hasFile('imagen_producto')) {
            $file = $data->file('imagen_producto');
            $WithoutCar = $Emp->razon_social_empresa;
            $path = preg_replace('/[0-9\@\.\;\" "]+/', '', $WithoutCar);
            $endPath = 'storage/images/producto/' . $path . '/';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $data->file('imagen_producto')->move($endPath, $fileName);
            $direcion = $endPath . $fileName;
        }
        Productos::create([
            'cantidad_disponible'   => $data->cantidad_disponible ?? '',
            'nombre_producto'       => $data->nombre_producto,
            'imagen_producto'       => URL($direcion),
            'descripcion_producto'  => $data->descripcion_producto,
            'precio_producto'       => $data->precio_producto,
            'codigo_nandina'        => $data->codigo_nandina,
            'estrella'              => $data->estrella,
            'estado_producto'       => 'normal',
            'id_rubro'              => $data->id_rubro,
            'id_categoria'          => $data->id_categoria,
            'id_unidad_medida'      => $data->id_unidad_medida,
            'id_moneda'             => $data->id_moneda,
            'estado'                => 'inactivo',
            'id_empresa'            => $id,
            'id_user'               => Auth::id(),
        ]);
        return redirect()->route('home');
    }
    public function oneProd($id)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();


        $productoEdit = Productos::where('id_producto', $id)->first();

        $rubros = Rubro::all();
        $categorias = Categoria::all();
        $monedas = Monedas::all();
        $medidas = UnidadMedidas::all();
        return view('admin.editproducto', [
            'empresas' => $empresas,
            'roles' => $rol,
            'productoEdit' => $productoEdit,
            'rubros' => $rubros,
            'categorias' => $categorias,
            'monedas' => $monedas,
            'medidas' => $medidas
        ]);
    }
    public function eliminarProd($id)
    {
        Productos::where('id_producto', $id)->update(['estado' => 'eliminado']);
        return redirect()->route('home');
    }
    public function observadoProd($id)
    {
        Productos::where('id_producto', $id)->update(['estado' => 'observado']);
        return redirect()->route('home');
    }
    public function publicarProd($id)
    {

        Productos::where('id_producto', $id)->update(['estado' => 'activo']);
        return redirect()->route('home');
    }
    public function listProdT()
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();




        $productosInactivos = Productos::all()->where('estado', 'inactivo');
        $productosActivos = Productos::all()->where('estado', 'activo');
        $productosObservados = Productos::all()->where('estado', 'observado');
        $productosTodos = Productos::all();
        return view('admin.listaproductot', [
            'empresas' => $empresas,
            'roles' => $rol,
            'prodInactivos' => $productosInactivos,
            'prodActivos' => $productosActivos,
            'prodObservados' => $productosObservados,
            'prodTodos' => $productosTodos
        ]);
    }
    public function updateProd($id, Request $data)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();

    
        $direcion = '';
        if ($data->hasFile('imagen_producto')) {
            $file = $data->file('imagen_producto');
            $WithoutCar = $empresas[0]->razon_social_empresa;
            $path = preg_replace('/[0-9\@\.\;\" "]+/', '', $WithoutCar);
            $endPath = 'storage/images/producto/' . $path . '/';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $data->file('imagen_producto')->move($endPath, $fileName);
            $direcion = $endPath . $fileName;
        }
        Productos::where('id_producto', $data->id_producto)->update([
            'cantidad_disponible'   => $data->cantidad_disponible ?? '',
            'nombre_producto'       => $data->nombre_producto,
            'imagen_producto'       => URL($direcion),
            'descripcion_producto'  => $data->descripcion_producto,
            'precio_producto'       => $data->precio_producto,
            'codigo_nandina'        => $data->codigo_nandina,
            'estrella'              => $data->estrella,
            'estado_producto'       => 'normal',
            'id_rubro'              => $data->id_rubro,
            'id_categoria'          => $data->id_categoria,
            'id_unidad_medida'      => $data->id_unidad_medida,
            'id_moneda'             => $data->id_moneda,
            'id_empresa'            => $id,
            'estado'                => 'inactivo',
            'id_user'               => Auth::id(),
        ]);
        return redirect()->route('home');
    }

    /////////////////////////////////   //////////////////////////////  //////////////////////////////////
    public function listgrupRubEmp($id){
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();

        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();


        $gruporubros = DB::table('grupo_rubro')
            ->join('empresas', 'grupo_rubro.id_empresa', '=', 'empresas.id_empresa')
            ->join('rubro', 'grupo_rubro.id_rubro', '=', 'rubro.id_rubro')
            ->select('grupo_rubro.id','empresas.id_empresa','empresas.razon_social_empresa','rubro.id_rubro','rubro.nombre_rubro','grupo_rubro.created_at')
            ->where('grupo_rubro.id_empresa',$id)->get();
        $rubros = Rubro::all();
        return view('admin.gruporubro', [
            'empresas' => $empresas,
            'roles' => $rol,
            'rubros' => $rubros,
            'gruporubros'   => $gruporubros,
            'id_empresa'    => $id,
        ]);
    }
    public function agreGrupRubro(Request $data){
        GrupoRubro::create([
            'id_empresa'   => $data->id_empresa,
            'id_rubro'     => $data->id_rubro,
        ]);    
        return redirect()->route('home');
    }
    public function eliminarGrupoRubro($id){
        GrupoRubro::where('id', $id)->delete();
        return redirect()->route('home');
    }

    ////////////////////////////////////////////////////////////////
    public function listEmp()
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();

        $empresaInactivos = Empresas::all()->where('estado', 'inactivo');
        $empresaActivos = Empresas::all()->where('estado', 'activo');
        $empresaTodos = Empresas::all();
        return view('admin.listempresa', [
            'empresas' => $empresas,
            'roles' => $rol,
            'empInactivos' => $empresaInactivos,
            'empActivos' => $empresaActivos,
            'empTodos' => $empresaTodos
        ]);
    }
    public function oneEmp($id)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();



        $empresasEdit = Empresas::where('id_empresa', $id)->first();


        return view('admin.onempresa', [
            'empresas' => $empresas,
            'roles' => $rol,
            'empresasEdit' => $empresasEdit,
        ]);
    }
    public function updateEmp($id, Request $data)
    {
        $direcion = '';
        if ($data->hasFile('imagen_empresa')) {
            $file = $data->file('imagen_empresa');
            $WithoutCar = $data->razon_social_empresa;
            $path = preg_replace('/[0-9\@\.\;\" "]+/', '', $WithoutCar);
            $endPath = 'storage/images/empresas/' . $path . '/';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $data->file('imagen_empresa')->move($endPath, $fileName);
            $direcion = $endPath . $fileName;
        }

        Empresas::where('id_empresa', $id)->update([
            'razon_social_empresa'  => $data->razon_social_empresa ?? '',
            'descripcion_empresa'   => $data->descripcion_empresa,
            'nit'                   => $data->nit ?? '',
            'matricula'             => $data->matricula ?? '',
            'telefono'              => $data->telefono ?? 0,
            'celular_1'             => $data->celular_1 ?? 0,
            'nombre_1'              => $data->nombre_1,
            'celular_2'             => $data->celular_2,
            'nombre_2'              => $data->nombre_2,
            'email'                 => $data->email ?? '',
            'pag_web'               => $data->pag_web ?? '',
            'ruex'                  => $data->ruex ?? '',
            'estado_ruex'           => $data->estado_ruex ?? 0,
            'imagen_empresa'        => URL($direcion),
            'logo_empresa'          => '',
            'estado'                => 'inactivo',
        ]);
        return redirect()->route('home');
    }

    public function eliminarEmp($id)
    {
        Empresas::where('id_empresa', $id)->update(['estado' => 'inactivo']);
        return redirect()->route('home');
    }
    public function publicarEmp($id)
    {
        Empresas::where('id_empresa', $id)->update(['estado' => 'activo']);
        return redirect()->route('home');
    }



    ////////////////////////////////////////////////////////////////
    public function listRubro()
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();



        
        $rubros = Rubro::all();
        return view('admin.listrubro', [
            'empresas' => $empresas,
            'roles' => $rol,
            'rubros' => $rubros,
        ]);
    }
    public function oneRubro($id)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();



        $rubroEdit = Rubro::where('id_rubro', $id)->first();


        return view('admin.onerubro', [
            'empresas' => $empresas,
            'roles' => $rol,
            'rubroEdit' => $rubroEdit,
        ]);
    }
    public function updateRubro($id, Request $data)
    {
        $direcion = '';
        if ($data->hasFile('imagen_rubro')) {
            $file = $data->file('imagen_rubro');
            $endPath = 'storage/images/rubros/';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $uploadSuccess = $data->file('imagen_rubro')->move($endPath, $fileName);
            $direcion = $endPath . $fileName;
        }
        Rubro::where('id_rubro', $id)->update([
            'nombre_rubro'  => $data->nombre_rubro,
            'abreviacion_rubro'   => $data->abreviacion_rubro,
            'imagen_rubro'        => URL($direcion),
            'estado'                => 'activo',
        ]);
        return redirect()->route('home');
    }

    public function eliminarRubro($id)
    {
        Rubro::where('id_rubro', $id)->update(['estado' => 'eliminado']);
        return redirect()->route('home');
    }
    public function estadoRubro($id)
    {
        $rubro = Rubro::where('id_rubro', $id)->first();
        if ($rubro->estado == 'activo') {
            Rubro::where('id_rubro', $id)->update(['estado' => 'inactivo']);
        } else {
            Rubro::where('id_rubro', $id)->update(['estado' => 'activo']);
        }
        return redirect()->route('home');
    }



    ////////////////////////////////////////////////////////////////
    public function listCategoria()
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();


        $categorias = Categoria::all();
        return view('admin.listcategoria', [
            'empresas' => $empresas,
            'roles' => $rol,
            'categorias' => $categorias,
        ]);
    }
    public function oneCategoria($id)
    {
        $empresas = DB::table('grupo_empresa_user')
            ->join('empresas', 'grupo_empresa_user.id_empresa', '=', 'empresas.id_empresa')
            ->join('users', 'grupo_empresa_user.id_user', '=', 'users.id')
            ->select('empresas.*')
            ->where('id_user', Auth::id(),)->get();
        $rol = DB::table('grupo_rol')
            ->join('users', 'grupo_rol.id_user', '=', 'users.id')
            ->join('rol', 'grupo_rol.id_rol', '=', 'rol.id_rol')
            ->select('rol.id_rol','rol.nombre_rol')
            ->where('id_user', Auth::id(),)->get();


        $categoriaEdit = Categoria::where('id_categoria', $id)->first();


        return view('admin.onecategoria', [
            'empresas' => $empresas,
            'roles' => $rol,
            'categoriaEdit' => $categoriaEdit,
        ]);
    }
    public function updateCategoria($id, Request $data)
    {
        Categoria::where('id_categoria', $id)->update([
            'nombre_rubro'  => $data->nombre_rubro,
            'abreviacion_rubro'   => $data->abreviacion_rubro,
            'id_rubro'        => $data->id_rubro,
        ]);
        return redirect()->route('home');
    }

    public function eliminarCategoria($id)
    {
        Categoria::where('id_categoria', $id)->update(['estado' => 'eliminado']);
        return redirect()->route('home');
    }
    public function estadoCategoria($id)
    {
        $categoria = Categoria::where('id_categoria', $id)->first();
        if ($categoria->estado == 'activo') {
            Categoria::where('id_categoria', $id)->update(['estado' => 'inactivo']);
        } else {
            Categoria::where('id_categoria', $id)->update(['estado' => 'activo']);
        }
        return redirect()->route('home');
    }
}
