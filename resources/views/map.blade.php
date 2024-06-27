@extends('layout.main')
@section('content')

<!-- Main Content -->
<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid py-5">

        <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body">
                    <div id="map" style="height: 400px; width: 900px;"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
@endsection

@section('script')
<script src="{{asset('asset/js/map.js')}}"></script>
@endsection