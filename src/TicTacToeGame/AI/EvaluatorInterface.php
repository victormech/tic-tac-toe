<?php

namespace TicTacToeGame\AI;

use TicTacToeGame\ValueObject\State;

interface EvaluatorInterface
{
    public function nextMove() : State;
}
