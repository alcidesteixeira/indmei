@extends('layouts.app')

@section('content')

    <style>
        button.disabled:hover {
            cursor: not-allowed;
        }
    </style>

    <div class="container" style="margin-bottom:20px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Escolher mecanismo de gestão de emails:</div>

                    <div class="card-body" style="margin: 0 auto">
                        <button type="button" data-url="http://webmail.indmei.pt/" class="btn btn-default webmail" style="height:64px;">
                            <a href="http://webmail.indmei.pt/" target="_blank"><img src="{{asset('images/email/cpanel.png')}}" style="max-width: 50px"></a></button>
                        <button data-url="https://mail.sapo.pt/" class="btn btn-default">
                            <a href="https://mail.sapo.pt/" target="_blank"><img src="{{asset('images/email/sapo.svg')}}" style="max-width: 50px"></a></button>
                        <button type="button" data-url="https://outlook.live.com/" class="btn btn-default">
                            <a href="https://outlook.live.com/" target="_blank"><img src="{{asset('images/email/outlook.png')}}" style="max-width: 50px"></a></button>
                        <button type="button" data-url="https://www.google.com/gmail/" class="btn btn-default">
                            <a href="https://www.google.com/gmail/" target="_blank"><img src="{{asset('images/email/gmail.png')}}" style="max-width: 50px"></a></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Gerir:</div>

                    <div class="card-body" id="content" style="min-height: 500px">


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $( document ).ready ( function () {
            $( " .webmail" ).click ( function () {
                $("#content").html("");
                $("#content").append("<iframe src='"+$(this).data('url')+"' style='width:100%;min-height:500px;border:none;'></iframe>");
            });
        });
    </script>
@endsection