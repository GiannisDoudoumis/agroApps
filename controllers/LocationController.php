<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use app\models\Location;
use yii\filters\VerbFilter;

class LocationController extends Controller
{
    // Define behaviors for access control
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    // Action to list all locations
    public function actionIndex()
    {
        $query = Location::find();

        // Pagination
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $locations = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'locations' => $locations,
            'pagination' => $pagination,
        ]);
    }

    // Action to create a new location
    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Redirect to the index after saving
            Yii::$app->session->setFlash('success', 'Location saved successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

}

    // Action to view details of a location
    public function actionView($id)
    {
        return $this->render('view', [
            'location' => $this->findModel($id),
        ]);
    }

    // Find a location by ID
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested location does not exist.');
    }
}
