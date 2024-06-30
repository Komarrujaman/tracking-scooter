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
                                    <button type="button" data-toggle="modal" data-target="#edit-{{$item->id}}" class="btn btn-success"><i class="fas fa-pen"></i> Edit</button>
                                    <button data-toggle="modal" data-target="#delete-{{$item->id}}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                </td>
                            </tr>

                            <!-- EDIT MODAL -->
                            <div class="modal fade" id="edit-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Scooter {{$item->scooter}}</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('scooter-edit', $item->id)}}" method="POST">
                                                @csrf
                                                <div class="form-inline">
                                                    <input type="text" name="scooter" id="" class="form-control form-control-sm" value="{{$item->scooter}}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- END EDIT MODAL -->

                            <!-- DELETE MODAL -->
                            <div class="modal fade" id="delete-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Delete Scooter {{ $item->scooter }}
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{route('scooter-delete', $item->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="text" value="{{$item->id}}" hidden>
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END DELETE MODAL -->
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