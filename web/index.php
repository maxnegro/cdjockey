<?php


require __DIR__ . '/../vendor/autoload.php';

// Environment
require __DIR__ . '/../common/env.php';

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Bootstrap application
require __DIR__ . '/../common/bootstrap.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
