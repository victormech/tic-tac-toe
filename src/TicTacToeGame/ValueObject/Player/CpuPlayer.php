<?php

namespace TicTacToeGame\ValueObject\Player;

use TicTacToeGame\Enum\StateTypeEnum;

class CpuPlayer implements PlayerInterface
{
    public function getValue(): int
    {
        return 10;
    }

    public function getStateType(): int
    {
        return StateTypeEnum::CPU_PLAYER;
    }
}
