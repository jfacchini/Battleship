<?php

namespace Jfacchini\Battleship;

use Jfacchini\Battleship\Exception\HitException;
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
     * Render the current board
     *
     * @param  bool $cheat
     * @return string
     */
    public function render($cheat)
    {
        $boardString = ' ';
        for ($i = 0; $i < self::SIZE; $i++) {
            $boardString .= ' '.($i+1);
        }
        $boardString .= "\n";

        foreach ($this->board as $i => $rows) {
            $boardString .= chr($i+65);
            foreach ($rows as $j => $col) {
                $boardString .= ' '.$this->renderSquare($this->board[$i][$j], $cheat);
            }
            if ($i < (self::SIZE - 1)) {
                $boardString .= "\n";
            }
        }

        return $boardString;
    }

    /**
     * Render a specific square
     *
     * @param  int  $square
     * @param  bool $cheat
     * @return string
     */
    private function renderSquare($square, $cheat)
    {
        if ($cheat) {
            if ($square > 0) {
                return 'X';
            } else {
                return ' ';
            }
        }

        switch ($square) {
            case self::HIT:
                return 'X';
            case self::MISS:
                return '-';
            default:
                return '.';
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

    /**
     * Try to hit with a given square
     *
     * @param  string $square
     * @return string
     * @throws HitException
     */
    public function hit($square)
    {
        if (!preg_match('/^[a-jA-J]{1}([1-9]{1}|10)$/', $square)) {
            throw new HitException($square.' is not a valid input');
        }

        $row = ord(substr(strtoupper($square), 0, 1)) - 65;
        $col = intval(substr($square, 1)) - 1;

        /** @var int $choosenSquare */
        $choosenSquare = $this->board[$row][$col];
        if ($choosenSquare > 0) {
            $this->board[$row][$col] = self::HIT;
            /** @var Ship $ship */
            $ship = $this->ships[$choosenSquare - 1];
            $ship->hit();

            if ($ship->isSunk()) {
                return '*** SUNK ***';
            }
        }

        switch ($choosenSquare) {
            case self::FREE:
                $this->board[$row][$col] = self::MISS;
            case self::MISS:
                return '*** MISS ***';
        }

        return '*** HIT ***';
    }
}