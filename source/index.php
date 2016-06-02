<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL);

$tstart= microtime(true);

/* this can be used to disable caching in xMOD absolutely */
$modx_cache_disabled= false;

/* include custom core config and define core path */
@include(dirname(__FILE__) . '/config.php');

/* start output buffering */
ob_start();

   
function return503($msg = "Error", $title = "Error 503: Site temporarily unavailable"){
    ob_get_level() && @ob_end_flush();
    header('HTTP/1.1 503 Service Unavailable');
    echo "<html><title>{$title}</title><body><h1>Error 503</h1><p>{$msg}</p></body></html>";
    exit();    
}

/* include the modX class */
if (!@include_once (XMOD_CORE_PATH . "/xmod/classes/xmod.class.php")) return503("Site temporarily unavailable");

// Create xMOD core
$xmod = new xMOD($configuration);

if (!is_object($xmod) || !($xmod instanceof xMOD) || !$xmod->initialize()) return503("Unable to initialize xMOD");

if (!$xmod->start($tstart)) return503("Unable to run execution");

ob_get_level() && @ob_end_flush();