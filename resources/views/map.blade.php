@extends('layout.main', ['title' => 'History'])
@section('content')

<!-- Main Content -->
<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid py-5">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="card shadow-sm rounded-lg w-100" style="max-height: 350px;">
                <div class="card-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div id="map" class="embed-responsive-item" style="max-height: 300px;"></div>
                    </div>
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