<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
                <?php
                // Get weather data for this location from all APIs
                $weatherData = $weatherData[$location->id];
                foreach ($weatherData as $apiSource => $data):
                    ?>
                    <h4><?= Html::encode($apiSource) ?></h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Max Temperature (°C)</th>
                            <th>Min Temperature (°C)</th>
                            <th>Precipitation (mm)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $weather):
                            $dailyData = Json::decode($weather->daily_data);
                            ?>
                            <tr>
                                <td><?= Html::encode($weather->date) ?></td>
                                <td><?= Html::encode($dailyData['temperature_max']) ?></td>
                                <td><?= Html::encode($dailyData['temperature_min']) ?></td>
                                <td><?= Html::encode($dailyData['precipitation_sum']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </td>
            <td>
                <?= Html::a('Refresh Weather', ['refresh-weather', 'id' => $location->id], ['class' => 'btn btn-warning btn-sm']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
