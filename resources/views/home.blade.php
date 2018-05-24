@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                  <!--<dashboard-component user-object={{Auth::user()}}></dashboard-component>-->
                  <div align="center" class="embed-responsive embed-responsive-16by9">
                  <video id="video-player" autoplay controls class="embed-responsive-item"></video>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<torrents-component></torrents-component>
<script>
document.getElementById("video-player").addEventListener("play", myFunction);

function myFunction() {
    $('#torrentModal').modal('hide');
}
</script>
@endsection
