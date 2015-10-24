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

        $boardProp = new \ReflectionProperty($boardGame, 'board');
        $boardProp->setAccessible(true);
        $this->assertCount(BoardGame::SIZE, $boardProp->getValue($boardGame));

        $shipsProp = new \ReflectionProperty($boardGame, 'ships');
        $shipsProp->setAccessible(true);
        $ships = $shipsProp->getValue($boardGame);

        $this->assertCount(3, $ships);

        $battleship = $ships[0];
        $maxSquaresProp = new \ReflectionProperty($battleship, 'maxSquares');
        $maxSquaresProp->setAccessible(true);
        $this->assertEquals(Ship::BATTLESHIP_MAX_SQUARES, $maxSquaresProp->getValue($battleship));

        for ($i = 1; $i < 3; $i++) {
            $destroyer = $ships[$i];
            $maxSquaresProp = new \ReflectionProperty($destroyer, 'maxSquares');
            $maxSquaresProp->setAccessible(true);
            $this->assertEquals(Ship::DESTROYER_MAX_SQUARES, $maxSquaresProp->getValue($destroyer));
        }
    }
}