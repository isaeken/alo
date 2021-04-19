<?php

/**
 * This is an example project for ALO.
 */

$title = "Example Project";
$page = "home";

if ($page == "home") {
    require_once __DIR__ . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "home.php";
}

