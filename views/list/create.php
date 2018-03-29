<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Isomap */

$this->title = Yii::t('app', 'Create Isomap');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Isomaps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="isomap-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
