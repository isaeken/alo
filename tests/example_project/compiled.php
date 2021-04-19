<?php

/**
 * This is an example project for ALO.
 */

$title = "Example Project";
$page = "home";

if ($page == "home") {?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <style type="text/css">body {
    background-color: #111111;
    color: #e7e7e7;
}
</style>
</head>
<body>
<?php ?><header>
    <a href="#">Home</a>
    <a href="#">Other Page</a>
    <a href="#">Another Page</a>
</header>
<?php
; ?>

<div style="margin-top: 1rem; margin-bottom: 1rem;">
    Hello, <?php echo ["Dude", "Buddy", "...", "World"][rand(0, 3)]; ?>!
</div>

<?php ?><footer>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab beatae commodi eius et impedit, ipsam labore nihil officiis perspiciatis praesentium quas quia recusandae repudiandae saepe sit temporibus ut? Aperiam, officiis.
</footer>
<?php
; ?>
</body>
</html>
<?php
;
}

