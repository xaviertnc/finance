<?php

require $app->vendorsPath . '/OneFile/Logger.php';

$app->logger = new \OneFile\Logger($app->storagePath . '/logs');
