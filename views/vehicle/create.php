<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\enums\vehicle\VehicleTypeEnum;

/* @var $this yii\web\View */
/* @var $model app\models\Vehicle */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Vehicle';
$this->params['breadcrumbs'][] = ['label' => 'Vehicles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="vehicle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="vehicle-form">

        <?php $form = ActiveForm::begin([
            'action' => ['vehicle/create'],  // Explicitly set the action to the create method of VehicleController
        ]); ?>

        <?= $form->field($model, 'vehicle_type')->dropDownList(
            array_combine(VehicleTypeEnum::values(), array_map(fn($status) => $status->label(), VehicleTypeEnum::cases())),
            ['prompt' => 'Select Vehicle Type']
        ) ?>

        <?= $form->field($model, 'licence_plate')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'capacity')->input('number') ?>

        <?= $form->field($model, 'active')->checkbox() ?>

        <?= $form->field($model, 'has_specific_drivers')->checkbox() ?>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
