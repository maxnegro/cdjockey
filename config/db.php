<?php

if (YII_ENV_PROD) {
  return [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=cdjockey',
      'username' => 'cdjockey',
      'password' => 'cdjockey',
      'charset' => 'utf8',

      // Schema cache options (for production environment)
      'enableSchemaCache' => true,
      'schemaCacheDuration' => 60,
      'schemaCache' => 'cache',
  ];
} else {
  return [
      'class' => 'yii\db\Connection',
      'dsn' => 'mysql:host=localhost;dbname=cdjockey',
      'username' => 'cdjockey',
      'password' => 'cdjockey',
      'charset' => 'utf8',

      // Schema cache options (for production environment)
      // 'enableSchemaCache' => true,
      // 'schemaCacheDuration' => 60,
      // 'schemaCache' => 'cache',
  ];
}
