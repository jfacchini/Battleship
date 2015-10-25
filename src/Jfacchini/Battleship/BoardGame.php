<?php

namespace Jfacchini\Battleship;
use Jfacchini\Battleship\Ship\Ship;

/**
 * Class BoardGame
 * @package Jfacchini\Battleship
 */
class BoardGame
{
    /** Board game size e.g. 10X10 */
    const SIZE = 10;

    /** Free square */
    const FREE = 0;

    /** Hit square */
    const HIT = -1;

    /** Miss square */
    const MISS = -2;

    /** @var array */
    private $board;

    /** @var Ship[] */
    private $ships;

    public function __construct()
    {
        $this->ships = [];
        $this->ships[] = Ship::createBattleship(1);
        for ($i = 2; $i < 4; $i++) {
            $this->ships[] = Ship::createDestroyer($i);
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

    /**
     * Init a new game
     *
     * @return void
     */
    public function init()
    {
        $this->clear();

        foreach ($this->ships as $ship) {
            $this->put($ship);
        }
    }

    /**
     * Clear the current board
     *
     * @return void
     */
    private function clear()
    {
        $this->board = [];
        for ($i = 0; $i < self::SIZE; $i++) {
            $columns = [];
            for ($j = 0; $j < self::SIZE; $j++) {
                $columns[] = self::FREE;
            }
            $this->board[] = $columns;
        }
    }

    /**
     * Put a Ship on the board
     *
     * @param Ship $ship
     *
     * @return void
     */
    public function put(Ship $ship)
    {
        $isPut = false;
        while (!$isPut) {
            $isFree = false;
            $row = 0;
            $col = 0;

            while (!$isFree) {
                $row = rand(0, self::SIZE - 1);
                $col = rand(0, self::SIZE - 1);

                $isFree = $this->isSquareFree($row, $col);
            }

            $isPut = $this->tryUp($ship, $row, $col);
            if (!$isPut) {
                $isPut = $this->tryRight($ship, $row, $col);
            }
            if (!$isPut) {
                $isPut = $this->tryDown($ship, $row, $col);
            }
            if (!$isPut) {
                $isPut = $this->tryLeft($ship, $row, $col);
            }
        }
    }

    /**
     * Check if the square at the given coordinate is free
     *
     * @param  int $row
     * @param  int $col
     * @return bool
     */
    private function isSquareFree($row, $col)
    {
        return ($this->board[$row][$col] === self::FREE);
    }

    /**
     * Try to put the ship to the up direction
     *
     * @param  Ship $ship  Ship to put
     * @param  int  $row X coordinate
     * @param  int  $col Y coordinate
     * @return bool
     */
    private function tryUp(Ship $ship, $row, $col)
    {
        $endRow = $row - ($ship->getSize() - 1);
        // The last square must be on the board
        if ($endRow >= 0) {
            $areSquaresFree = true;
            for ($i = $row; $i >= $endRow; $i--) {
                if (!$this->isSquareFree($i, $col)) {
                    $areSquaresFree = false;
                    break;
                }
            }

            // if all square are free we put the ship
            if ($areSquaresFree) {
                for ($i = $row; $i >= $endRow; $i--) {
                    $this->board[$i][$col] = $ship->getId();
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Try to put the ship to the right direction
     *
     * @param  Ship $ship  Ship to put
     * @param  int  $row X coordinate
     * @param  int  $col Y coordinate
     * @return bool
     */
    private function tryRight(Ship $ship, $row, $col)
    {
        $endCol = $col + ($ship->getSize() - 1);
        // The last square must be on the board
        if ($endCol < self::SIZE) {
            $areSquaresFree = true;
            for ($i = $col; $i <= $endCol; $i++) {
                if (!$this->isSquareFree($row, $i)) {
                    $areSquaresFree = false;
                    break;
                }
            }

            // if all square are free we put the ship
            if ($areSquaresFree) {
                for ($i = $col; $i <= $endCol; $i++) {
                    $this->board[$row][$i] = $ship->getId();
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Try to put the ship to the down direction
     *
     * @param  Ship $ship  Ship to put
     * @param  int  $row X coordinate
     * @param  int  $col Y coordinate
     * @return bool
     */
    private function tryDown(Ship $ship, $row, $col)
    {
        $endRow = $col + ($ship->getSize() - 1);
        // The last square must be on the board
        if ($endRow < self::SIZE) {
            $areSquaresFree = true;
            for ($i = $row; $i <= $endRow; $i++) {
                if (!$this->isSquareFree($i, $col)) {
                    $areSquaresFree = false;
                    break;
                }
            }

            // if all square are free we put the ship
            if ($areSquaresFree) {
                for ($i = $row; $i <= $endRow; $i++) {
                    $this->board[$i][$col] = $ship->getId();
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Try to put the ship to the left direction
     *
     * @param  Ship $ship  Ship to put
     * @param  int  $row X coordinate
     * @param  int  $col Y coordinate
     * @return bool
     */
    private function tryLeft(Ship $ship, $row, $col)
    {
        $endCol = $row - ($ship->getSize() - 1);
        // The last square must be on the board
        if ($endCol >= 0) {
            $areSquaresFree = true;
            for ($i = $col; $i >= $endCol; $i--) {
                if (!$this->isSquareFree($row, $i)) {
                    $areSquaresFree = false;
                    break;
                }
            }

            // if all square are free we put the ship
            if ($areSquaresFree) {
                for ($i = $col; $i >= $endCol; $i--) {
                    $this->board[$row][$i] = $ship->getId();
                }
                return true;
            }
        }

        return false;
    }
}