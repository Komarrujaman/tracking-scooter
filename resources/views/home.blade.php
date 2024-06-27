@extends('layout.main', ['title' => 'Home'])
@section('content')

<!-- Main Content -->
<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid py-5">

        <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="card shadow-sm rounded-lg">
                <div class="card-body">
                    <form action="{{route('passenger')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" id="nama" focus class="form-control" placeholder="Passengers Name">
                        </div>

                        <div class="form-inline mb-2">
                            <div class="form-group mr-2">
                                <div class="form-inline">
                                    <select name="scooter_id" id="scooter_id" class="form-control">
                                        <option selected disabled>Choose Scooters</option>
                                        @forelse ($listScooter as $scooter )
                                        <option value="{{$scooter->id}}">{{ $scooter->scooter }}</option>
                                        @empty
                                        <option selected disabled>No Scooter Available</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-inline">
                                    <select name="duration" id="duration" class="form-control">
                                        <option selected disabled>Duration (Hour)</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-inline mb-2">
                            <div class="form-group mr-2">
                                <label for="start" class=" mr-1">Start</label>
                                <input type="datetime-local" name="start" id="start" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="end" class=" mr-1">End</label>
                                <input type="datetime-local" name="end" id="end" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-md btn-primary">Mulai</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold" style="color:black;">Passenger List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>name</th>
                                <th>Scooter</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Tracking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($passengers as $item)
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->scooter }}</td>
                                <td>{{ $item->duration }} Hour</td>
                                <td>
                                    @if ($item->status == 0)
                                    <span class="badge badge-success">Used</span>
                                    @else
                                    <span class="badge badge-danger">Tidak Tersedia</span>
                                    @endif
                                </td>
                                <td>{{ $item->start }}</td>
                                <td>{{ $item->end }}</td>
                                <td><a href="{{url('map', $item->id)}}" class="btn btn-primary"><i class="fas fa-map"></i></a></td>
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