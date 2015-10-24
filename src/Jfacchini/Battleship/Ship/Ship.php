<?php

namespace Jfacchini\Battleship\Ship;

/**
 * Class Ship
 * @package Jfacchini\Battleship\Ship
 */
class Ship
{
    const BATTLESHIP_MAX_SQUARES = 5;

    const DESTROYER_MAX_SQUARES  = 4;

    /** @var int */
    private $countHit;

    /** @var int */
    private $maxSquares;

    /**
     * @param $maxSquares
     */
    public function __construct($maxSquares)
    {
        $this->countHit   = 0;
        $this->maxSquares = $maxSquares;
    }

    /**
     * Hit the ship
     */
    public function hit()
    {
        $this->countHit++;
    }

    /**
     * Check if the ship sunk
     *
     * @return bool
     */
    public function isSunk()
    {
        return $this->countHit < $this->maxSquares;
    }

    /**
     * Create a new battleship
     *
     * @return Ship
     */
    public static function createBattleship()
    {
        return new Ship(self::BATTLESHIP_MAX_SQUARES);
    }

    /**
     * Create a new destroyer
     *
     * @return Ship
     */
    public static function createDestroyer()
    {
        return new Ship(self::DESTROYER_MAX_SQUARES);
    }
}