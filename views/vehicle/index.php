<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\enums\vehicle\VehicleTypeEnum;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VehicleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vehicles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehicle-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Vehicle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel, // Enable filtering
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            // Vehicle Type filter
            [
                'attribute' => 'vehicle_type',
                'value' => function ($model) {
                    return VehicleTypeEnum::from($model->vehicle_type)->label();
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'vehicle_type',
                    array_combine(
                        array_map(fn($enum) => $enum->value, VehicleTypeEnum::cases()),
                        array_map(fn($enum) => $enum->label(), VehicleTypeEnum::cases())
                    ),
                    ['class' => 'form-control', 'prompt' => 'Select Vehicle Type']
                ),
                'contentOptions' => ['style' => 'text-align: center; width: 150px;'],
            ],

            'licence_plate',
            'capacity',

            // Active filter
            [
                'attribute' => 'active',
                'format' => 'boolean',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'active',
                    [1 => 'Yes', 0 => 'No'],
                    ['class' => 'form-control', 'prompt' => 'Select Active Status']
                ),
                'contentOptions' => ['style' => 'text-align: center; width: 150px;'],
            ],

            // Has Specific Drivers filter
            [
                'attribute' => 'has_specific_drivers',
                'format' => 'boolean',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'has_specific_drivers',
                    [1 => 'Yes', 0 => 'No'],
                    ['class' => 'form-control', 'prompt' => 'Select Has Specific Drivers']
                ),
                'contentOptions' => ['style' => 'text-align: center; width: 150px;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',  // Remove 'view' from the buttons
            ],
        ],
    ]); ?>

</div>
