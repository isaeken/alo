#!/bin/php
<?php

use IsaEken\Alo\Alo;
use IsaEken\Alo\Cli;

require_once __DIR__ . "/../vendor/autoload.php";

(new Cli)->route(new Alo, collect($argv)->splice(1)->toArray());
