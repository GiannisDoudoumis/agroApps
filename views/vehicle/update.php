<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\enums\vehicle\VehicleTypeEnum;

/* @var $this yii\web\View */
/* @var $model app\models\Vehicle */

$this->title = 'Update Vehicle: ' . $model->licence_plate;  // Set the page title based on vehicle model
$this->params['breadcrumbs'][] = ['label' => 'Vehicles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vehicle-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="vehicle-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'vehicle_type')->dropDownList(
            array_combine(VehicleTypeEnum::values(), array_map(fn($status) => $status->label(), VehicleTypeEnum::cases())),
            ['prompt' => 'Select Vehicle Type']
        ) ?>


        <?= $form->field($model, 'licence_plate')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'capacity')->textInput(['type' => 'number', 'min' => 1]) ?>

        <?= $form->field($model, 'active')->checkbox() ?>

        <?= $form->field($model, 'has_specific_drivers')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
