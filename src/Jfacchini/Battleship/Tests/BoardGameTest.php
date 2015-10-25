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

        $this->assertEquals($expected, $boardGame->render(false));
    }

    //TODO: test rendering a board with ships

    public function testHitShip()
    {
        $board = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 2, 2, 2, 2, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 2, 2, 2, 2, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ];

        $boardGame = new BoardGame();
        $boardProp = new \ReflectionProperty($boardGame, 'board');
        $boardProp->setAccessible(true);
        $boardProp->setValue($boardGame, $board);

        $msg = $boardGame->hit('A5');
        $this->assertEquals('*** MISS ***', $msg);
        $msg = $boardGame->hit('a6');
        $this->assertEquals('*** MISS ***', $msg);
        $msg = $boardGame->hit('I3');
        $this->assertEquals('*** HIT ***', $msg);
        $boardGame->hit('I4');
        $boardGame->hit('i5');
        $msg = $boardGame->hit('i6');
        $this->assertEquals('*** SUNK ***', $msg);

        $board = [
            [0, 0, 0, 0, BoardGame::MISS, BoardGame::MISS, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 1, 0, 0, 2, 2, 2, 2, 0],
            [0, 0, 1, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, BoardGame::HIT, BoardGame::HIT, BoardGame::HIT, BoardGame::HIT, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ];

        $this->assertEquals($board, $boardProp->getValue($boardGame));
    }

    /**
     * @expectedException \Jfacchini\Battleship\Exception\HitException
     */
    public function testBadHit()
    {
        $boardGame = new BoardGame();
        $boardGame->hit('J01');
    }
}