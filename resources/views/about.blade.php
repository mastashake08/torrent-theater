@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <p>
                  {{env('APP_NAME')}} is a <a href="https://webtorrent.io">WebTorrent</a> based movie theatre!
                  Simply choose which movie you want to stream (or input the corresponding BitTorrent hash) and begin streaming your
                  favorite movies right in the browser! No Downloads nessacary. Not sure you want to watch a certain movie? Then watch the trailer to get a better feel!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
