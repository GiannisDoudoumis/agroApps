<?php
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = 'Weather Locations';
?>

<h1>Weather Locations</h1>

<p><?= Html::a('Add New Location', ['create'], ['class' => 'btn btn-success']) ?></p>

<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Coordinates</th>
        <th>Weather Data</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($locations as $location): ?>
        <tr>
            <td><?= Html::encode($location->name) ?></td>
            <td><?= Html::encode("{$location->latitude}, {$location->longitude}") ?></td>
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
                                    <th>Date</th>
                                    <th>Max Temperature (°C)</th>
                                    <th>Precipitation (mm)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($dailyData['time']) && is_array($dailyData['time'])): ?>
                                    <?php
                                    $today = date('Y-m-d'); // Get today's date
                                    $dailyDates = $dailyData['time'];

                                    // Filter the daily data to include only today and the next 3 days
                                    $filteredDates = array_filter($dailyDates, function($date) use ($today) {
                                        return (strtotime($date) >= strtotime($today) && strtotime($date) < strtotime($today . ' +4 days'));
                                    });

                                    // Sort the dates in ascending order
                                    usort($filteredDates, function($a, $b) {
                                        return strtotime($a) - strtotime($b); // Sort by date
                                    });

                                    foreach ($filteredDates as $index => $date): ?>
                                        <tr>
                                            <td><?= Html::encode($date) ?></td>
                                            <td><?= Html::encode($dailyData['temperature_2m_max'][$index] ?? 'N/A') ?></td>
                                            <td><?= Html::encode($dailyData['precipitation_sum'][$index] ?? 'N/A') ?></td>
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
                                // Initialize variables
                                $hourlyTimes = $hourlyData['time'] ?? [];
                                $hourlyTemps = $hourlyData['temperature_2m'] ?? [];
                                $hourlyHumidity = $hourlyData['relative_humidity_2m'] ?? [];
                                $hourlyWindSpeed = $hourlyData['wind_speed_10m'] ?? [];

                                $previousDate = '';
                                $dateColumns = [];

                                // Collect hourly data for today and the next 3 days
                                foreach ($hourlyTimes as $index => $time) {
                                    $currentDate = substr($time, 0, 10); // Extract date part (YYYY-MM-DD)

                                    // Only show each date once and group hours under it, and ensure we only show up to 4 days
                                    if ($currentDate !== $previousDate) {
                                        if ($previousDate !== '') {
                                            // End previous group of hours
                                        }
                                        $previousDate = $currentDate;
                                        if (strtotime($currentDate) >= strtotime($today) && strtotime($currentDate) < strtotime($today . ' +4 days')) {
                                            $dateColumns[] = $currentDate;
                                        }
                                    }
                                }

                                // Render hourly data with collapsible rows for each day
                                foreach ($dateColumns as $date) {
                                    // Make the button ID unique for both location, API source, and date
                                    $buttonId = "toggle-hours-{$location->id}-{$apiSource}-{$date}"; // Unique button ID per location, API source, and date
                                    $dataId = "hours-{$location->id}-{$apiSource}-{$date}"; // Unique data ID for collapsible section

                                    echo "<tr><td >" . Html::encode($date) . "</td><td colspan='4'>
                                    <button type='button' class='btn btn-info' id='{$buttonId}' onclick='toggleHours(\"{$dataId}\")'>Show/Hide Hours</button>
                                    <div id='{$dataId}' style='display:none; margin-top:10px;' class='hourly-data'>";

                                    // Output hourly data for each day, skipping every 3rd hour (index 0, 3, 6, ...)
                                    foreach ($hourlyTimes as $index => $time) {
                                        $currentDate = substr($time, 0, 10);
                                        if ($currentDate === $date && $index % 3 === 0) { // Skip every 3rd hour
                                            echo "<div class='hour-row'>";

                                            // Extract time portion only (HH:MM)
                                            $timeOnly = substr($time, 11, 5);

                                            echo "<div><strong>Time:</strong> " . Html::encode($timeOnly) . "</div>";
                                            echo "<div><strong>Temp:</strong> " . Html::encode($hourlyTemps[$index] ?? 'N/A') . "°C</div>";
                                            echo "<div><strong>Hum:</strong> " . Html::encode($hourlyHumidity[$index] ?? 'N/A') . "%</div>";
                                            echo "<div><strong>Wind Sp: </strong> " . Html::encode($hourlyWindSpeed[$index] ?? 'N/A') . " km/h</div>";
                                            echo "</div>";
                                        }
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
                </style>

            </td>
            <td>
                <?= Html::a('Refresh Weather', ['refresh-weather', 'id' => $location->id], ['class' => 'btn btn-warning btn-sm']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
