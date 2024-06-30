@extends('layout.main', ['title' => 'History'])
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
@endsection

@section('script')
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibWFydTEwMDQiLCJhIjoiY2xnNDFobjlrMGwzMDNycWVmYTR2NnJqdyJ9.e9fdx4qNxp-dBV7LMAK9uw';

    // Initialize the map
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [106.8933407, -6.3018336], // Set initial center to Jakarta
        zoom: 14.19
    });

    // Data koordinat dari PHP
    var histories = JSON.parse(`<?php echo json_encode($histories) ?>`);

    // Convert histories data to GeoJSON format and get coordinates
    var coordinates = histories.map(function(history) {
        return [parseFloat(history.longitude), parseFloat(history.latitude)];
    });

    // Add markers to the map
    coordinates.forEach(function(coord) {
        new mapboxgl.Marker()
            .setLngLat(coord)
            .addTo(map);
    });

    // Function to get route from Mapbox Directions API
    function getRoute(coords) {
        var coordinatesStr = coords.map(function(coord) {
            return coord.join(',');
        }).join(';');

        var url = `https://api.mapbox.com/directions/v5/mapbox/driving/${coordinatesStr}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                var route = data.routes[0].geometry.coordinates;
                var geojsonRoute = {
                    type: 'Feature',
                    geometry: {
                        type: 'LineString',
                        coordinates: route
                    }
                };

                // Add route to the map
                if (map.getLayer('route')) {
                    map.getSource('route').setData(geojsonRoute);
                } else {
                    map.addLayer({
                        id: 'route',
                        type: 'line',
                        source: {
                            type: 'geojson',
                            data: geojsonRoute
                        },
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        paint: {
                            'line-color': '#FF0000',
                            'line-width': 5
                        }
                    });
                }

                // Fit map to the route
                var bounds = new mapboxgl.LngLatBounds();
                route.forEach(function(coord) {
                    bounds.extend(coord);
                });
                map.fitBounds(bounds, {
                    padding: 20
                });
            })
            .catch(error => console.error('Error fetching route:', error));
    }

    // Draw route based on histories data
    if (coordinates.length > 1) {
        getRoute(coordinates);
    }
</script>
@endsection