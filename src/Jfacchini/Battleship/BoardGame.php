<?php

namespace Jfacchini\Battleship;
use Jfacchini\Battleship\Ship\Battleship;
use Jfacchini\Battleship\Ship\Destroyer;
use Jfacchini\Battleship\Ship\Ship;

/**
 * Class BoardGame
 * @package Jfacchini\Battleship
 */
class BoardGame
{
    /** Board game size e.g. 10X10 */
    const SIZE = 10;

    /** @var array */
    private $board;

    /** @var Ship[] */
    private $ships;

    public function __construct()
    {
        $this->board = [];
        for ($i = 0; $i < self::SIZE; $i++) {
            $this->board[] = [];
        }

        $this->ships = [];
        $this->ships[] = Ship::createBattleship();
        for ($i = 0; $i < 2; $i++) {
            $this->ships[] = Ship::createDestroyer();
        }
    }

    /**
     * Check if all ships are sunk
     *
     * @return bool
     */
    public function isFinished()
    {
        foreach ($this->ships as $ship) {
            if (!$ship->isSunk()) {
                return false;
            }
        }

        return true;
    }
}