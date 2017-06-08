<?php

namespace TicTacToeGame\ValueObject;

class State
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var int
     */
    private $value;

    /**
     * State constructor.
     * @param int $x
     * @param int $y
     * @param int $value
     */
    public function __construct(int $x, int $y, int $value)
    {
        $this->x = $x;
        $this->y = $y;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getX() : int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY() : int
    {
        return $this->y;
    }

    /**
     * @return int
     */
    public function getValue() : int
    {
        return $this->value;
    }
}