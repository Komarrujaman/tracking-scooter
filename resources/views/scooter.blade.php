@extends('layout.main', ['title' => 'Home'])
@section('content')

<!-- Main Content -->
<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid py-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold" style="color:black;">Scooter List</h5>
                <form action="{{route('scooter-create')}}" method="POST">
                    @csrf
                    <div class="form-inline">
                        <input type="text" name="scooter" id="" class="form-control form-control-sm" placeholder="Scooter Name">
                        <button type="submit" class="btn btn-sm btn-primary ml-2">Add New Scooter</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Scooter</th>
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scooter as $item)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $item->scooter }}</td>
                                <td>
                                    @if ($item->status == 1)
                                    <span class="badge badge-success">Available</span>
                                    @else
                                    <span class="badge badge-danger">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-success"><i class="fas fa-pen"></i> Edit</a>
                                    <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

@endsection

@section('script')
<script src="{{asset('asset/js/script.js')}}"></script>
@endsection