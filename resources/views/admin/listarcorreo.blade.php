@extends('home')
@section('title', 'Lista Correo')
@section('content')

    <!--**********************************
                        Content body start
                    ***********************************-->
    <div class="container-fluid">
        <div class="row page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)">Email</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Inbox</a>
                </li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="email-list mt-3">
                            <div class="message">
                                <div>
                                    <div class="d-flex message-single">
                                        <div class="ps-1 align-self-center">
                                            <div class="form-check custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkbox21" />
                                                <label class="form-check-label" for="checkbox21"></label>
                                            </div>
                                        </div>
                                        <div class="ms-2">
                                            <button class="border-0 bg-transparent align-middle p-0">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ URL('one-correo-admin/'.Crypt::encryptString(1)) }}" class="col-mail col-mail-2">
                                            
                                        <div class="subject">
                                            Even the all-powerful Pointing has no control
                                            about the blind texts it is an almost
                                            unorthographic life One day however a small line
                                            of blind text by the name of
                                        </div>
                                        <div class="date">11:49 am</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
                            Content body end
                        ***********************************-->
@endsection
