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
                            <div class="p-3 text-center">
                                <div class="row mb-3 text-center">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-exchange-alt fa-4x"></i><br><small>Send</small></button>
                                    </div>
                                </div>
                                @forelse($savings as $s)
                                <div class="row bg-info p-2 d-flex justify-content-center">
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
                                <div class="row bg-danger p-2 d-flex justify-content-center">
                                    <div class="col-md-12 text-center">
                                        <h6 class="text-white">No savings account found.</h6>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vcard" role="tabpanel" aria-labelledby="vcard-tab">
                            <div class="p-3 text-center">
                                <div class="row mb-3 text-center">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-exchange-alt fa-4x"></i><br><small>Send</small></button>
                                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                            data-target="#getPinModal" id="getPinBtn"
                                            data-card-no="{{ $vcard->acc_no }}"><i
                                                class="fas fa-money-check-alt fa-4x"></i><br><small>Pin</small></button>
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-qrcode fa-4x"></i><br><small>Scan</small></button>
                                        <button type="button" class="btn btn-primary btn-lg"><i
                                                class="fas fa-qrcode fa-4x"></i><br><small>Generate</small></button>
                                    </div>
                                </div>
                                <div class="row bg-info p-2 d-flex justify-content-center">
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

<!-- Modals -->
<div class="modal fade" id="getPinModal" tabindex="-1" role="dialog" aria-labelledby="getPinModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="getPinModalTitle">Cardless Withdrawal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-md-2 col-md-8">
                        <h5>Card Number: <span id="cardless-number"></span></h5>
                        <h5>Pin: <span id="pin"></span></h5>
                        <small>Pin can only be used for 10 minutes.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        (function ($) {
            $(document).on("click", "#getPinBtn", function () {
                context = $(this);
                card_no = $(this).data('card-no');
                $('#pin').text('Loading...');
                $('#cardless-number').text(card_no);
                $.get('/vcard/pin', function (data, status) {
                    pin = data.pin;
                    $('#pin').text(pin);
                });
            });
        })(jQuery);
    });

</script>
@endsection
