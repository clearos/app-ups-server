<?php
$app['basename'] = 'ups_server';
$app['version'] = '1.0.0';
$app['release'] = '1';
$app['vendor'] = 'UWS';
$app['packager'] = 'UWS';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('ups_server_app_description');

$app['name'] = lang('ups_server_app_name');
$app['category'] = lang('base_category_network');
$app['subcategory'] = lang('base_subcategory_infrastructure');

$app['controllers']['ups_server']['title'] = $app['name'];
$app['controllers']['settings']['title'] = lang('base_settings');
