<?php

require_once __DIR__.'/../vendor/autoload.php';

use Jfacchini\Battleship\BoardGame;

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() {
    $board = new BoardGame();

    ob_start();
    var_dump($board->getBoard());
    $result = ob_get_clean();

    return $result;
});

$app->run();