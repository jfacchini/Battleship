<?php

namespace Jfacchini\Battleship\Tests;

use Jfacchini\Battleship\BoardGame;
use PHPUnit_Framework_TestCase;

/**
 * Class BoardGameTest
 * @package Jfacchini\Battleship\Tests
 */
class BoardGameTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $boardGame = new BoardGame();

        $this->assertCount(BoardGame::SIZE, $boardGame->getBoard());
    }
}