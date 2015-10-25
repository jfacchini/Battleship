<?php

namespace Jfacchini\Battleship\Tests;

use Jfacchini\Battleship\BoardGame;
use Jfacchini\Battleship\Ship\Ship;
use PHPUnit_Framework_TestCase;

/**
 * Class BoardGameTest
 * @package Jfacchini\Battleship\Tests
 */
class BoardGameTest extends PHPUnit_Framework_TestCase
{
    public function testBoardGameConstruct()
    {
        $boardGame = new BoardGame();

        $shipsProp = new \ReflectionProperty($boardGame, 'ships');
        $shipsProp->setAccessible(true);
        $ships = $shipsProp->getValue($boardGame);

        $this->assertCount(3, $ships);

        /** @var Ship $battleship */
        $battleship = $ships[0];
        $sizeProp = new \ReflectionProperty($battleship, 'size');
        $sizeProp->setAccessible(true);
        $this->assertEquals(Ship::BATTLESHIP_SIZE, $sizeProp->getValue($battleship));
        $this->assertFalse($battleship->isSunk());

        for ($i = 1; $i < 3; $i++) {
            /** @var Ship $destroyer */
            $destroyer = $ships[$i];
            $sizeProp = new \ReflectionProperty($destroyer, 'size');
            $sizeProp->setAccessible(true);
            $this->assertEquals(Ship::DESTROYER_SIZE, $sizeProp->getValue($destroyer));
            $this->assertFalse($destroyer->isSunk());
        }
    }

    public function testInitBoard()
    {
        $boardGame = new BoardGame();
        $boardGame->init();

        $boardProp = new \ReflectionProperty($boardGame, 'board');
        $boardProp->setAccessible(true);
        $board = $boardProp->getValue($boardGame);
        $this->assertCount(BoardGame::SIZE, $board);

        $countShipPieces = 0;
        foreach ($board as $columns) {
            $this->assertCount(BoardGame::SIZE, $columns);
            foreach ($columns as $value) {
                if ($value > 0) {
                    $countShipPieces++;
                }
            }
        }

        $shipsProp = new \ReflectionProperty($boardGame, 'ships');
        $shipsProp->setAccessible(true);
        $ships = $shipsProp->getValue($boardGame);

        $countAvailableShipPieces = 0;
        /** @var Ship $ship */
        foreach ($ships as $ship) {
            $sizeProp = new \ReflectionProperty($ship, 'size');
            $sizeProp->setAccessible(true);
            $countAvailableShipPieces += $sizeProp->getValue($ship);
        }

        $this->assertEquals($countAvailableShipPieces, $countShipPieces);
    }

    public function testRenderEmptyBoard()
    {
        $boardGame = new BoardGame();
        $clearMeth = new \ReflectionMethod($boardGame, 'clear');
        $clearMeth->setAccessible(true);
        $clearMeth->invoke($boardGame);

        $expected = <<<Board
  1 2 3 4 5 6 7 8 9 10
A . . . . . . . . . .
B . . . . . . . . . .
C . . . . . . . . . .
D . . . . . . . . . .
E . . . . . . . . . .
F . . . . . . . . . .
G . . . . . . . . . .
H . . . . . . . . . .
I . . . . . . . . . .
J . . . . . . . . . .
Board;

        $this->assertEquals($expected, $boardGame->render());
    }

    //TODO: test rendering a board with ships
}