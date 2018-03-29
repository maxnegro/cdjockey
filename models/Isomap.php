<?php
namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\BaseStringHelper;

/**
 * This is the model class for table "isomap".
 *
 * @property int $id
 * @property string $isofile
 * @property string $sharename
 * @property string $sharedesc
 * @property string $lastupdated
 * @property int $enable
 */
class Isomap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'isomap';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isofile', 'sharename', 'sharedesc'], 'string', 'length' => [4,256]],
            ['isofile', 'trim'],
            ['isofile', 'unique'],
            ['isofile', 'required'],
            ['enable', 'boolean'],
            [['lastupdated'], 'safe'],
            // [['lastupdated'], 'datetime'],
            [['fileExists'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    // public function attributes() {
    //   return array_merge(parent::attributes(), ['fileExists']);
    // }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'isofile' => Yii::t('app', 'File ISO'),
            'sharename' => Yii::t('app', 'Nome condivisione'),
            'sharedesc' => Yii::t('app', 'Descrizione'),
            'lastupdated' => Yii::t('app', 'Ultima modifica'),
            'enable' => Yii::t('app', 'Abilitato'),
            'fileExists' => Yii::t('app', 'File presente'),
        ];
    }

    /**
     * Calculated field: iso file exists
     *
     * @return return bool
     */
    public function getFileExists() {
      // return (Yii::getAlias('@isoImages') . '/' . $this->isofile) ;
      return file_exists(Yii::getAlias('@isoImages') . '/' . $this->isofile) ? 1 : 0;
    }

    /**
     * Helper function: get array of iso files not mapped
     *
     * @return return array
     */
    public static function getOrphans() {
      $_orphans = [];
      foreach(
        FileHelper::findFiles(
          Yii::getAlias('@isoImages'),
          [
            'only' => ['/*.iso'],
            'caseSensitive' => false,
            'recursive' => false
          ]
        )
        as $fname)
      {
        $bname = BaseStringHelper::basename($fname);
        if (!Isomap::findOne(['isofile' => $bname])) {
          $_orphans[] = ['isofile' => $bname];
        }
      }
      return $_orphans;
    }
}
