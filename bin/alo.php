#!/bin/php
<?php

use IsaEken\Alo\Alo;
use IsaEken\Alo\Cli;
use IsaEken\Alo\Exceptions\DirectoryNotFoundException;
use IsaEken\Alo\Exceptions\FileNotFoundException;
use IsaEken\Alo\Helpers;

require_once __DIR__ . "/../vendor/autoload.php";

$cli = new Cli;
$alo = new Alo;
$cli->route($alo, collect($argv)->splice(1)->toArray());
