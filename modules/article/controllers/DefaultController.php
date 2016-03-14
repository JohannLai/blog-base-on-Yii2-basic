<?php

namespace app\modules\article\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        echo 'hello article';
        //return $this->render('index');
    }
}
