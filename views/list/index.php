<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Lista condivisioni');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="isomap-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a(Yii::t('app', 'Create Isomap'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'=>function($model){
          if ( $model->enable and ! $model->fileExists ) {
            return ['class' => 'danger'];
          } elseif ( !$model->enable and !$model->fileExists ) {
            return ['class' => 'info'];
          } elseif ( !$model->enable and $model->fileExists ) {
            return ['class' => 'warning'];
          }
        },
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'isofile:ntext',
            'sharename:ntext',
            'sharedesc:ntext',
            'lastupdated:datetime',
            'enable:boolean',
            'fileExists:boolean',

            [
              'class' => 'yii\grid\ActionColumn',
              'headerOptions' => [
                'style' => 'width: 5em;',
              ],
            ],
        ],
    ]); ?>

    <h2>File ISO non mappati</h2>
    <?= GridView::widget([
      'dataProvider' => $orphans,
      'columns' => [
        'isofile',
        [
          'class' => 'yii\grid\ActionColumn',
          'headerOptions' => [
            'style' => 'width: 5em;',
          ],
          'template' => '{create}',
          'buttons' => [
            'create' => function($url, $model, $key) {
              return Html::a(
                '<span class="glyphicon glyphicon-plus"></span>',
                $url,
                [
                  'title' => 'Aggiungi',
                  'data-pjax' => 0,
                ]
              );
            }
          ]
        ],
      ]
    ])
    ?>
</div>
