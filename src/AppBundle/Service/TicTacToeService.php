<?php

namespace AppBundle\Service;

use AppBundle\Mapper\TicTacToeGameMapper;
use TicTacToeGame\GameRules;

class TicTacToeService
{
    public function evaluateGameState(GameRules $game) : string
    {
        if (!$game->isGameOver()) {
            $nextTurn = $game->evaluateCpuTurn();
            $game->applyState($nextTurn);
        }

        return $this->mapGameToJson($game);
    }

    public function mapGameData(string $json) : GameRules
    {
        return (new TicTacToeGameMapper())->mapFromJson($json);
    }

    public function mapGameToJson(GameRules $rules) : string
    {
        return (new TicTacToeGameMapper())->mapToJson($rules);
    }
}
