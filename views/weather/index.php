<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

$this->title = 'Weather';
?>

<h1>Weather</h1>

<!-- Location Filter Form -->
<?php if (!empty($locations)): ?>
    <div class="location-filter">
        <?= Html::beginForm('', 'get', ['class' => 'form-inline', 'id' => 'locationFilterForm']) ?>
        <?= Html::label('Select Location: ', 'location-select', ['class' => 'mr-2']) ?>
        <?= Html::dropDownList(
            'location',
            $selectedLocationId ?? null,  // The selected location from the controller
            ArrayHelper::map($allLocationsForSelect, 'id', 'name'), // Using all locations here
            [
                'prompt' => 'All Locations', // This will show "All Locations" as the default option
                'class' => 'form-control',
                'id' => 'location-select',  // Add an ID for easy selection in JavaScript
            ]
        ) ?>
        <?= Html::endForm() ?>
    </div>
<?php endif; ?>

<?php if (empty($locations)): ?>
    <!-- Display message when no locations are available -->
    <div class="alert alert-warning">
        <strong>No locations available.</strong> Please add a location to see the weather data.
    </div>
    <!-- Button to go to location index page -->
    <div>
        <?= Html::a('Go to Locations', ['location/index'], ['class' => 'btn btn-primary']) ?>
    </div>
<?php else: ?>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <!-- <th>Coordinates</th> -->
            <th>Weather Data</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($locations as $location): ?>
            <tr>
                <!-- Reduced width for Name and Coordinates columns -->
                <td class="location-name"><?= Html::encode($location->name) ?></td>
                <!-- <td class="location-coordinates"><?= Html::encode("{$location->latitude}, {$location->longitude}") ?></td> -->
                <td>
                    <div class="weather-data-container">
                        <?php
                        $weatherDataForLocation = $weatherData[$location->id] ?? [];

                        foreach ($weatherDataForLocation as $apiSource => $data):
                            if (!$data) {
                                echo "<p>No data available for " . Html::encode($apiSource) . ".</p>";
                                continue;
                            }

                            try {
                                $dailyData = !empty($data->daily_data) ? Json::decode($data->daily_data) : [];
                                $hourlyData = !empty($data->hourly_data) ? Json::decode($data->hourly_data) : [];
                            } catch (\Exception $e) {
                                echo "<p>Error decoding weather data for " . Html::encode($apiSource) . ".</p>";
                                continue;
                            }
                            ?>
                            <div class="weather-api-source">
                                <h4><?= Html::encode($apiSource) ?></h4>

                                <!-- Daily Forecast (Show Today and Next 3 Days Only) -->
                                <h5>Daily Forecast</h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 30%;">Date</th>
                                        <th>Max Temperature (°C)</th>
                                        <th>Precipitation (mm)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($dailyData)): ?>
                                        <?php foreach ($dailyData as $data): ?>
                                            <tr>
                                                <td><?= Html::encode($data['date']) ?></td>
                                                <td><?= Html::encode($data['temperatureMax'] ?? 'N/A') ?></td>
                                                <td><?= Html::encode($data['precipitation'] ?? 'N/A') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3">No daily data available</td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                                <!-- Hourly Forecast (Collapsible) -->
                                <h5>Hourly Forecast (Click to Expand)</h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 30%;">Date</th> <!-- Adjust this width percentage as per your preference -->
                                        <th>Time</th>
                                        <th>Temp (°C)</th>
                                        <th>Hum (%)</th>
                                        <th>Wind (km/h)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $groupedHourlyData = [];

                                    // Group hourly data by day
                                    foreach ($hourlyData as $hourly) {
                                        $date = substr($hourly['timestamp'], 0, 10); // Extract the date (YYYY-MM-DD)
                                        $groupedHourlyData[$date][] = $hourly;
                                    }

                                    foreach ($groupedHourlyData as $date => $hourlyEntries) {
                                        // Unique IDs for collapsing rows
                                        $toggleButtonId = "toggle-hours-{$location->id}-{$apiSource}-{$date}";
                                        $dataContainerId = "hours-{$location->id}-{$apiSource}-{$date}";

                                        echo "<tr>";
                                        echo "<td><strong>" . Html::encode($date) . "</strong></td>";
                                        echo "<td colspan='4'>
                    <button type='button' class='btn btn-info' id='{$toggleButtonId}' onclick='toggleHours(\"{$dataContainerId}\")'>
                        Show/Hide Hours
                    </button>
                    <div id='{$dataContainerId}' style='display:none; margin-top:10px;' class='hourly-data'>";

                                        // Display hourly data for this day
                                        foreach ($hourlyEntries as $hourly) {
                                            echo "<div class='hour-row'>";
                                            echo "<div><strong>Time:</strong> " . substr($hourly['timestamp'], 11, 5) . "</div>";
                                            echo "<div><strong>Temp:</strong> " . Html::encode($hourly['temperature'] ?? 'N/A') . "°C</div>";
                                            echo "<div><strong>Hum:</strong> " . Html::encode($hourly['humidity'] ?? 'N/A') . "%</div>";
                                            echo "<div><strong>Wind Sp:</strong> " . Html::encode($hourly['windSpeed'] ?? 'N/A') . " km/h</div>";
                                            echo "</div>";
                                        }

                                        echo "</div></td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php endforeach; ?>
                    </div>

                    <script>
                        // Function to toggle the display of hourly data
                        function toggleHours(dataId) {
                            var element = document.getElementById(dataId);
                            if (element.style.display === "none") {
                                element.style.display = "block";
                            } else {
                                element.style.display = "none";
                            }
                        }
                    </script>

                    <style>
                        /* Style to align weather data from different API sources side by side */
                        .weather-data-container {
                            display: flex;
                            flex-wrap: wrap;
                        }

                        .weather-api-source {
                            width: 48%; /* Adjust this to fit your layout */
                            margin-right: 2%;
                            margin-bottom: 20px;
                        }

                        .weather-api-source:last-child {
                            margin-right: 0;
                        }

                        /* Reduce size of hourly data */
                        .hourly-data {
                            display: flex;
                            flex-wrap: wrap;
                        }

                        .hour-row {
                            width: 100%;
                            margin-bottom: 5px;
                            display: flex;
                            justify-content: space-between;
                            border: 1px solid #ddd;
                            padding: 5px; /* Reduced padding */
                            margin-top: 5px;
                            border-radius: 5px;
                            font-size: 0.9em; /* Reduced font size */
                        }

                        .hour-row div {
                            width: 22%; /* Adjust as needed for side-by-side view */
                            margin-right: 5%;
                        }

                        .hour-row div:last-child {
                            margin-right: 0;
                        }

                        .hour-row strong {
                            display: inline-block;
                            width: 50%;
                        }

                        /* Set width for name and coordinates columns */
                        .location-name {
                            width: 12%; /* Adjust this value to your preference */
                        }

                        .location-coordinates {
                            width: 12%; /* Adjust this value to your preference */
                        }
                    </style>
                </td>
                <td>
                    <?= Html::a('Refresh Weather', ['refresh-weather', 'id' => $location->id], ['class' => 'btn btn-warning btn-sm']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<script>
    document.getElementById('location-select').addEventListener('change', function() {
        var locationId = this.value;
        var form = document.getElementById('locationFilterForm');

        // Prevent default form submission behavior
        event.preventDefault();

        // Clear the query parameters and only append the selected location
        var url = new URL(window.location.href);
        url.searchParams.delete('location'); // Ensure any existing 'location' parameter is removed
        url.searchParams.append('location', locationId); // Add the new location parameter

        // Redirect to the updated URL with the new location parameter
        window.location.href = url.toString();
    });

</script>