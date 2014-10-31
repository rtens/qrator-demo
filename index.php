<?php

use blog\Admin;
use watoki\cqurator\web\IndexResource;
use watoki\curir\WebDelivery;

require_once __DIR__ . '/bootstrap.php';

$factory = WebDelivery::init();
Admin::init($factory);

WebDelivery::quickResponse(IndexResource::class, $factory);