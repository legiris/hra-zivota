<?php
    require_once 'LifeGrid.php';

    $lifeGrid = new LifeGrid();
    $lifeGrid->setInitModel('blinker');
    $lifeGrid->setMaxLoop(5);
    $lifeGrid->loader();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="description" content="" />
    <title>Hra zivota</title>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    
  </body>
</html>