<?php
use yii\helpers\Html;

$this->title = 'Locations';
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['depends' => [\yii\web\JqueryAsset::class]]);

// Format the locations data correctly before passing to JavaScript
$locationsFormatted = [];
foreach ($locations as $location) {
    // Ensure latitude and longitude are numeric
    $locationsFormatted[] = [
        'name' => $location->name,
        'latitude' => (float)$location->latitude,
        'longitude' => (float)$location->longitude,
    ];
}

$locationsJson = json_encode($locationsFormatted);
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('Add New Location', ['create'], ['class' => 'btn btn-success']) ?></p>
<!-- Map Container -->
<div id="map" style="height: 500px;"></div>

<!-- Location List Table -->
<table class="table table-bordered" style="margin-top: 20px;">
    <tr>
        <th>Name</th>
        <th>Coordinates</th>
    </tr>
    <?php foreach ($locations as $location): ?>
        <tr>
            <td><?= Html::encode($location->name) ?></td>
            <td><?= Html::encode("{$location->latitude}, {$location->longitude}") ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([37.9838, 23.7275], 6); // Default to Greece

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Locations JSON from PHP
        var locations = <?= $locationsJson ?>;

        console.log('Locations Data:', locations); // Debug: Check if locations are being received

        // Add a marker for each location
        locations.forEach(function(location) {
            var lat = location.latitude;
            var lon = location.longitude;
            var name = location.name;

            // Check if coordinates are valid before adding the marker
            if (lat && lon) {
                L.marker([lat, lon])
                    .addTo(map)
                    .bindPopup('<b>' + name + '</b><br>' + lat + ', ' + lon);
            } else {
                console.log('Invalid coordinates for location:', location);
            }
        });
    });
</script>
