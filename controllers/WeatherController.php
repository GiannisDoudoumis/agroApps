<?php

namespace app\controllers;

use app\components\WeatherService;
use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use app\models\Location;
use app\models\WeatherData;

class WeatherController extends Controller
{
    public function actionIndex()
    {
        // Get the location ID from the GET request
        $selectedLocationId = Yii::$app->request->get('location');

        $allLocationsForSelect = Location::find()->select('id, name')->all();

        // Fetch all locations
        $locationsCursor = Location::find();

        // If a location is selected, filter by that location
        if ($selectedLocationId) {
            $locationsCursor = $locationsCursor->andWhere(['id' => $selectedLocationId]);
        }

        // Fetch weather data for each location from all available APIs
        $weatherData = [];
        foreach ($locationsCursor->each() as $location) {
            $weatherData[$location->id] = WeatherData::getWeatherDataFromApis($location);
        }

        return $this->render('index', [
            'allLocationsForSelect' => $allLocationsForSelect,
            'locations' => $locationsCursor->all(),
            'weatherData' => $weatherData,
            'selectedLocationId' => $selectedLocationId,  // Pass the selected location ID
        ]);
    }




    public function actionRefreshWeather($id)
    {

        $service = new WeatherService();
        try {
            $service->fetchWeatherData($id);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error',  $e->getMessage());
            return $this->redirect(['index']);

        }
        Yii::$app->session->setFlash('success', 'Weather data refreshed');
        return $this->redirect(['index']);

    }


}
