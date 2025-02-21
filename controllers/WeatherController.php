<?php

namespace app\controllers;

use app\models\driver\Driver;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

// Make sure to have the Driver model

class WeatherController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'index'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'index'],
                        'allow' => true,
                        'roles' => ['@'], // Only authenticated users can access these actions
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Driver models.
     * Provides search functionality.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Driver();  // Assuming Driver is your model
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); // Use search query params

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Driver model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Driver();

        // If form is submitted and valid
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Redirect to the index page or any other page after successfully creating the driver
            Yii::$app->session->setFlash('success', 'Driver created successfully.');
            return $this->redirect(['index']);
        }

        // Render the create view with model
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Driver model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Driver updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete(); // Delete the model from the database

        // Set a success flash message
        Yii::$app->session->setFlash('success', 'Driver deleted successfully.');

        // Redirect to the index page
        return $this->redirect(['index']);
    }

    /**
     * Finds the Driver model based on its primary key value.
     *
     * @param int $id
     * @return Driver the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Driver::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
