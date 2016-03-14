<?php
namespace app\components;
use yii\base\Widget;
class TopMenu extends Widget{
    public function init(){
        parent::init();
        echo '<ul>';
    }

    public function run(){
        return '</ul>';
    }

    public function addMenu($menuName){
        return '<li>'.$menuName.'</li>';
    }
}
