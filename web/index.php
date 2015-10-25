<?php

use Jfacchini\Battleship\BoardGame;
use Jfacchini\Battleship\Exception\HitException;
use Silex\Application;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Request;
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

    //TODO: use twig
    $template = <<<TEMPLATE
<!DOCTYPE html>
<html>
    <head>
    <title>Battleship game</title>
    </head>
    <body>
        {{msg}}
        <pre>{{render}}</pre>
        <form action="/hit" method="post">
            Enter coordinates (row, col), e.g. A5 <input type="text" name="coordinates" size="5" autofocus />
            <input type="submit" value="Hit" />
        </form>
    </body>
</html>
TEMPLATE;

    $msg = $session->get('msg');
    $session->remove('msg');
    $template = str_replace('{{msg}}', is_null($msg) ? '' : $msg, $template);

    $show = !is_null($session->get('show'));
    $session->remove('show');
    $template = str_replace('{{render}}', $game->render($show), $template);

    if ($game->isFinished()) {
        $session->remove('game');
        $template = <<<TEMPLATE
<!DOCTYPE html>
<html>
    <head>
    <title>Battleship game</title>
    </head>
    <body>
    Well done! You completed the game in {{N}} shots
    </body>
</html>
TEMPLATE;
        $template = str_replace('{{N}}', $game->getNbShots(), $template);
    } else {
        $session->set('game', $game);
    }

    return $template;
});

$app->post('/hit', function(Request $request) use ($app) {
    $coordinates = $request->request->get('coordinates');
    /** @var Session $session */
    $session = $app['session'];
    /** @var BoardGame $game */
    $game = $session->get('game');

    if (!is_null($coordinates)) {
        if ($coordinates === 'show') {
            $session->set('show', true);
            return $app->redirect('/');
        }

        try {
            $msg  = $game->hit($coordinates);
            $session->set('msg', $msg);
        } catch(HitException $e) {
            $session->set('msg', '*** ERROR ***');
        }
    }

    if ($game->isFinished()) {
        $session->remove('msg');
        $session->set('finish', true);
    }

    return $app->redirect('/');
});

$app->run();