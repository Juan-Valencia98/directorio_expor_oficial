@extends('home')
@section('title', 'Editar Empresas')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Empresa</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Empresa</h5>
                        <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal"></a>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ URL('update-empr/' . Crypt::encryptString($empresasEdit->id_empresa)) }}"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label class="text-black font-w500">Nombre de la
                                    Empresa</label>
                                <input type="text" class="form-control focus:outline-none"
                                    name="razon_social_empresa" id="razon_social_empresa" value="{{$empresasEdit->razon_social_empresa}}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="text-black font-w500">Descripcion</label>
                                <textarea class="form-control" rows="4" name="descripcion_empresa" 
                                    id="descripcion_empresa" >{{$empresasEdit->descripcion_empresa}}</textarea>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Nit</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2 input-number"
                                        name="nit" id="nit"  value="{{$empresasEdit->nit}}" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Matricula</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2 input-number"
                                        name="matricula" id="matricula"  value="{{$empresasEdit->matricula}}" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Telefono</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2" 
                                        name="telefono" id="telefono"  value="{{$empresasEdit->telefono}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Correo Electronico</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2" 
                                        name="email" id="email"  value="{{$empresasEdit->email}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Persona Nombre 1</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2" 
                                        name="nombre_1" id="nombre_1"  value="{{$empresasEdit->nombre_1}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Celular 1</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2"
                                        name="celular_1" id="celular_1"  value="{{$empresasEdit->celular_1}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Persona Nombre 2</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2"
                                        name="nombre_2" id="nombre_2"  value="{{$empresasEdit->nombre_2}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Celular 2</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2"
                                        name="celular_2" id="celular_2"  value="{{$empresasEdit->celular_2}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Pagina Web</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2" name="pag_web"
                                        id="pag_web" value="{{$empresasEdit->pag_web}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Ruex</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2 input-number" name="ruex"
                                        id="ruex" value="{{$empresasEdit->ruex}}" readonly>
                                </div>
                                
    
                            </div>
                            <div class="mb-3">
                                <label class="text-black font-w500">Imagen</label>
                                <div class="input-group">
                                    <div class="form-file ">
                                        <input accept="image/png,image/jpeg,image/jpg" type="file"
                                            class="form-file-input form-control focus:outline-none "
                                            name="imagen_empresa" id="imagen_empresa" value="{{$empresasEdit->imagen_empresa}}" required>
                                    </div>
                                </div>
                                <p class="text-image-2"> </p>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-8 ms-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <button type="button" class="btn btn-primary center">Create</button>
                            </div> --}}
    
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
