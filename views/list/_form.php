<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Isomap */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="isomap-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isofile')->textInput() ?>

    <?= $form->field($model, 'sharename')->textInput() ?>

    <?= $form->field($model, 'sharedesc')->textInput() ?>

    <?= $form->field($model, 'enable')->DropDownList(['0' => 'No', '1' => 'Sì']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
