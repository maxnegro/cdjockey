<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\PendingChanges;
use yii\bootstrap\Alert;
use nirvana\showloading\ShowLoadingAsset;
ShowLoadingAsset::register($this);


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Lista condivisioni');
$this->params['breadcrumbs'][] = $this->title;

if (PendingChanges::hasPendingChanges() > 0) {
  Alert::begin([
    'options' => [
        'class' => 'alert-warning',
    ],
  ]);

  echo 'È necessario rigenerare la configurazione del server. ';
  echo Html::a(Yii::t('app', 'Genera'), ['generate'], ['class' => 'btn btn-success', 'onclick' => '$(\'#wrap\').showLoading()']);

  Alert::end();
}
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
          return [];
        },
        'columns' => [
            'isofile:ntext',
            'sharename:ntext',
            'sharedesc:ntext',
            [
              'attribute' => 'lastupdated',
              'contentOptions' => function($model, $key, $index, $column) {
                if (!$model->fileIsInSync) {
                  return ['class' => 'danger'];
                }
                return [];
              },
            'format' =>  'datetime',
            ],
            'enable:boolean',
            'fileExists:boolean',

            [
              'class' => 'yii\grid\ActionColumn',
              'headerOptions' => [
                'style' => 'width: 4em;',
              ],
              'contentOptions' => ['class' => 'text-center'],
              'template' => '{update} {delete}',
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
            'style' => 'width: 4em;',
          ],
          'contentOptions' => ['class' => 'text-center'],
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
