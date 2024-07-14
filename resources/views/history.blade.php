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
                                <td>{{ $item->start }}</td>
                                <td>{{ $item->end }}</td>
                                <td>
                                    <button class="btn btn-primary open-map-modal" data-id="{{ $item->id }}">
                                        <i class="fas fa-map"></i>
                                    </button>
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
@endsection

@section('script')
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibWFydTEwMDQiLCJhIjoiY2xnNDFobjlrMGwzMDNycWVmYTR2NnJqdyJ9.e9fdx4qNxp-dBV7LMAK9uw';

    // Initialize the map with default settings
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [106.8933407, -6.3018336], // Set initial center to Jakarta
        zoom: 15
    });

    var currentMarkers = [];
    var isUserInteracting = false;

    // Track when the user is interacting with the map
    map.on('movestart', function() {
        isUserInteracting = true;
    });

    map.on('moveend', function() {
        isUserInteracting = false;
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
            })
            .catch(error => console.error('Error fetching route:', error));
    }

    // Function to update map with new data
    function updateMap(histories) {
        // Save the current center and zoom
        var currentCenter = map.getCenter();
        var currentZoom = map.getZoom();

        // Clear existing markers and route
        currentMarkers.forEach(marker => marker.remove());
        currentMarkers = [];

        if (map.getLayer('route')) {
            map.removeLayer('route');
            map.removeSource('route');
        }

        // Convert histories data to GeoJSON format and get coordinates
        var coordinates = histories.map(function(history) {
            return [parseFloat(history.longitude), parseFloat(history.latitude)];
        });

        // Add markers to the map
        coordinates.forEach(function(coord) {
            var marker = new mapboxgl.Marker()
                .setLngLat(coord)
                .addTo(map);
            currentMarkers.push(marker);
        });

        // Draw route based on histories data
        if (coordinates.length > 1) {
            getRoute(coordinates);
        }

        // Only update center and zoom if the user is not interacting with the map
        if (!isUserInteracting && coordinates.length > 0) {
            map.setCenter(coordinates[coordinates.length - 1]);
            map.setZoom(15);
        } else {
            map.setCenter(currentCenter);
            map.setZoom(currentZoom);
        }
    }

    // Function to fetch latest histories
    function fetchLatestHistories(passengerId) {
        $.ajax({
            url: '/latest-histories/' + passengerId,
            method: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    updateMap(data);
                    document.getElementById('map').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            },
            error: function(error) {
                console.error('Error fetching latest histories:', error);
            }
        });
    }

    // Add event listener to tracking buttons
    document.querySelectorAll('.open-map-modal').forEach(button => {
        button.addEventListener('click', function() {
            var passengerId = this.getAttribute('data-id');
            fetchLatestHistories(passengerId);
        });
    });
</script>
@endsection