#!/bin/php
<?php

use IsaEken\Alo\Alo;
use IsaEken\Alo\Cli;
use IsaEken\Alo\Exceptions\DirectoryNotExistsException;
use IsaEken\Alo\Exceptions\FileNotExistsException;
use IsaEken\Alo\Helpers;

require_once __DIR__ . "/../vendor/autoload.php";

try {
    $cli = new Cli;
    $alo = new Alo;

    $cli->route($alo, collect($argv)->splice(1)->toArray());
} catch (DirectoryNotExistsException| FileNotExistsException $exception) {
    Helpers::output("ERR: File or directory not found: " . $exception->getMessage());
}
