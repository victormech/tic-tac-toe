<?php

namespace TicTacToeGame\ValueObject\Player;

use TicTacToeGame\Enum\StateTypeEnum;

class HumanPlayer implements PlayerInterface
{
    public function getValue(): int
    {
        return -10;
    }

    public function getStateType(): int
    {
        return StateTypeEnum::HUMAN_PLAYER;
    }
}
