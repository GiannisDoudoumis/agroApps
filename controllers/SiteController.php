<?php

namespace app\controllers;

use app\models\SignupForm;
use yii\web\Controller;
class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
