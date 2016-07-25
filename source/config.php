<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Системные настройки

$configuration['site']['name']              = 'xMOD Demonstration Site';
$configuration['site']['default_charset']   = 'UTF-8';


$configuration['pathes']['core']            = dirname(__FILE__) . '/core/';
$configuration['pathes']['cache']           = dirname(__FILE__) . '/core/cache/';
$configuration['pathes']['assets']          = dirname(__FILE__) . '/assets/';

// Настройки логов
$configuration['log']['file']               = '/core/logs/error.log';
$configuration['log']['dateformat']         = 'M.Y.d H:i:s';

// Настройки сервера
$configuration['server']['url_scheme']      = 'http://';
$configuration['server']['port']            = $_SERVER['SERVER_PORT'];
$configuration['server']['host']            = $_SERVER['HTTP_HOST'];
$configuration['server']['name']            = $_SERVER['SERVER_NAME'];

// Настройки БД
$configuration['db']['offline']             = false; // Режим работы без подключения к БД
$configuration['db']['type']                = 'mysql';
$configuration['db']['host']                = 'localhost';
$configuration['db']['dbname']              = ' ';
$configuration['db']['user']                = ' ';
$configuration['db']['passwd']              = ' ';
$configuration['db']['prefix']              = 'xmod_';

// Режим отладки
$configuration['debug']['level']            = 5;

if (!defined('XMOD_CORE_PATH')) define('XMOD_CORE_PATH', $configuration['pathes']['core']);

/*
if (!defined('DIRECTORY_SEPARATOR')) {
    define('DIRECTORY_SEPARATOR', '/');
}



if (!defined('XMOD_URL_SCHEME')) {
    define('XMOD_URL_SCHEME', ((!isset($configuration['server']['url_scheme'])) ? $configuration['server']['url_scheme'] : 'http://'));
}

if (!defined('XMOD_SITE_URL')) {
    define('XMOD_SITE_URL', XMOD_URL_SCHEME.$host.$port);
}

if (!defined('XMOD_ASSETS_PATH')) {
    $xmod_assets_path= $_SERVER['DOCUMENT_ROOT'].'/assets/';
    $xmod_assets_url= XMOD_SITE_URL.'/assets/';
    define('XMOD_ASSETS_PATH', $xmod_assets_path);
    define('XMOD_ASSETS_URL', $xmod_assets_url);
}

if (!defined('XMOD_CACHE_DISABLED')) {
    $xmod_cache_disabled= false;
    define('XMOD_CACHE_DISABLED', $xmod_cache_disabled);
}
 
 */