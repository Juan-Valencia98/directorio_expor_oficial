@extends('home')
@section('title', 'Editar Producto')
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
                @if (session('status'))
                    <h6 class="alert alert-succes">{{session('status')}}</h6>
                @endif
                <div class="card">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <a href="javascript:void(0);" class="btn-close" data-bs-dismiss="modal"></a>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ URL('update-prod/' . Crypt::encryptString($empresas[0]->id_empresa)) }}"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf   
                            @method('PUT')
                            <input type="hidden" value="{{ $productoEdit->id_producto }}" id="id_producto" name="id_producto" />
                            <input type="hidden" value="{{$productoEdit->imagen_producto}}" id="imagen_producto" name="imagen_producto" />
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500">Rubro</label>
                                    <select class="default-select form-control focus:outline-none" name="id_rubro"
                                        id="id_rubro" required>
                                        <option value="{{$productoEdit->id_rubro}}" selected>{{$productoEdit->id_rubro}}</option>
                                        @foreach ($rubros as $rubro)
                                            <option value="{{ $rubro->id_rubro }}">
                                                {{Str::limit($rubro->nombre_rubro, 30, $end=' ...')}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500">Categoria</label>
                                    <select class="default-select form-control focus:outline-none"  id="limit-selection" multiple="multiple" name="id_categoria"
                                        id="id_categoria" required>
                                        <option value="{{$productoEdit->id_categoria}}" selected>{{$productoEdit->id_categoria}}</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id_categoria }}">
                                                {{Str::limit($categoria->descripcion_corta, 30, $end=' ...')}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-black font-w500">Nombre del
                                    Producto</label>
                                <input type="text" class="form-control focus:outline-none"
                                    placeholder="Introduzca el producto..." name="nombre_producto" id="nombre_producto" value="{{$productoEdit->nombre_producto}}"
                                    required>
                                <div class="invalid-feedback">
                                    por favor, ingrese producto.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-black font-w500">Descripcion</label>
                                <textarea class="form-control focus:outline-none" rows="4" name="descripcion_producto" id="descripcion_producto"
                                
                                    required>{{$productoEdit->descripcion_producto}}</textarea>
                            </div>
                            <div class="row">
                                
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500">Unidad de Medida</label>
                                    <select class="default-select form-control wide focus:outline-none focus:ring-2"
                                        name="id_unidad_medida" id="id_unidad_medida" required autocomplete="off">
                                        <option value="{{$productoEdit->id_unidad_medida}}" selected>{{$productoEdit->id_unidad_medida}}</option>
                                        @foreach ($medidas as $medida)
                                            <option value="{{ $medida->id_unidad_medida }}">
                                                {{ $medida->nombre_unidad_medida }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500">Estrella</label>
                                    <select class="default-select form-control wide focus:outline-none focus:ring-2"
                                        name="estrella" id="estrella" required>
                                        <option value="{{$productoEdit->estrella}}" selected>{{$productoEdit->estrella}}</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Precio</label>
                                    <input type="text"
                                        class="form-control focus:outline-none focus:ring-2 input-number-dec"
                                        name="precio_producto" id="precio_producto" value="{{$productoEdit->precio_producto}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500">Moneda</label>
                                    <select class="default-select form-control wide focus:outline-none" name="id_moneda"
                                        id="id_moneda"  required>
                                        <option value="{{$productoEdit->id_moneda}}" selected>{{$productoEdit->id_moneda}}</option>
                                        @foreach ($monedas as $moneda)
                                            <option value="{{ $moneda->id_moneda }}">
                                                {{ $moneda->nombre_moneda }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Cantidad Disponible</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2 input-number"
                                        name="cantidad_disponible" id="cantidad_disponible" value="{{$productoEdit->cantidad_disponible}}" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="text-black font-w500 ">Codigo Nandina</label>
                                    <input type="text" class="form-control focus:outline-none focus:ring-2 input-number"
                                        name="codigo_nandina" id="codigo_nandina" value="{{$productoEdit->codigo_nandina}}" required>
                                </div>
                                

                            </div>
                            <div class="mb-3">
                                <label class="text-black font-w500">Imagen</label>
                                <div class="input-group">
                                    <div class="form-file ">
                                        <input accept="image/png,image/jpeg,image/jpg" type="file"
                                            class="form-file-input form-control focus:outline-none input-image-2"
                                            name="imagen_producto" id="imagen_producto"  >
                                            
                                    </div>
                                    
                                </div>
                                <p class="text-image-2"> </p>
                                <img src="{{$productoEdit->imagen_producto}}" alt="Imagen">
                            </div>
                            <div class="mb-3 row">
                                <div class="col-lg-8 ms-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
