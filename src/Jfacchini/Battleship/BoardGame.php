<?php

namespace Jfacchini\Battleship;

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

    public function __construct()
    {
        $this->board = [];
        for ($i = 0; $i < self::SIZE; $i++) {
            $this->board[] = [];
        }
    }

    /**
     * @return array
     */
    public function getBoard()
    {
        return $this->board;
    }
}