<?php

namespace app\controllers;
use yii\web\Controller;
use app\models\Test;

class IndexController extends Controller{
    public function actionIndex(){
        return $this->renderPartial('index');
    }
    public function actionMenu(){
        return $this->renderPartial('menu');
    }
}
