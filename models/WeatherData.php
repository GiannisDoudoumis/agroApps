<?php

namespace app\models;

use Yii;
use app\models\Location;

/**
 * This is the model class for table "weather_data".
 *
 * @property int $id
 * @property int $location_id
 * @property string $api_source
 * @property string $date
 * @property string $hourly_data
 * @property string $daily_data
 * @property string $created_at
 *
 * @property Location $location
 */
class WeatherData extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'weather_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'api_source', 'date', 'hourly_data', 'daily_data'], 'required'],
            [['location_id'], 'integer'],
            [['date', 'hourly_data', 'daily_data', 'created_at'], 'safe'],
            [['api_source'], 'string', 'max' => 255],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_id' => 'Location ID',
            'api_source' => 'Api Source',
            'date' => 'Date',
            'hourly_data' => 'Hourly Data',
            'daily_data' => 'Daily Data',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    /**
     * Fetch weather data from all APIs for a specific location.
     *
     * @param Location $location
     * @return array
     */
    public static function getWeatherDataFromApis(Location $location)
    {
        $weatherData = [];

        // Get the list of weather APIs from config
        $weatherApis = Yii::$app->params['weather_api_urls'];

        foreach ($weatherApis as $apiName => $apiUrl) {
            // Fetch weather data for each API source dynamically
            $data = self::find()
                ->where(['location_id' => $location->id, 'api_source' => $apiName])
                ->orderBy(['date' => SORT_DESC])
                ->one();

            // Store data only if available
            if ($data) {
                $weatherData[$apiName] = $data;
            }
        }

        return $weatherData;
    }


}
