<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pendingchanges".
 *
 * @property int $id
 * @property string $createdAt
 */
class PendingChanges extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pending_changes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createdAt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'createdAt' => Yii::t('app', 'Created At'),
        ];
    }

    public static function setPendingChanges() {
      $model = new Pendingchanges();
      $model->save();
    }

    public static function hasPendingChanges() {
      return PendingChanges::find()->count();
    }

    public static function clearPendingChanges() {
      return PendingChanges::deleteAll();
    }
}
