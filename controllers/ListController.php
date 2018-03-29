<?php

namespace app\controllers;

use Yii;
use app\models\Isomap;
use app\models\PendingChanges;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\BaseStringHelper;


/**
 * ListController implements the CRUD actions for Isomap model.
 */
class ListController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
              'class' => \yii\filters\AccessControl::className(),
              'only' => ['create', 'update', 'delete', 'generate'],
              'rules' => [
                  // deny all POST requests
                  [
                      'allow' => true,
                      'verbs' => ['POST'],
                      'roles' => ['@'],
                  ],
                  // allow authenticated users
                  [
                      'allow' => true,
                      'roles' => ['@'],
                  ],
                  // everything else is denied
              ],
            ],
        ];
    }

    /**
     * Lists all Isomap models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Isomap::find(),
            'sort' => [
              'attributes' => ['isofile', 'sharename', 'lastupdated'],
              'defaultOrder' => [
                'isofile' => SORT_ASC,
              ],
              'sortParam' => 'isomap-sort',
            ],
            'pagination' => [
              'pageParam' => 'isomap-page',
            ],

        ]);

        $orphansDataProvider = new ArrayDataProvider([
          'allModels' => Isomap::getOrphans(),
          'key' => 'isofile',
          'sort' => [
            'attributes' => ['isofile'],
            'defaultOrder' => [
              'isofile' => SORT_ASC,
            ],
            'sortParam' => 'orphans-sort',
          ],
          'pagination' => [
            'pageParam' => 'orphans-page',
          ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'orphans' => $orphansDataProvider,
        ]);
    }

    /**
     * Displays a single Isomap model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Isomap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = NULL)
    {
        $model = new Isomap();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        if (!empty($id)) {
          $model->isofile = $id;
          $model->sharename = BaseStringHelper::basename($model->isofile, '.iso');
        }
        $model->enable=1;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Isomap model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Isomap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGenerate() {
      PendingChanges::clearPendingChanges();
      Isomap::generateAutoFsMap();
      return $this->redirect(['index']);
    }

    /**
     * Finds the Isomap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Isomap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Isomap::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
