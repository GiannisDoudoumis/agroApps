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
        $locations = Location::find()->all(); // Get all locations

        // Fetch weather data for each location from all available APIs
        $weatherData = [];
        foreach ($locations as $location) {
            $weatherData[$location->id] = WeatherData::getWeatherDataFromApis($location);
        }

        return $this->render('index', [
            'locations' => $locations,
            'weatherData' => $weatherData
        ]);
    }


    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Location saved successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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
