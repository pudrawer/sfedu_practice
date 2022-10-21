#!/usr/bin/php
<?php

define('APP_ROOT', __DIR__);

require APP_ROOT . '/vendor/autoload.php';

$lineResource = new \App\Models\Resource\LineResource();
$brandResource = new \App\Models\Resource\BrandResource();

$lineList = $lineResource->getInformation();
foreach ($lineList as &$line) {
    $brandInfo = $brandResource->getById($line['car_brand_id']);

    $line['brand_name'] = $brandInfo['name'];
    $line['country_id'] = $brandInfo['country_id'];
}

$currentTime = new DateTime();
$currentTime = date('d_m_Y__H_i', $currentTime->getTimestamp());
$fileName = APP_ROOT . '/var/output/lines_models_' . $currentTime . '.csv';

$output = fopen($fileName, 'w');
foreach ($lineList as $line) {
    fputcsv($output, $line);
}
