@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
            <div class="alert" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="savings-tab" data-toggle="tab" href="#savings" role="tab"
                                aria-controls="savings" aria-selected="true">Savings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="vcard-tab" data-toggle="tab" href="#vcard" role="tab"
                                aria-controls="vcard" aria-selected="false">vCard</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="savings" role="tabpanel"
                            aria-labelledby="savings-tab">
                            <div class="p-3">
                                <div class="row mb-3 text-center">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-exchange-alt fa-2x"></i></button>
                                    </div>
                                </div>
                                @forelse($savings as $s)
                                <div class="col-md-12 row bg-info p-2">
                                    <div class="col-md-4 text-center text-white inline">
                                        <h4 class="mt-1">{{ $s->acc_no }}</h4>
                                    </div>
                                    <div class="col-md-4 text-center text-white inline">
                                        <h4 class="mt-1">{{ $s->balance }}</h4>
                                    </div>
                                    <div class="col-md-4 text-center"><button type="button"
                                            class="btn btn-success btn-sm rounded-0">History</button></div>
                                </div>
                                @empty
                                <div class="col-md-12 row bg-danger p-2 text-center">
                                    <div class="col-md-12">
                                        <h6 class="text-white">No savings account found.</h6>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vcard" role="tabpanel" aria-labelledby="vcard-tab">
                            <div class="p-3">
                                <div class="row mb-3 text-center">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-exchange-alt fa-2x"></i></button>
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-money-bill-alt fa-2x"></i></button>
                                        <button type="button" class="btn btn-primary btn-lg fa-lg"><i
                                                class="fas fa-qrcode fa-2x"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-12 row bg-info p-2">
                                    <div class="col-md-4 text-center text-white inline">
                                        <h5 class="mt-1">{{ $vcard->acc_no }}</h5>
                                    </div>
                                    <div class="col-md-4 text-center text-white inline">
                                        <h4 class="mt-1">{{ $vcard->balance }}</h4>
                                    </div>
                                    <div class="col-md-4 text-center"><button type="button"
                                            class="btn btn-success btn-sm rounded-0">History</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
