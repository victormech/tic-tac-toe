<?php

namespace TicTacToeGame\ValueObject\Player;

interface PlayerInterface
{
    /**
     * @return int
     */
    public function getValue() : int;

    /**
     * @return int
     */
    public function getStateType() : int;
}
