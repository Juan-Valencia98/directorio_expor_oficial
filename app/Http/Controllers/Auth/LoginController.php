<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Empresas;
use App\Models\EstadoEmpresas;
use App\Models\Rol;
use App\Models\Rubro;
use App\Models\GrupoRol;
use App\Models\GrupoEmpresaUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'estado' => 'activo'])) {
            $response = Http::post('http://api.taypi.senavex.gob.bo/api/pruebas/upea/v1/signin', [
                'ci' => $request->username,
                'password' => $request->password,
            ]);
            if ($response->status() == 200) {

                $dataEmp = Http::get('api.taypi.senavex.gob.bo/api/pruebas/upea/v1/signin/' . $response->object()->token);
                foreach ($dataEmp->object() as $data) {
                    $GrupRolId = GrupoRol::where([['id_user', Auth::id()],['id_rol',$data->id_rol]])->first();
                    if (!$GrupRolId) {
                        GrupoRol::create([
                            'id_user'=>Auth::id(),
                            'id_rol' => $data->id_rol
                        ]);
                    }
                    $GrupEmpUserId = GrupoEmpresaUser::where([['id_user', Auth::id()],['id_empresa',$data->id_empresa]])->first();
                    if(!$GrupEmpUserId) {
                        GrupoEmpresaUser::create([
                            'id_user' =>Auth::id(),
                            'id_empresa'=>$data->id_empresa
                        ]);
                    }
                    DB::table('empresas')
                    ->where('id_empresa', $data->id_empresa)
                    ->update([
                        'matricula'             => $data->matricula??'',
                        'ruex'                  => $data->ruex ?? 0,
                        'estado_ruex'           => $data->estado_ruex??0,
                        'id_estado_empresa'     => $data->id_estado_empresa,
                    ]);
                }
                return $this->sendLoginResponse($request);
            } else {
                return view('welcome');
            }
        } else {
            $response = Http::post('http://api.taypi.senavex.gob.bo/api/pruebas/upea/v1/signin', [
                'ci' => $request->username,
                'password' => $request->password,
            ]);
            if ($response->status() == 200) {

                $jwtToken = $response->object()->token;
                $tokenPart = explode(".", $jwtToken);
                $tokenDataPayload = $tokenPart[1];
                $arrayDataPayload = base64_decode($tokenDataPayload);
                $dataPerson = json_decode($arrayDataPayload);


                User::create([
                    'ci' => $dataPerson->ci,
                    'nombres' => $dataPerson->nombres,
                    'primerApellido' => $dataPerson->primerApellido,
                    'segundoApellido' => $dataPerson->segundoApellido,
                    'email' => $dataPerson->email,
                    'username' => $dataPerson->ci,
                    'password' => Hash::make($request->password),
                ]);

                $dataEmp = Http::get('api.taypi.senavex.gob.bo/api/pruebas/upea/v1/signin/' . $response->object()->token);

                foreach ($dataEmp->object() as $data) {
                    $RolId = Rol::where('id_rol', $data->id_rol)->first();
                    if (!$RolId) {
                        Rol::create([
                            'id_rol' => $data->id_rol,
                            'nombre_rol' => $data->rol
                        ]);
                    }
                    $EstEmpId = EstadoEmpresas::where('id_estado_empresa', $data->id_estado_empresa)->first();
                    if (!$EstEmpId) {
                        EstadoEmpresas::create([
                            'id_estado_empresa' => $data->id_estado_empresa,
                            'estado_empresa' => $data->estado_empresa,
                        ]);
                    }
                    $EmpId = Empresas::where('id_empresa', $data->id_empresa)->first();
                    if(!$EmpId){
                        Empresas::create([
                            'id_empresa'            => $data->id_empresa,
                            'razon_social_empresa'  => $data->razon_social??'',
                            'descripcion_empresa'   => '',
                            'nit'                   => $data->nit??'',
                            'matricula'             => $data->matricula??'',
                            'telefono'              => $data->telefono??0,
                            'celular_1'             => $data->celular??0,
                            'nombre_1'              => '',
                            'celular_2'             => 0,
                            'nombre_2'              => '',
                            'email'                 => $data->email??'',
                            'pag_web'               => $data->pag_web??'',
                            'ruex'                  => $data->ruex??'',
                            'estado_ruex'           => $data->estado_ruex??0,
                            'imagen_empresa'        => '',
                            'logo_empresa'          => '',
                            'id_estado_empresa'     => $data->id_estado_empresa,
                            'estado'                => 'inactivo',
                        ]);
                    }
                    
                }
                
                

                return view('welcome');
            } else {
                return $this->sendFailedLoginResponse($request, 'auth.failed_status');
            }
        }
    }
    public function username()
    {
        return 'username';
    }
}
