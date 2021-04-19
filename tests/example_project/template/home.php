<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <style type="text/css">%- include __DIR__ . DIRECTORY_SEPARATOR . '/../assets/app.css' -%</style>
</head>
<body>
<?php include "header.php"; ?>

<div style="margin-top: 1rem; margin-bottom: 1rem;">
    Hello, <?php echo ["Dude", "Buddy", "...", "World"][rand(0, 3)]; ?>!
</div>

<?php include "footer.php"; ?>
</body>
</html>
