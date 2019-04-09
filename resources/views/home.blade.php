@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                                        <button type="button" class="btn btn-primary btn-lg m-1"><i
                                                class="fas fa-exchange-alt fa-4x" data-toggle="modal"
                                                data-target="#sendSavingsModal"
                                                id="sendSavingsBtn"></i><br><small>Send</small>
                                        </button>
                                    </div>
                                </div>
                                @forelse($savings as $s)
                                <div class="row bg-info p-2 d-flex justify-content-center">
                                    <div class="col-md-4 text-center text-white inline">
                                        <h4 class="mt-1"><span onclick="copyText(this)"
                                                style="cursor:copy">{{ $s->acc_no }}</span></h4>
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
                                        <button type="button" class="btn btn-primary btn-lg m-1"><i
                                                class="fas fa-exchange-alt fa-4x" data-toggle="modal"
                                                data-target="#sendVcardModal"
                                                id="sendVcardBtn"></i><br><small>Send</small>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg m-1" data-toggle="modal"
                                            data-target="#getPinModal" id="getPinBtn"
                                            data-card-no="{{ $vcard->acc_no }}"><i
                                                class="fas fa-money-check-alt fa-4x"></i><br><small>Pin</small>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg m-1"><i
                                                class="fas fa-qrcode fa-4x" data-toggle="modal"
                                                data-target="#scanQRModal" id="scanQRBtn"></i><br><small>Scan</small>
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg m-1"><i
                                                class="fas fa-qrcode fa-4x" data-toggle="modal"
                                                data-target="#generateQRModal"></i><br><small>Generate</small>
                                        </button>
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
                                            class="btn btn-success btn-sm rounded-0"
                                            id="getVcardHistoryBtn">History</button></div>
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
                        <h5>Card Number: {{ $vcard->acc_no }}</h5>
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

<div class="modal fade" id="scanQRModal" tabindex="-1" role="dialog" aria-labelledby="scanQRModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQRModalTitle">Scan QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row d-flex justify-content-center">
                    <div id="loadingMessage">🎥 Unable to access video stream (please make sure you have a webcam
                        enabled)</div>
                    <canvas id="canvas" hidden></canvas>
                    <div id="output" hidden>
                        <div id="outputMessage">No QR code detected.</div>
                        <div hidden><b>Data:</b> <span id="outputData"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendSavingsModal" tabindex="-1" role="dialog" aria-labelledby="sendSavingsModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendSavingsModalTitle">Send (Savings)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-md-1 col-md-10">
                        <form action="">
                            <div class="form-group row">
                                <label for="to" class="col-md-3 col-form-label text-md-right">To</label>

                                <div class="col-md-9">
                                    <input id="sav-to" type="text"
                                        class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}" name="to"
                                        value="{{ old('to') }}" placeholder="To" required>

                                    @if ($errors->has('to'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('to') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>

                                <div class="col-md-9">
                                    <input id="sav-amount" type="text"
                                        class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                        name="amount" value="{{ old('amount') }}" placeholder="Amount" required>

                                    @if ($errors->has('amount'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendVcardModal" tabindex="-1" role="dialog" aria-labelledby="sendVcardModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendVcardModalTitle">Send (vCard)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-md-1 col-md-10">
                        <form action="{{ route('vcard.send') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="to" class="col-md-3 col-form-label text-md-right">To</label>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="country_code">+673</span>
                                        </div>
                                        <input id="vcard-to" type="text"
                                            class="form-control{{ $errors->vcard_send->has('to') ? ' is-invalid' : '' }}"
                                            name="to" value="{{ old('to') }}" placeholder="To" required>
                                    </div>

                                    @if ($errors->vcard_send->has('to'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->vcard_send->first('to') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>

                                <div class="col-md-9">
                                    <input id="vcard-amount" type="number" min="0.00" step="0.01"
                                        class="form-control{{ $errors->vcard_send->has('amount') ? ' is-invalid' : '' }}"
                                        name="amount" value="{{ old('amount') }}" placeholder="Amount" required>

                                    @if ($errors->vcard_send->has('amount'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->vcard_send->first('amount') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description"
                                    class="col-md-3 col-form-label text-md-right">Description</label>

                                <div class="col-md-9">
                                    <input id="vcard-description" type="text" maxlength="15"
                                        class="form-control{{ $errors->vcard_send->has('description') ? ' is-invalid' : '' }}"
                                        name="description" value="{{ old('description') }}" placeholder="Description"
                                        required>

                                    @if ($errors->vcard_send->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->vcard_send->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Send
                                    </button>
                                </div>
                            </div>
                            <small class="col-md-9 offset-md-3 text-center">Please double check before sending.</small>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="generateQRModal" tabindex="-1" role="dialog" aria-labelledby="generateQRModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateQRModalTitle">Generate QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-md-1 col-md-10">
                        <div class="form-group row">
                            <label for="to" class="col-md-3 col-form-label text-md-right">To</label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="country_code">+673</span>
                                    </div>
                                    <input id="qr-to" type="text"
                                        class="form-control{{ $errors->vcard_send->has('to') ? ' is-invalid' : '' }}"
                                        name="to" value="{{ old('to') }}" placeholder="To" required>
                                </div>

                                @if ($errors->vcard_send->has('to'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->vcard_send->first('to') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-md-3 col-form-label text-md-right">Amount</label>

                            <div class="col-md-9">
                                <input id="qr-amount" type="number" min="0.00" step="0.01"
                                    class="form-control{{ $errors->vcard_send->has('amount') ? ' is-invalid' : '' }}"
                                    name="amount" value="{{ old('amount') }}" placeholder="Amount" required>

                                @if ($errors->vcard_send->has('amount'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->vcard_send->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right">Description</label>

                            <div class="col-md-9">
                                <input id="qr-description" type="text" maxlength="15"
                                    class="form-control{{ $errors->vcard_send->has('description') ? ' is-invalid' : '' }}"
                                    name="description" value="{{ old('description') }}" placeholder="Description"
                                    required>

                                @if ($errors->vcard_send->has('description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->vcard_send->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary btn-block" id="generateQRBtn">
                                    Generate
                                </button>
                            </div>
                        </div>
                        <small class="col-md-9 offset-md-3 text-center">Please double check before sharing.</small>
                        <div class="col-md-12 d-flex justify-content-center mt-3">
                            <div id="qrcode"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/jsQR.js') }}"></script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        (function ($) {

            // Init sweetalert2 component
            @component('components.sa')
            @slot('title', session('title'))
            @slot('description', session('description'))
            @slot('status', session('status'))
            @endcomponent

            // Click get pin button
            $(document).on("click", "#getPinBtn", function () {
                $('#pin').text('Loading...');
                $.get('/vcard/pin', function (data, status) {
                    pin = data.pin;
                    $('#pin').text(pin);
                });
            });

            // Click vcard history button
            $(document).on("click", "#getVcardHistoryBtn", function () {
                $.get('/vcard/history/{{ encrypt($vcard->acc_no, false) }}', function (data,
                    status) {
                    vcardhistory = data;
                    console.log(vcardhistory);
                });
            });

            // Click vcard history button
            $(document).on("click", "#generateQRBtn", function () {
                to = $('#qr-to').val();
                amount = $('#qr-amount').val();
                description = $('#qr-description').val();

                data = to + '|' + amount + '|' + description;

                var qrcode = new QRCode("qrcode", {
                    text: 'bibdpoc:' + btoa(data),
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            });

            // Video for QR
            var video = document.createElement("video");
            var canvasElement = document.getElementById("canvas");
            var canvas = canvasElement.getContext("2d");
            var loadingMessage = document.getElementById("loadingMessage");
            var outputContainer = document.getElementById("output");
            var outputMessage = document.getElementById("outputMessage");
            var outputData = document.getElementById("outputData");

            function drawLine(begin, end, color) {
                canvas.beginPath();
                canvas.moveTo(begin.x, begin.y);
                canvas.lineTo(end.x, end.y);
                canvas.lineWidth = 4;
                canvas.strokeStyle = color;
                canvas.stroke();
            }

            $(document).on("click", "#scanQRBtn", function () {
                // Use facingMode: environment to attemt to get the front camera on phones
                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                }).then(function (stream) {
                    video.srcObject = stream;
                    video.setAttribute("playsinline",
                        true); // required to tell iOS safari we don't want fullscreen
                    video.play();
                    requestAnimationFrame(tick);

                    $('#scanQRModal').on('hidden.bs.modal', function (e) {
                        video.pause();
                        video.src = "";
                        stream.getTracks()[0].stop();
                    });
                });
            });



            function tick() {
                loadingMessage.innerText = "⌛ Loading video..."
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    loadingMessage.hidden = true;
                    canvasElement.hidden = false;
                    outputContainer.hidden = false;
                    canvasElement.height = video.videoHeight - 200;
                    canvasElement.width = video.videoWidth - 200;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });
                    if (code) {
                        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
                        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
                        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner,
                            "#FF3B58");
                        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
                        outputMessage.hidden = true;
                        outputData.parentElement.hidden = false;
                        if (code.data.includes('bibdpoc:')) {
                            outputData.innerText = "Found data, redirecting...";
                            $('#scanQRModal').modal('toggle');
                            data = (atob(code.data.split(":")[1])).split('|');
                            $('#vcard-to').val(data[0]);
                            $('#vcard-amount').val(data[1]);
                            $('#vcard-description').val(data[2]);
                            $('#sendVcardModal').modal('toggle')
                        }
                    } else {
                        outputMessage.hidden = false;
                        outputData.parentElement.hidden = true;
                    }
                }
                requestAnimationFrame(tick);
            }
        })(jQuery);
    });

    function copyText(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

</script>
@if(count($errors->vcard_send) > 0)
<script>
    $('#vcard').tab('show');
    setTimeout(function () {
        $('#sendVcardBtn').click();
    }, 500);

</script>
@endif
@endsection
