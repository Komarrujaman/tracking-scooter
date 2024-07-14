@extends('layout.main', ['title' => 'History'])
@section('content')

<!-- Main Content -->
<div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid py-5">
        <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="card shadow-sm rounded-lg w-100">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold" style="color:black;">Passenger Information</h5>
                </div>
                <div class="card-body" style="color:black;">
                    <h6><i class="bi bi-person-badge"></i> Name : {{$passenger->name}}</h6>
                    <h6><i class="bi bi-scooter"></i> Scooter Number : {{$scooter->scooter}}</h6>
                    <h6><i class="bi bi-pin"></i> Start Time : {{$passenger->start}}</h6>
                    <h6><i class="bi bi-pin-fill"></i> End Time : {{$passenger->end}}</h6>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center mb-4">
            <div class="card shadow-sm rounded-lg w-100" style="max-height: 400px;">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold" style="color:black;">Tracking Location</h5>
                </div>
                <div class="card-body">
                    <!-- Hidden element to store passenger ID -->
                    <div id="passenger-id" data-passenger-id="{{ $passenger->id }}" style="display: none;"></div>
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
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibWFydTEwMDQiLCJhIjoiY2xnNDFobjlrMGwzMDNycWVmYTR2NnJqdyJ9.e9fdx4qNxp-dBV7LMAK9uw';

    // Initialize the map with default settings
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [106.8933407, -6.3018336], // Set initial center to Jakarta
        zoom: 15
    });

    // Data koordinat dari PHP
    var histories = JSON.parse(`<?php echo json_encode($histories) ?>`);

    // Convert histories data to GeoJSON format and get coordinates
    var coordinates = histories.map(function(history) {
        return [parseFloat(history.longitude), parseFloat(history.latitude)];
    });

    var isUserInteracting = false;

    // Track when the user is interacting with the map
    map.on('movestart', function() {
        isUserInteracting = true;
    });

    map.on('moveend', function() {
        isUserInteracting = false;
    });

    // Wait until the map is loaded
    map.on('load', function() {
        if (coordinates.length > 0) {
            // Add markers to the map
            coordinates.forEach(function(coord, index) {
                new mapboxgl.Marker()
                    .setLngLat(coord)
                    .addTo(map);
            });

            // Draw route based on histories data
            if (coordinates.length > 1) {
                getRoute(coordinates);
            }

            // Center the map based on the latest data point
            map.setCenter(coordinates[coordinates.length - 1]);
            map.setZoom(15);
        }
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
        var layers = map.getStyle().layers;
        if (layers) {
            layers.forEach(function(layer) {
                if (layer.id !== 'route' && layer.id.startsWith('marker-')) {
                    if (map.getLayer(layer.id)) {
                        map.removeLayer(layer.id);
                    }
                    if (map.getSource(layer.id)) {
                        map.removeSource(layer.id);
                    }
                }
            });
        }

        // Convert histories data to GeoJSON format and get coordinates
        var coordinates = histories.map(function(history, index) {
            return {
                id: 'marker-' + index,
                coords: [parseFloat(history.longitude), parseFloat(history.latitude)]
            };
        });

        // Add markers to the map
        coordinates.forEach(function(coord) {
            new mapboxgl.Marker()
                .setLngLat(coord.coords)
                .addTo(map);

            map.addLayer({
                id: coord.id,
                type: 'symbol',
                source: {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: coord.coords
                        }
                    }
                },
                layout: {
                    'icon-image': 'marker-15',
                    'icon-size': 1.5
                }
            });
        });

        // Draw route based on histories data
        if (coordinates.length > 1) {
            getRoute(coordinates.map(c => c.coords));
        }

        // Only update center and zoom if the user is not interacting with the map
        if (!isUserInteracting) {
            if (coordinates.length > 0) {
                map.setCenter(coordinates[coordinates.length - 1].coords);
                map.setZoom(15);
            } else {
                map.setCenter(currentCenter);
                map.setZoom(currentZoom);
            }
        }
    }

    // Function to fetch latest histories
    function fetchLatestHistories() {
        var passengerId = document.getElementById('passenger-id').getAttribute('data-passenger-id');

        $.ajax({
            url: '/latest-histories/' + passengerId,
            method: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    updateMap(data);
                }
            },
            error: function(error) {
                console.error('Error fetching latest histories:', error);
            }
        });
    }

    setInterval(fetchLatestHistories, 10000);
</script>
@endsection