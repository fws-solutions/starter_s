<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols
/**
 * Theme's entry point.
 *
 * This theme follows declare-or-execute principle so this file should be only PHP file in theme that execute code,
 * all other PHP files (except templates and config files) should be declarative.
 *
 * DO NOT write any scripts here, all features should be written inside FWS directory.
 *
 * @package fws_starter_s
 */


declare(strict_types=1);
use FWS\FWS;
use FWS\Bootstrap;


// constants
const FWS_DIR = __DIR__;


// declare shorthand for FWS service container in global namespace
function fws(): FWS // phpcs:ignore NeutronStandard.Globals.DisallowGlobalFunctions -- only global func in theme
{
    return FWS::getInstance();
}


// run bootstrapper
require_once FWS_DIR . '/fws/app/Bootstrap.php';
Bootstrap::run();
