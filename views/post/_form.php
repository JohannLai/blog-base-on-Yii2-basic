<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Lookup;
use app\components\CKEditor;
use iutbay\yii2kcfinder\KCFinderInputWidget;
/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


<?= $form->field($model, 'content')->widget(CKEditor::className(),[
        'options' => ['rows' => 8],
        'preset' => 'full'
])?>


<?= $form->field($model, 'images')->widget(KCFinderInputWidget::className(),[
    'multiple' => true,
]); ?>

    <?= $form->field($model, 'tags')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'status')->dropDownList(Lookup::items('PostStatus')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
