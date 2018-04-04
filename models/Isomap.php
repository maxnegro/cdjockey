<?php
namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\BaseStringHelper;
use app\models\PendingChanges;

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
            ['enable', 'filter', 'filter' => 'intval'],
            [['lastupdated', 'fileExists', 'fileIsInSync'], 'safe'],
            // ['lastupdated', 'integer'],
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
            'fileIsInSync' => Yii::t('app', 'File sincronizzato'),
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

    /**
     * Generate autofs map file and link factory
     *
     * @return return void
     */
    public static function generateAutoFsMap() {
      exec(sprintf('rm %s/*', Yii::getAlias(env('IMAGELINKS'))));
      $autofsOutFile = fopen(Yii::getAlias(env('GENERATED') . '/auto.isosrv'), 'w' );
      fwrite($autofsOutFile, "# Generated automatically. Do not edit. It will be rewritten.\n#\n");
      $sambaOutFile = fopen(Yii::getAlias(env('GENERATED') . '/smb-iso.conf'), 'w' );
      fwrite($sambaOutFile, "# Generated automatically. Do not edit. It will be rewritten.\n#\n");
      foreach (Isomap::find()->where(['enable' => 1])->all() as $model) {
        if ($model->fileExists) {
          // $baseFilename = BaseStringHelper::basename($model->isofile, '.iso');
          $baseFilename = $model->sharename;
          // Generate autofs line
          fwrite($autofsOutFile, sprintf(
            "%s\t-fstype=iso9660,ro,loop\t:%s/%s\n",
            $baseFilename,
            Yii::getAlias('@isoImages'),
            $model->isofile
          ));
          // Create link
          // exec(sprintf('ln -s %s %s', Yii::getAlias(env('IMAGEMOUNTS') . '/' . $baseFilename), Yii::getAlias(env('IMAGELINKS') . '/' . $baseFilename) ));
          // Generate smb.conf lines
          fwrite($sambaOutFile, sprintf("[%s]\n", $model->sharename));
          fwrite($sambaOutFile, sprintf("comment = %s\n", $model->sharedesc));
          fwrite($sambaOutFile, sprintf("path = %s\n", Yii::getAlias(env('IMAGEMOUNTS')) . '/' . $baseFilename));
          fwrite($sambaOutFile, sprintf("public = yes\n"));
          fwrite($sambaOutFile, sprintf("writable = no\n"));
          fwrite($sambaOutFile, sprintf("printable = no\n"));
          fwrite($sambaOutFile, sprintf("\n"));
        }
      }
      fclose($autofsOutFile);
      fclose($sambaOutFile);
      exec('sudo service autofs restart');
      exec('sudo service smbd restart');
      exec('sudo service nmbd restart');
    }

    public function afterSave($insert, $changedAttributes) {
      parent::afterSave($insert, $changedAttributes);
      // Isomap::generateAutoFsMap();
      if (!empty($changedAttributes)) {
        PendingChanges::setPendingChanges();
      }
    }

    public function afterDelete() {
      parent::afterDelete();
      // Isomap::generateAutoFsMap();
      PendingChanges::setPendingChanges();
    }

    public function beforeSave($insert)
    {
      if (!parent::beforeSave($insert)) {
          return false;
      }
      $this->lastupdated = filemtime(Yii::getAlias('@isoImages') . '/' . $this->isofile);

      // ...custom code here...
      return true;
    }

    /**
     * Calculated field: iso file is in sync
     *
     * @return return bool
     */
    public function getFileIsInSync() {
      // return (Yii::getAlias('@isoImages') . '/' . $this->isofile) ;
      return filemtime(Yii::getAlias('@isoImages') . '/' . $this->isofile) === $this->lastupdated ? 1 : 0;
    }

}
