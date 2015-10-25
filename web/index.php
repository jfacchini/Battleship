<?php

use Jfacchini\Battleship\BoardGame;
use Silex\Application;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();
$app['debug'] = true;

$app->register(new SessionServiceProvider());

$app->get('/', function() use ($app) {
    /** @var Session $session */
    $session = $app['session'];

    $game = $session->get('game');
    if (is_null($game)) {
        $game = new BoardGame();
        $game->init();
    }

    ob_start();
    var_dump($game);
    $result = ob_get_clean();

    if ($game->isFinished()) {
        $session->remove('game');
    } else {
        $session->set('game', $game);
    }

    return $result;
});

$app->run();