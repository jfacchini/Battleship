<?php

namespace Jfacchini\Battleship\Ship;

/**
 * Class Ship
 * @package Jfacchini\Battleship\Ship
 */
class Ship
{
    /** The size of a battleship */
    const BATTLESHIP_SIZE = 5;

    /** The size of a destroyer */
    const DESTROYER_SIZE  = 4;

    /** @var int */
    private $id;

    /** @var int */
    private $countHit;

    /** @var int */
    private $size;

    /**
     * @param int $id
     * @param int $size
     */
    public function __construct($id, $size)
    {
        $this->id         = $id;
        $this->countHit   = 0;
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
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
        return $this->countHit >= $this->size;
    }

    /**
     * Create a new battleship
     *
     * @param $id
     * @return Ship
     */
    public static function createBattleship($id)
    {
        return new Ship($id, self::BATTLESHIP_SIZE);
    }

    /**
     * Create a new destroyer
     *
     * @param $id
     * @return Ship
     */
    public static function createDestroyer($id)
    {
        return new Ship($id, self::DESTROYER_SIZE);
    }
}