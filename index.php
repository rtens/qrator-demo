<?php

use blog\Admin;
use watoki\qrator\web\IndexResource;
use watoki\curir\WebDelivery;

require_once __DIR__ . '/bootstrap.php';

WebDelivery::errorReporting(true);

$factory = WebDelivery::init();
Admin::init($factory);

WebDelivery::quickResponse(IndexResource::class, $factory);