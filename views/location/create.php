<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Add New Location';
$this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
$this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="weather-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['id' => 'location-name', 'maxlength' => true]) ?>

    <label>Pick Location on Map:</label>
    <div id="map" style="height: 400px;"></div>

    <?= $form->field($model, 'latitude')->textInput(['id' => 'latitude', 'readonly' => true]) ?>
    <?= $form->field($model, 'longitude')->textInput(['id' => 'longitude', 'readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save Location', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var map = L.map('map').setView([37.9838, 23.7275], 6); // Default to Greece

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        function updateLatLng(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        }

        // Update the name input with just the place's name (not full address)
        function updateLocationName(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.address && data.address.city) {
                        // Use just the city name or the most relevant name part
                        document.getElementById("location-name").value = data.address.city || data.address.town || data.address.village || "Unnamed Location";
                    } else {
                        document.getElementById("location-name").value = "Unnamed Location";
                    }
                })
                .catch(error => console.error('Error fetching location name:', error));
        }

        map.on('click', function (e) {
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
            updateLatLng(e.latlng.lat, e.latlng.lng);
            updateLocationName(e.latlng.lat, e.latlng.lng);
        });

        // Debounce function to delay search on user input
        var debounceTimeout;
        document.getElementById("location-name").addEventListener("input", function () {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function () {
                var locationName = document.getElementById("location-name").value;
                if (locationName.trim() === "") {
                    return; // Don't search if input is empty
                }

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationName)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 10);

                            if (marker) {
                                marker.setLatLng([lat, lon]);
                            } else {
                                marker = L.marker([lat, lon]).addTo(map);
                            }
                            updateLatLng(lat, lon);
                            // Set the name field to just the name, not full address
                            document.getElementById("location-name").value = data[0].display_name.split(',')[0]; // Get the first part of the address (usually the name)
                        } else {
                            alert("Location not found. Try a different name.");
                        }
                    })
                    .catch(error => console.error('Error fetching coordinates:', error));
            }, 1000); // Wait for 1 second after typing stops
        });
    });
</script>
